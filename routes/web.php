<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');

// ADMIn-Only
Route::get('/admins-only', function() {
   if(Gate::allows(('visitAdminPages'))) {
    return 'Admin Only Access';
   }
   return 'You cannot view this page';
});


// AUTH
Route::post('/register', [UserController::class, "register"])
    ->middleware('guest');
Route::post('/login', [UserController::class, "login"])
    ->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])
    ->middleware('mustBeLoggedIn');

// BLOG Posts
Route::get('/create-post', [PostController::class, 'showCreateForm'])
  ->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])
  ->middleware('mustBeLoggedIn');

Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->middleware('mustBeLoggedIn');
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');

Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');


// PROFILE 
// search 'user' based on 'username' {user:username}
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
