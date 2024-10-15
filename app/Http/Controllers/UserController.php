<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    // GET view
    public function showCorrectHomepage() {
        if(auth()->check()) {
            return view('homepage-feed');
        }else {
            return view('homepage');
        };
    }

    // POST Register
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        $incomingFields['password'] = 
        User::create($incomingFields);
        return 'register';
    }

    // POST Login
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);

        if(auth()->attempt([
                'username' => $incomingFields['loginusername'],
                'password' => $incomingFields['loginpassword'],
            ])) 
        {
            $request->session()->regenerate();
            return 'logged in';
        } else {
            return 'error loggin in';

        }
    }
}
