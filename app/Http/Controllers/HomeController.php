<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('messages')->where('id', '!=', Auth::id())->get();
//        $unread = Message::with('user')->where(function ($query) use($from){
//            $query->where('to', '=', Auth::id())
//                ->where('from', '=', $from);
//        })->orWhere(function ($query2){
//            $query2->where('seen', '=', 0);
//        })->get();
        $unread = User::whereHas('messages', function ($query) {
           $query->where('to', '=', Auth::id())
           ->where('seen', '=', '0');
        })->count();

//        $user = User::with('unread_messages')->get();

        $user = User::with('unread_messages')->whereHas('messages', function ($query) {
            $query->where('to', '=', Auth::id())
                ->where('seen', '=', '0');
        })->get();
//        dd($unread);
        dd($user);
        return view('home', compact('users', 'unread'));
    }
}
