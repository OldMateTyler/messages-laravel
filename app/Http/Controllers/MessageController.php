<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function show(Request $request)
    {
    	$messages = Message::where('author', '=', $request->user('sanctum')->id)->orWhere('recipient', '=', $request->user('sanctum')->id)->get();

    	return response()->json([
    		'messages' => $messages
    	], 200);
    }
    public function getMessageByID(Request $request, $message_id)
    {   
        $message = Message::where('id','=',$message_id);
        $userID = $request->user('sanctum')->id;
        $message = Message::where('id','=',$message_id)
        ->where(function($query) use ($userID){
            $query->where('author', '=', $userID)->orWhere('recipient', '=', $userID);
        })
        ->get();
        return $message;
        if ($message) {
            return response()->json([
                'messages' => $message
            ], 200);
        } else {
        return response()->json([
                    'type' => 'messages',
                    'message' => 'Not Found'
                ], 404);
        }
    }
    public function getThreadMessages(Request $request, $thread_id)
    {
        $message = Message::where('thread_id', '=', $thread_id);
        $message = $message->where('author', '=', $request->user('sanctum')->id)->orWhere('recipient', '=', $request->user('sanctum')->id)->get();
        if ($message) {
            return response()->json([
                'messages' => $message
            ], 200);
        } else {
        return response()->json([
                    'type' => 'messages',
                    'message' => 'Not Found'
                ], 404);
        }
    }
    public function deleteMessageByID(Request $request, $message_id)
    {
        $message = Message::find($message_id)->where('author', '=', $request->user('sanctum')->id);
        if ($message) {
            $message->delete();
            return response()->json([], 204);
        } else {
            return response()->json([
                'type' => 'messages',
                'message' => 'Not Found'
            ], 404);
        }
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|string',
            'author' => 'required|integer',
            'recipient' => 'required|integer',
            'thread_id' => 'required|integer',
        ]);

        $message = Message::create([
            'body' => $request->body,
            'author' => $request->user('sanctum')->id,
            'recipient' => $request->recipient,
            'thread_id' => $request->thread_id,
        ]);
        if ($message) {
            return response()->json([], 200);
        } else {
        return response()->json([
                'type' => 'messages',
                'message' => 'Unable to create message'
            ], 404);
        }
    }
}
