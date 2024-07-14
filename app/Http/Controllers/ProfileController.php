<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\FriendReuqest;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('query');

        // dd($searchQuery);
        $users = User::where('name', 'like', '%' . $searchQuery . '%')->get();

        // Search but exclude the logged in user
        $users = $users->reject(function ($user) {
            return $user->id === Auth::id();
        });
        return view('profile.search', compact('users'));
    }

    public function show(User $user)
    {
        

        // check if this user is already a friend
        $isFriend_left = Friendship::where('user_id', Auth::id())
            ->where('friend_id', $user->id)
            ->where('accepted', true)
            ->get();
        

        $isFriend_right = Friendship::where('friend_id', Auth::id())
            ->where('user_id', $user->id)
            ->where('accepted', true)
            ->get();

        $isFriend = count($isFriend_left) + count($isFriend_right);

        // dd($isFriend_left, $isFriend_right);

        // dd(Auth::id(),$user, $isFriend);


        $mutualFriends = $this::_getUserFriends($user)
            ->intersect($this::_getUserFriends(Auth::user()));

        // Exclude the logged in user and the user whose profile is being viewed
        $mutualFriends = $mutualFriends->reject(function ($friend) use ($user) {
            return $friend->id === Auth::id() || $friend->id === $user->id;
        });

        // dd($mutualFriends);

        
        return view('profile.show', [
            'user' => $user,
            'isFriend' => $isFriend,
            'mutualFriends' => $mutualFriends,
            'countMutualFriends' => count($mutualFriends),
        ]);
    }

    public function addFriend(User $user)
    {
        // get logged in user
        $authUser = Auth::user();

        Friendship::create([
            'user_id' => $authUser->id,
            'friend_id' => $user->id,
        ]);
        
        try {
            Mail::to($user->email)->send(new FriendReuqest());
        } catch (\Throwable $th) {
            // Do nothing
        }
        

        //return to dashboard
        return Redirect::route('dashboard');
    }


    public function acceptFriend(User $user)
    {
        // dd($user);
        // get logged in user
        $authUser = Auth::user();

        Friendship::where('user_id', $user->id)
            ->where('friend_id', $authUser->id)
            ->update([
                'accepted' => true,
            ]);

        //return to dashboard
        return Redirect::route('dashboard');
    }

    public function friendsList ()
    {
        // dd("This is the friends list hola");
        $user = Auth::user();

        $friends = $this::_getUserFriends($user);
        
        // Exclude the logged in user
        $friends = $friends->reject(function ($friend) {
            return $friend->id === Auth::id();
        });

        // dd($friends);

        return view('profile.friends', compact('friends'));
    }

    public static function _getUserFriends($user)
    {
        $friend_id_list = Friendship::where('user_id', $user->id)
            ->orWhere('friend_id', $user->id)
            ->accepted()
            ->get();

        $friends = User::whereIn('id', $friend_id_list->pluck('user_id'))
            ->orWhereIn('id', $friend_id_list->pluck('friend_id'))
            ->get();

        return $friends;
    }
}
