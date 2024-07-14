<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Friendship;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    $friend_requests = Friendship::where('friend_id', $user->id)
        ->where('accepted', false)
        ->get();

    $friends = DB::select("
                        SELECT COUNT(*) AS friends_count
                        FROM friendship
                        WHERE (user_id = ? OR friend_id = ?)
                        AND accepted = 1
                    ", [$user->id, $user->id]);

    $friends_count = $friends[0]->friends_count;

    return view('dashboard')->with([
        'friend_requests' => $friend_requests,
        'friends_count' => $friends_count,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Facebook Authentication Routes
Route::get('auth/facebook', [SocialController::class, 'facebookRedirect'])->name('login.facebook');
Route::get('auth/facebook/callback', [SocialController::class, 'facebookCallback']);

Route::get('/user/search', [ProfileController::class, 'search'])->name('user.search');

// show single profile
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// route for sending friend request
Route::post('/profile/{user}/add', [ProfileController::class, 'addFriend'])->name('profile.add');

// route for accepting friend request
Route::post('/profile/{user}/accept', [ProfileController::class, 'acceptFriend'])->name('profile.accept');

// route for rejecting friend request
Route::post('/profile/{user}/decline', [ProfileController::class, 'rejectFriend'])->name('profile.reject');

// route for showing friends list
Route::get('/profile/friends/list', [ProfileController::class, 'friendsList'])->name('profile.friends');

// API for showing friends list
Route::get('/users/friends/list', [UserController::class, 'index']);

require __DIR__.'/auth.php';
