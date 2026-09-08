<?php

namespace App\Http\Controllers;

use App\Models\CompanyUserMapping;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    // View Login Page
    public function viewLogin(): View {
        return view('pages.auth.login');
    }

    // Handle Login Reuqest
    public function handleLogin(Request $request): RedirectResponse {

        try {

            $validator = Validator::make($request->all(),[
                'email' => ['required', 'string', 'email', 'min:7', 'max:100', 'exists:users'],
                'password' => ['required', 'string', 'min:4', 'max:20'],
                'remember' => ['nullable', 'boolean']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), ], $request->input('remember'))) {
                return redirect()->route('view.dashboard')->with('message',[
                    'status' => 'success',
                    'message' => 'Logged in successfully'
                ]);
            }

            return redirect()->back()->withErrors([
                'password' => ['Wrong password']
            ])->withInput($request->only('email', 'remember'));
            
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

    }

    // View Invite Page
    public function viewInvite(string $token): View|RedirectResponse
    {
        $invite = Invite::where('token', $token)->first();

        if (!$invite) {
            return abort(404, 'Invite not found.');
        }

        if ($invite->expires_at->isPast()) {
            return abort(404, 'Invite not found.');
        }

        if (User::where('email', $invite->email)->exists()) {
            return abort(404, 'Invite not found.');
        }

        return view('pages.auth.invite-register', [
            'invite' => $invite,
        ]);
    }

    // Handle Register From Invite
    public function handleRegisterFromInvite(Request $request, $token): RedirectResponse 
    {
        try {

            $validator = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'string', 'email', 'min:7', 'max:100', 'unique:users'],
                'password' => ['required', 'string', 'min:4', 'max:20', 'confirmed'],
            ]);

            if ($validator->fails()) {
            dd($validator->errors());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $invite = Invite::where('token', $token)->first();

            $role = Role::where('id', $invite->role_id)->first();

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $invite->email;
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $user->assignRole($role);

            $user_company_mapping = new CompanyUserMapping();
            $user_company_mapping->user_id = $user->id;
            $user_company_mapping->company_id = $invite->company_id;
            $user_company_mapping->save();

            Auth::login($user);

            return redirect()->route('view.dashboard')->with('message',[
                'status' => 'success',
                'message' => 'Registred successfully'
            ]);
            
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

    }
    
}
