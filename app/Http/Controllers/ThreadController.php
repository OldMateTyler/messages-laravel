<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;

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
        $thread = Thread::where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->get();
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
    public function getThreadByID(Request $request, $thread_id)
    {
        $thread = Thread::where('id', '=', $thread_id);
        $thread = $thread->where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->first();
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
    public function updateThread(Request $request, $thread_id){
        $thread = Thread::where('id', '=', $thread_id);
        $thread = $thread->where('userOne', '=', $request->user('sanctum')->id)->orWhere('userTwo', '=', $request->user('sanctum')->id)->first();
        if ($thread) {
              $name = $request->getContent();
              $name = explode(':"', $name)[1];
              $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
              $thread->name = $name;
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
            'userOne' => 'required|integer',
            'userTwo' => 'required|integer',
            'name' => 'required|string'
        ]);

        $thread = Thread::create([
            'userOne' => $request->user('sanctum')->id,
            'userTwo' => $request->userTwo,
            'name' => $request->name
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
}
