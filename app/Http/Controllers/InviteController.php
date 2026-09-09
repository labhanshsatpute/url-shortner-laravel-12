<?php

namespace App\Http\Controllers;

use App\Enums\InviteStatus;
use App\Enums\Permissions\UserPermission;
use App\Models\Company;
use App\Models\CompanyUserMapping;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class InviteController extends Controller
{
    // View All Users
    public function viewAllUsers(): mixed {

        $user = User::find(Auth::id());

        if ($user->hasPermissionTo(UserPermission::VIEW_ALL_COMPANY_USERS->value)) {
            $users = User::with(['companies', 'short_urls'])->paginate(10);
            $companies = Company::all();
            $roles = Role::all();
            
            return view('pages.users.list', [
                'users' => $users,
                'companies' => $companies,
                'roles' => $roles,
            ]);
        }

        if ($user->hasPermissionTo(UserPermission::VIEW_SELF_COMPANY_USERS->value)) {

            $company_ids = CompanyUserMapping::where('user_id', $user->id)
            ->pluck('company_id');

            $companies = Company::whereIn('id', $company_ids)->get();
            $roles = Role::whereIn('name', ['Admin', 'Member'])->get();

            $users = User::whereHas('company_mappings', function ($query) use ($company_ids) {
                $query->whereIn('company_id', $company_ids);
            })->with(['companies', 'short_urls'])->paginate(10);

            return view('pages.users.list', [
                'users' => $users,
                'companies' => $companies,
                'roles' => $roles,
            ]);
        }
        
        return abort(403, 'Unauthorized action.');
    }

    // Handle Create Invite
    public function handleCreateInvite(Request $request): RedirectResponse {

        try {

            $validator = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:2', 'max:250'],
                'email' => ['required', 'string', 'min:2', 'max:250'],
                'company_id' => ['required', 'string', 'exists:companies,id'],
                'role_id' => ['required', 'string', 'exists:roles,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('message', [
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ])->withInput();
            }

            $invite = new Invite();
            $invite->name = $request->input('name');
            $invite->email = $request->input('email');
            $invite->company_id = $request->input('company_id');
            $invite->role_id = $request->input('role_id');
            $invite->invited_by = Auth::id();
            $invite->token = bin2hex(random_bytes(16));
            $invite->expires_at = now()->addDays(7);
            $invite->save();

            return redirect()->route('view.all.users')->with('message', [
                'status' => 'success',
                'message' => 'Invite Link : ' . route('view.invite',['token' => $invite->token])
            ]);

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    // View Invite Page
    public function viewInviteRequest(string $token): View|RedirectResponse
    {
        $invite = Invite::where('token', $token)->first();

        if (!$invite) {
            return abort(404, 'Invite not found.');
        }

        if ($invite->expires_at->isPast()) {
            return abort(404, 'Invite Expired');
        }

        return view('pages.users.invite-request', [
            'invite' => $invite,
        ]);
    }

    // Handle Invite Request
    public function handleInviteRequest(Request $request, $token): RedirectResponse 
    {
        try {

            $validator = Validator::make($request->all(),[
                'status' => ['required', 'string', new Enum(InviteStatus::class)],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $invite = Invite::where('token', $token)->first();

            if ($request->input('status') == InviteStatus::ACCEPT->value) {
                $user_company_mapping = new CompanyUserMapping();
                $user_company_mapping->user_id = Auth::id();
                $user_company_mapping->company_id = $invite->company_id;
                $user_company_mapping->save();

                $invite->delete();

                return redirect()->route('view.dashboard')->with('message',[
                    'status' => 'success',
                    'message' => 'Invite accepted'
                ]);
            }
            else {
                $invite->delete();
                return redirect()->route('view.dashboard')->with('message',[
                    'status' => 'error',
                    'message' => 'Invite rejected'
                ]);
            }
            
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

    }

    
}
