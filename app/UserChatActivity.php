<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserChatActivity extends Model
{
	public $timestamps = false;
    protected $table = 'user_chat_activity';

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function chat()
    {
    	return $this->belongsTo('App\Chat');
    }
}
