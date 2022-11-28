<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function show()
    {
    	$messages = Message::get();

    	return response()->json([
    		'messages' => $messages
    	], 200);
    }
    public function getMessageByID($message_id)
    {
        $message = Message::find($message_id);
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
    public function getThreadMessages($thread_id)
    {
        $message='asdfasdf';
        $message = Message::where('thread_id', '=', $thread_id)->get();
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
    public function deleteMessageByID($message_id)
    {
        $message = Message::find($message_id);
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
            'author' => $request->author,
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
