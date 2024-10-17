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
        $user = User::create($incomingFields);
        auth()->login($user); // sends the cokkie session value
        return redirect('/')->with('success', 'Thank you for registering.');
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
            return redirect('/')->with('success', 'You have successfully logged in');
        } else {
            return redirect('/')->with('failure', 'Invalid login.');

        }
    }

    // POST Logout
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'You are logged out');;
    }


    // PROFILE//
    public function profile(User $user) {
        // $thePosts = $user->posts()->get();
        // return $thePosts;
        return view('profile-posts', [
            'username' => $user->username,
            'posts' => $user->posts()->latest()->get(),
            'postCount' => $user->posts()->count()
        ]);
    }

    // SHOW AVATAR Form
    public function showAvatarForm() {
        return view('avatar-form');
    }

    // STORE Avatar
    public function storeAvatar(Request $request) {
        $request->file('avatar')
            ->store('public/avatars');
    }
}
