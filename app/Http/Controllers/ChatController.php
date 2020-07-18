<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\Message;
use App\User;
use App\UserChatActivity;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\JoinedChannel;
use App\Events\LeftChannel;
use App\Events\Typing;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
    	$user = Auth::user();
    	$room_id = $request->id;
    	$server_url = URL('/');

    	if ($userchatact = UserChatActivity::where('chat_id', $room_id)->where('user_id', $user->id)->first())
    	{
    		$this->activeIfNot($user->id, $room_id);
    	}
    	else 
    	{
    		$new_user_chat_act = new UserChatActivity();
    		$new_user_chat_act->chat_id = $room_id;
    		$new_user_chat_act->user_id = $user->id;
    		$new_user_chat_act->active = 1;
    		$new_user_chat_act->save();
            
            event(new JoinedChannel($user, $room_id));
    	}

    	$active_user_ids = UserChatActivity::where('chat_id', $room_id)
    		->where('active', 1)
    		->pluck('user_id');


    	if (count($active_user_ids)>0) {
    		$active_users = User::findMany($active_user_ids);
    	}
    	else {
    		$active_users = [];
    	}

    	if ($chat = Chat::find($room_id)) 
    	{
    		return view('chat', ['user'=>$user, 'chat'=>$chat, 'server_url'=>$server_url, 
    			'active_users'=>$active_users]);
    	}
    	else
    	{
    		abort(404);
    	}
    	
    }

    public function fetchMessages(Request $request)
    {
    	$user = Auth::user();
    	$chat_id = $request->chat_id;

    	$this->activeIfNot($user->id, $chat_id);

    	if ($chat = Chat::find($chat_id))
    	{
    		return $chat->messages()->with('user')->get();
    	}
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $this->activeIfNot($user->id, $request->chat_id);

        $message = new Message();
        $message->chat_id = $request->chat_id;
        $message->user_id = $user->id;
        $message->message = $request->message;
        $message->save();

        event(new MessageSent($message));

        return ['status' => 'Message Sent!', 'user'=>$user];
    }

    public function activeIfNot($user_id, $chat_id)
    {
    	$userchatact = UserChatActivity::where('chat_id', $chat_id)->where('user_id', $user_id)->firstOrFail();
    	if (!$userchatact->active) {
    		$userchatact->active = 1;
    		$userchatact->save();
    		//broadcast user turned online
    		$user = User::find($user_id);
    		event(new JoinedChannel($user, $chat_id));
    	}
    }

    public function leaveChatRoom(Request $request)
    {
    	$user = Auth::user();
    	$chat_id = $request->chat_id;

    	$userchatact = UserChatActivity::where('chat_id', $chat_id)->where('user_id', $user->id)->firstOrFail();
    	$userchatact->active = 0;
    	$userchatact->save();
    	//broadcast user turned offline
    	event(new LeftChannel($user, $chat_id));
    }

    public function informExistence(Request $request)
    {
    	$user = Auth::user();
    	$chat_id = $request->chat_id;

    	$this->activeIfNot($user->id, $chat_id);
    }

    public function sendTypingEvent(Request $request)
    {
    	$user = Auth::user();
    	$chat_id = $request->chat_id;

        event(new Typing($user, $chat_id));
    }
}
