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
        $users = User::with(['unread_messages' => function($q) {
            $q->where('to', '=', auth()->id())
                ->where('seen', '=', 0);
        }])->where('id', '!=', auth()->id())->get();

        $unread = User::whereHas('messages', function ($query) {
           $query->where('to', '=', auth()->id())
           ->where('seen', '=', 0);
        })->count();

        return view('home', compact('users', 'unread'));
    }
}
