<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('login', 'LoginController@authenticate');
    Route::resource('post', 'PostController');

    // Route::middleware('auth')->get('/profile', function (Request $request) {
    //     return $request->user();
    // });

    Route::get('user/profile', function () {
        return Auth::user();
    })->middleware('auth');

    Route::resource('user', 'UserController')->middleware('auth');
    Route::resource('comment', 'CommentController');

});

