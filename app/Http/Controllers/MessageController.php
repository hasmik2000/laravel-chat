<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Events\MessageSentEvent;

class MessageController extends Controller
{
    public function fetch($id)
    {
        $messages = Message::all();

        return view('chat', compact('messages', 'id'));
    }

    public function send(Request $request)
    {
        $data = $request->all();

        $message = Message::create($data);

        event(new MessageSentEvent($message));
    }
}
