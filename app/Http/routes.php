<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
	    return view('welcome');
	});

	Route::get('/contact', 'ContactController@index');

	/*
	|--------------------------------------------------------------------------
	| Application Routes
	|--------------------------------------------------------------------------
	|
	| This route group applies the "web" middleware group to every route
	| it contains. The "web" middleware group is defined in your HTTP
	| kernel and includes session state, CSRF protection, and more.
	|
	*/

	Route::auth();

	Route::get('/chatrooms', 'ChatRoomController@index');

	/*Route::get('/chat', function(){
		return view('chat');
	});*/

	Route::get('/chat/{id}', 'ChatController@index');

	Route::get('/messages', 'ChatController@fetchMessages')->name('fetchChatMessages');

	Route::post('/messages', 'ChatController@sendMessage')->name('sendChatMessage');

	Route::get('/test', function(){
		return view('test');
	});

	Route::post('/leaveChatRoom', 'ChatController@leaveChatRoom')->name('leaveChatRoom');

	Route::post('/informExistence', 'ChatController@informExistence')->name('informExistence');

	Route::post('/sendTypingEvent', 'ChatController@sendTypingEvent')->name('sendTypingEvent');

});

