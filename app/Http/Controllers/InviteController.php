<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{
    // View All Users
    public function viewAllUsers(): mixed {

        $user = User::find(Auth::id());

        if ($user->hasPermissionTo(UserPermission::VIEW_ALL_COMPANY_USERS->value)) {
            $users = User::all();
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

            $records = CompanyUserMapping::whereIn('company_id', $company_ids)
                ->with(['user', 'company'])
                ->get();

            $roles = Role::whereIn('name', ['Admin','Member'])->get()   ;

            $users = $records->pluck('user')->unique('id')->values();
            $companies = $records->pluck('company')->unique('id')->values();

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
                'email' => ['required', 'string', 'min:2', 'max:250', 'unique:users'],
                'company_id' => ['required', 'string', 'exists:companies,id'],
                'role_id' => ['required', 'string', 'exists:roles,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $invite = new Invite();
            $invite->email = $request->input('email');
            $invite->company_id = $request->input('company_id');
            $invite->role_id = $request->input('role_id');
            $invite->invited_by = Auth::id();
            $invite->token = bin2hex(random_bytes(32));
            $invite->expires_at = now()->addDays(7);
            $invite->save();

            return redirect()->route('view.all.users')->with('message', [
                'status' => 'success',
                'message' => 'Invite successfully sent'
            ]);

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    
}
