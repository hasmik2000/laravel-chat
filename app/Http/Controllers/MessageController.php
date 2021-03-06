<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function fetch($id)
    {
        $from = Auth::user()->id;
        $user = Auth::user();

        Message::with('user')->where(function ($query) use ($from, $id) {
            $query->where('to', '=', $from)
                ->where('from', '=', $id);
        })->update(['seen' => 1]);

        $messages = Message::with('user')->where(function ($query) use ($from, $id) {
                $query->where('from', '=', $from)
                    ->where('to', '=', $id);
            })
            ->orWhere(function ($query2) use ($from, $id) {
                $query2->where('to', '=', $from)
                    ->where('from', '=', $id);
            })->get();

        $unread = User::whereHas('messages', function ($query) {
            $query->where('to', '=', auth()->id())
                ->where('seen', '=', 0);
        })->count();

        return view('chat', compact('messages', 'id', 'user', 'unread'));
    }

    public function send(Request $request)
    {
        $data = $request->all();

        $message = Message::create($data);

        $user = Auth::user();

        broadcast(new MessageSentEvent($message, $user))->toOthers();
    }

    public function seen($id)
    {
        Message::where('id', '=', $id)->update(['seen' => 1]);
    }
}
