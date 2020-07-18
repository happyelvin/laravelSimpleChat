@extends('layouts.app')
@section('content')
	<div class="container">
		<h4 class="text-center">Select one chat room to start chatting!</h4>
		<div class="panel panel-default">
		  <div class="panel-heading">List of Chat Room Available</div>
		  <ul class="list-group">
		  	<table class="table chatroom-table">
		    @foreach($chat_rooms as $chat_room)
		    	<!-- <li class="list-group-item">
			    	<div class="row equal">
					  <div class="col-sm-10">
					  	<div>{{$chat_room->name}}</div>
					  	<div class="intro-text">{{$chat_room->description}}</div>
					  </div>
					  <div class="col-sm-2 center-block">
					  	<button class="btn btn-sm btn-primary center-block" >Enter</button>
					  </div>
					</div>
			    </li> -->
			    <tr>
			    	<td>
			    		<div>{{$chat_room->name}}</div>
					  	<div class="intro-text">{{$chat_room->description}}</div>
			    	</td>
			    	<td>
			    		<a href="/chat/{{$chat_room->id}}" class="btn btn-sm btn-primary">Enter</a>
			    	</td>
			    </tr>
			@endforeach
  			</table>
		  </ul>
		</div>
		
	</div>
@endsection