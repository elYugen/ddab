<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check())
        {
           return redirect()->route('dashboard.index');
        }

        return view('dashboard.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([ 
            'email' => ['required', 'email'], 
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->deleted) {
            return back()->withErrors([
                'email' => 'Ce compte a été désactivé',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([ 
            'email' => 'Adresse mail ou mot de passe incorrect',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }  
}
