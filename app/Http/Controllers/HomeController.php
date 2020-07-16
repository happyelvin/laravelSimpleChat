<?php

namespace App\Http\Controllers;

use App\Events\SocketTesterEvent;
use App\Events\MessageSent;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;

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
        //event(new SocketTesterEvent('Test'));
        $messages = $this->fetchMessages();
        return view('chat', ['messages'=>$messages]);
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        event(new MessageSent($message));
        

        return ['status' => 'Message Sent!', 'user'=>$user];
    }
}
