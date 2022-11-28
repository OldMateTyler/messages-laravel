<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        try{
            $validate = Validator::make($request->all(), [
                'name' => 'required | string',
                'email' => 'required| email | unique:users',
                'password'=>'required | string',
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            if(Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'status' => true,
                    'nessage' => 'User Created Successfully',
                    'token' => $user->createToken("api-token")->plainTextToken
                ], 200);
            }
        } catch(\Throwable $t){
            return response()->json([
                'status' => false,
                'message' => $t->getMessage()
            ], 500);
        }
    }
    public function check(Request $request){
        return response()->json([
            'loggedIn' => $request->user('sanctum')->currentAccessToken()->token
        ], 200);
    }
    public function signIn(Request $request)
    {
        try{
            $validate = Validator::make($request->all(), [
                'email' => 'required | email | string',
                'password' => 'required'
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)){
                $user = User::where('email', $request->email)->first();
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("api-token")->plainTextToken,
                ], 200);
            }
        } catch(\Throwable $t){
            return response()->json([
                'status' => false,
                'message' => $t->getMessage()
            ], 500);
        }
    }
    public function signOut(Request $request){
        $request->user('sanctum')->tokens()->delete();
    }
}
