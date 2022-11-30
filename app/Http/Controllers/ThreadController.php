<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{
    public function show()
    {
    	$threads = Thread::get();

    	return response()->json([
    		'threads' => $threads
    	], 200);
    }
    public function getUsersThreads(Request $request)
    {
        $thread = Thread::select('threads.*', 'users.name as user_name')->where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->join('users', function($join) use ($request){
            $join->on('users.id', '=', 'threads.userOne')->where('threads.userOne', '!=', $request->user('sanctum')->id);
            $join->orOn('users.id', '=', 'threads.userTwo')->where('threads.userTwo', '!=', $request->user('sanctum')->id);
        })->get();
        if ($thread) {
            return response()->json([
                'threads' => $thread
            ], 200);
        } else {
        return response()->json([
                    'type' => 'threads',
                    'message' => 'Not Found'
                ], 404);
        }
    }
    public function checkUsersThreads(Request $request, $user_id){
        $thread = Thread::where(function ($query) use ($request, $user_id){
            $query->where('threads.userOne', '=', $request->user('sanctum')->id)->where('threads.userTwo', '=', $user_id);
            $query->orWhere('threads.userTwo', '=', $request->user('sanctum')->id)->where('threads.userOne', '=', $user_id);
        })->get();
        if ($thread) {
            return response()->json([
                $thread
            ], 200);
        } else {
        return response()->json([
                    'type' => 'threads',
                    'message' => 'Not Found'
                ], 404);
        }
    }
    public function getThreadByID(Request $request, $thread_id)
    {
        $thread = Thread::select('threads.*', 'users.user_img', 'users.id as users_id')->where('threads.id', '=', $thread_id)->where(function ($query) use ($request){
            $query->where('userOne', '=', $request->user('sanctum')->id);
            $query->orWhere('userTwo', '=', $request->user('sanctum')->id);
        });
        $thread = $thread->join('users', function($join) use ($request){
            $join->on('users.id', '=', 'threads.userOne')->where('threads.userOne', '!=', $request->user('sanctum')->id);
            $join->orOn('users.id', '=', 'threads.userTwo')->where('threads.userTwo', '!=', $request->user('sanctum')->id);
        })->first();
        if ($thread) {
            return response()->json([
                $thread
            ], 200);
        } else {
        return response()->json([
                    'type' => 'threads',
                    'message' => 'Not Found'
                ], 404);
        }
    }

    public function deleteThreadByID(Request $request, $thread_id)
    {
        $thread = Thread::where('id', '=', $thread_id);
        $thread = $thread->where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->first();
        if ($thread) {
            $thread->delete();
            return response()->json([], 204);
        } else {
            return response()->json([
                'type' => 'threads',
                'message' => 'Not Found'
            ], 404);
        }
    }
    public function updateThread(Request $request){
        $thread = Thread::where('id', '=', $request->thread_id);
        $thread = $thread->where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->first();
        if ($thread) {
              $thread->img_src = $request->img_src;
              $thread->thread_name = $request->thread_name;
              $thread->save();
              return response()->json([], 200);
        } else {
            return response()->json([
                'type'=> 'threads',
                'message' => "Not Found"
            ],404);
        }
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'userTwo' => 'required',
            'thread_name' => 'required|string',
            'img_src' => 'required'
        ]);
        DB::enableQueryLog();
        $dupThread = Thread::where(function ($query) use ($request){
            $query->where('userOne', '=', $request->user('sanctum')->id)->where('userTwo', '=', (int)$request->userTwo);
        })->where(function ($query) use ($request){
            $query->where('userOne', '=', (int)$request->userTwo)->where('userTwo', '=', $request->user('sanctum')->id);
        })->get();
        if($dupThread){
            $thread = Thread::create([
                'userOne' => $request->user('sanctum')->id,
                'userTwo' => $request->userTwo,
                'thread_name' => $request->thread_name,
                'img_src' => $request->img_src
            ]);
            if ($thread) {
                return response()->json([], 200);
            } else {
            return response()->json([
                    'type' => 'users',
                    'message' => 'Unable to create thread'
                ], 404);
            }
        }
        else{
            return response()->json(
                ['Thread already exists'], 201
            );
        }

    }
}
