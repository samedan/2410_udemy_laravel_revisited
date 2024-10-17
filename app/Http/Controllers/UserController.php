<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $this->getShareProfileData($user);     
        return view('profile-posts', [
            'posts' => $user->posts()->latest()->get(),
        ]);
    }
    // PROFILE Followers
    public function profileFollowers(User $user) {
        $this->getShareProfileData($user);  
        // return $user->followersOfMe()->latest()->get();
        return view('profile-followers', [
            'followers' => $user->followersOfMe()->latest()->get(),
        ]);
    }
    // PROFILE FollowING
    public function profileFollowing(User $user) {  
        $this->getShareProfileData($user);           
        return view('profile-following', [
            'following' => $user->followingTheseUsers()->latest()->get(),
        ]);
    }

    // Shared Profile function
    private function getShareProfileData($user) {
        $currentlyFollowing = 0; 
        if(auth()->check()) {
            $currentlyFollowing = Follow::where([
                ['user_id', '=', auth()->user()->id],
                ['followeduser', '=', $user->id]
            ])->count(); // BOOLEAN
        }
        View::share('sharedProfileData', [
            'username' => $user->username,            
            'postCount' => $user->posts()->count(),
            'followersCount' => $user->followersOfMe()->count(),
            'followingCount' => $user->followingTheseUsers()->count(),
            'avatar' => $user->avatar,
            'currentlyFollowing' => $currentlyFollowing
        ]);
    }

    // SHOW AVATAR Form
    public function showAvatarForm() {
        return view('avatar-form');
    }

    // STORE Avatar
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        
        $user = auth()->user();
        $filename = $user->id . "-" . uniqid() . ".jpg";

        // RESIZE IMAGE
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('avatar'));
        // crop the image
        $imgData = $image->cover(120,120)->toJpeg();
        // folder + filename
        Storage::put("public/avatars/". $filename , $imgData);

        // OLD avatar delete
        $oldAvatar = $user->avatar;
        // Update Database
        $user->avatar = $filename;
        $user->save();
        // delete old avatar if New Avatar
        if($oldAvatar != "/fallback-avatar.jpg") {
            // Transform 
            // /storage/avatars/12345.jpg
            // public/avatars/12345.jpg
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with('success', 'Avatar updated succesfully.');
    }
}
