<?php

use Illuminate\Database\Seeder;

class ChatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chats')->insert([
        	 'id' => "1",
             'name' => "General Chat Room",
             'description' => "A Chat Room for you to talk about general things.",
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('chats')->insert([
        	 'id' => "2",
             'name' => "Entertainment",
             'description' => "This is the place for you to discuss anything related to entertainment, from movies, dramas, songs, anime, to even games!",
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}