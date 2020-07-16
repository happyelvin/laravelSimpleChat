<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SocketTesterEvent;
use App\Http\Requests;

class SocketTesterController extends Controller
{
    public function index()
    {
    	event(new SocketTesterEvent('Test'));

    	return view('welcome');
    }
}
