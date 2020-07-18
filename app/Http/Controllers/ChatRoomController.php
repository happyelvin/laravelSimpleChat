<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;
use App\Chat;

class ChatRoomController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	//$user = Auth::user();
    	//$all_users = User::where('id', '!=', $user->id)->get();

    	$chat_rooms = Chat::all();
    	return view('contact', ['chat_rooms'=>$chat_rooms]);
    }
}
