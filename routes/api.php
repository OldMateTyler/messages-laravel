<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    //get all
    Route::get('messages', 'App\Http\Controllers\MessageController@show');
    Route::get('threads', 'App\Http\Controllers\ThreadController@show');
    Route::get('users', 'App\Http\Controllers\UserController@show');

    //get users threads
    Route::get('{user_id}/threads', 'App\Http\Controllers\ThreadController@getUsersThreads');

    //get threads messages
    Route::get('{thread_id}/messages','App\Http\Controllers\MessageController@getThreadMessages');

    //get id
    Route::get('messages/{message_id}', 'App\Http\Controllers\MessageController@getMessageByID');
    Route::get('threads/{thread_id}', 'App\Http\Controllers\ThreadController@getThreadByID');
    Route::get('users/{user_id}', 'App\Http\Controllers\UserController@getUserByID');

    //update 
    Route::patch('threads/{thread_id}', 'App\Http\Controllers\ThreadController@updateThread');
    Route::patch('users/{user_id}', 'App\Http\Controllers\UserController@updateUser');

    //delete
    Route::delete('messages/{message_id}', 'App\Http\Controllers\MessageController@deleteMessageByID');
    Route::delete('threads/{thread_id}', 'App\Http\Controllers\ThreadController@deleteThreadByID');
    Route::delete('users/{user_id}', 'App\Http\Controllers\UserController@deleteUserByID');
    
    //post 
    Route::post('messages', 'App\Http\Controllers\MessageController@store');
    Route::post('threads', 'App\Http\Controllers\ThreadController@store');
    Route::post('users', 'App\Http\Controllers\UserController@store');
});