<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        $data = Socialite::driver('facebook')->user();
        // dd("This is the user",$data);

        // dd("This is the token",$token);
        $this->_registerOrLoginUser($data);

        return redirect()->route('dashboard');
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->facebook_id = $data->id;
            $user->password = md5(uniqid());
            $user->save();
        }

        Auth::login($user);
    }
}
