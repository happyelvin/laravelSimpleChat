<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserChatActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chat_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('active')->default(1);

            $table->foreign('chat_id')->references('id')->on('chats');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chat_activity');
    }
}
