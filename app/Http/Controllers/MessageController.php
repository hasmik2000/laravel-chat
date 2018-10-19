<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function fetch($id)
    {
        $from = Auth::user()->id;
        $messages = DB::table('messages')->where([
            ['from', $from],
            ['to', $id],
            ['from', $id],
            ['to', $from]
        ])->get();

        dd($messages);
        $messages = Message::find('from', $from, 'to', $id);

        return view('chat', compact('messages', 'id'));
    }

    public function send(Request $request)
    {
        $data = $request->all();

        $message = Message::create($data);

        event(new MessageSentEvent($message));
    }
}
