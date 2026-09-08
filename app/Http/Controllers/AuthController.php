<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
