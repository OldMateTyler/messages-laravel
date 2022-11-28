<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show()
    {
    	$users = User::get();

    	return response()->json([
    		'users' => $users
    	], 200);
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
    public function deleteUserByID($user_id)
    {
        $user = User::find($user_id);
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
    public function updateUser(Request $request, $user_id){
        $user = User::find($user_id);
        if ($user) {
              $req = $request->getContent();
              $name = explode(':"', $req)[1];
              $email = explode('":', $name)[1];
              $name = explode('"', $name)[0];
              $email = str_replace(array('}', '"'), '', $email);
              $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
              $user->name = $name;
              $user->email = $email;
              $user->save();
              return response()->json([], 200);
        } else {
            return response()->json([
                'type'=> 'users',
                'message' => "Not found"
            ],404);
        }
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|unique:users',
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => $request->password
        ]);
        if ($user) {
            return response()->json([], 200);
        } else {
        return response()->json([
                'type' => 'users',
                'message' => 'Unable to create user'
            ], 404);
        }
    }
}