<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (Auth::user()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function login()
    {
        if (Auth::user()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function register_store(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        if (Auth::attempt($validator)) {
            Auth::login($user, true);
        }
        $messages['success'] = 'Successfully logged in!';
        return redirect('dashboard')->with('messages', $messages);
    }

    public function login_store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            Auth::login($user, true);
            $messages['success'] = 'Logged in Successfully!';
            return redirect("dashboard")->with('messages', $messages);
        }
        $messages['warning'] = 'The provided credentials do not match our records.';
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->with('messages', $messages);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
