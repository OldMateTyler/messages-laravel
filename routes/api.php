<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    //get all users messages
    Route::get('messages', 'App\Http\Controllers\MessageController@show')->middleware('auth:sanctum');;

    //get users threads
    Route::get('user/threads', 'App\Http\Controllers\ThreadController@getUsersThreads')->middleware('auth:sanctum');

    //get threads messages
    Route::get('{thread_id}/messages','App\Http\Controllers\MessageController@getThreadMessages')->middleware('auth:sanctum');

    //get id
    Route::get('messages/{message_id}', 'App\Http\Controllers\MessageController@getMessageByID')->middleware('auth:sanctum');
    Route::get('threads/{thread_id}', 'App\Http\Controllers\ThreadController@getThreadByID')->middleware('auth:sanctum');
    Route::get('users/{user_id}', 'App\Http\Controllers\UserController@getUserByID')->middleware('auth:sanctum');

    //update 
    Route::patch('threads/{thread_id}', 'App\Http\Controllers\ThreadController@updateThread')->middleware('auth:sanctum');
    Route::patch('users', 'App\Http\Controllers\UserController@updateUser')->middleware('auth:sanctum');

    //delete
    Route::delete('messages/{message_id}', 'App\Http\Controllers\MessageController@deleteMessageByID')->middleware('auth:sanctum');
    Route::delete('threads/{thread_id}', 'App\Http\Controllers\ThreadController@deleteThreadByID')->middleware('auth:sanctum');
    Route::delete('users', 'App\Http\Controllers\UserController@deleteUserByID')->middleware('auth:sanctum');
    
    //post 
    Route::post('messages', 'App\Http\Controllers\MessageController@store')->middleware('auth:sanctum');
    Route::post('threads', 'App\Http\Controllers\ThreadController@store')->middleware('auth:sanctum');;

    //authorisation api routes
    Route::post('signin', 'App\Http\Controllers\AuthController@signIn');
    Route::post('signup', 'App\Http\Controllers\AuthController@signUp');
    Route::post('signout', 'App\Http\Controllers\AuthController@signOut');
    Route::get('check', 'App\Http\Controllers\AuthController@check');
});