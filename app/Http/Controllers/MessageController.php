<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function fetch($id)
    {
        $from = Auth::user()->id;
        $user = Auth::user();

        $messages = Message::with('user')->where(function ($query) use ($from, $id) {
                $query->where('from', '=', $from)
                    ->where('to', '=', $id);
            })
            ->orWhere(function ($query2) use ($from, $id) {
                $query2->where('to', '=', $from)
                    ->where('from', '=', $id);
            })->get();
        return view('chat', compact('messages', 'id', 'user'));
    }

    public function send(Request $request)
    {
        $data = $request->all();

        $message = Message::create($data);

        $user = Auth::user();

        broadcast(new MessageSentEvent($message, $user))->toOthers();
    }
}
