<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show(Request $request)
    {
    	$users = User::select('users.id', 'users.name')->where('id', '!=', $request->user('sanctum')->id)->get();

    	return response()->json([$users
    	], 200);
    }
    public function getCurrentUser(Request $request){
        $user = User::find($request->user('sanctum')->id);
        if($user){
            return response()->json([
                $user
            ], 200);
        }
    }
    public function getUserByID($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            return response()->json([
                'users' => $user
            ], 200);
     } else {
      return response()->json([
                'type' => 'user',
                'message' => 'Not Found'
            ], 404);
     }
    }
    public function deleteUserByID(Request $request)
    {
        $user = User::find($request->user('sanctum')->id);
        if ($user) {
            $user->delete();
            return response()->json([], 204);
        } else {
            return response()->json([
                'type' => 'user',
                'message' => 'Not Found'
            ], 404);
        }
    }
    public function updateUser(Request $request){
        $user = User::find($request->user('sanctum')->id);
        if ($user) {
              $user->name = $request->name;
              $user->email = $request->email;
              $user->user_img = $request->user_img;
              $user->save();
              return response()->json([], 200);
        } else {
            return response()->json([
                'type'=> 'users',
                'message' => "Not found"
            ],404);
        }
    }
}
