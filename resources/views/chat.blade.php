@extends('layouts.app')

@section('content')
<script type="text/javascript">
    window.addEventListener("beforeunload", function(e){
        e.preventDefault()
        leaveChatRoom();
    });

    document.addEventListener("visibilitychange", function() {
        if (document.visibilityState === 'visible') {
            informExistence();
        } else {
            leaveChatRoom();
        }
    });
</script>

<input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
<input type="hidden" name="server_url" id="server_url" value="{{$server_url}}">
<input type="hidden" name="chat_id" id="chat_id" value="{{$chat->id}}">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">List of Users</div>
              <ul class="list-group" id="user_list">
                <table class="table chatroom-table" id="user_list_table">
                    @foreach($active_users as $active_user)
                    <tr class="user_list_tr" id="user_list_tr_{{$active_user->id}}">
                        <td>
                            <div class="user_list_name" id="user_list_name_{{$active_user->id}}">
                                {{$active_user->name}}    
                            </div>
                            <div class="intro-text user_list_typing" id="user_list_typing_{{$active_user->id}}">
                                &nbsp;
                            <div>
                        </td>
                    </tr>
                    @endforeach
                </table>
              </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">{{$chat->name}}</div>
                <div class="panel-body" id="chatPanel">
                        <ul id="chat">
                        </ul>
                    

                    <!-- Loading Spinner -->
                    <div class="loader" id="chatPanelLoader">Loading...</div>
                </div>
                <div class="panel-footer">
                    <!-- Box to enter message -->
                    <div class="input-group">
                        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here...">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm" id="sendMessageButton" data-style="zoom-in">
                                Send
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <template id="chatTemplate">
    <ul class="chat" id="chatTemplate_chat">
        <li class="left clearfix">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font chatTemplate_message_user"></strong>
                </div>
                <p class="chatTemplate_message_content"></p>
            </div>
        </li>
    </ul>
</template> -->

<template id="chatTemplate_other">
    <li class="you">
        <div class="entete">
            <h2 class="chatTemplate_message_user"></h2>
            <h3 class="chatTemplate_message_datetime"></h3>
        </div>
        <div class="message chatTemplate_message_content">
        </div>
    </li>
</template>

<template id="chatTemplate_me">
    <li class="me">
        <div class="entete">
            <h2 class="chatTemplate_message_user"></h2>
            <h3 class="chatTemplate_message_datetime"></h3>
        </div>
        <div class="message chatTemplate_message_content">
        </div>
    </li>
</template>

<template id="user_list_template">
    <tr class="user_list_tr">
        <td>
            <div class="user_list_name">Name</div>
            <div class="intro-text user_list_typing">&nbsp</div>
        </td>
    </tr>
</template>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>

<script type="text/javascript">
    var sendMessageLaddaButton = null;

    $(function() {
        var chat_id = $("#chat_id").val();
        //var sever_url = $("#server_url").val();
	var server_url = "{{$server_url}}";
        var socket = io(server_url+":3000");
        socket.on('chat-channel.'+chat_id+':App\\Events\\MessageSent', function(data){
            console.log(data);
            toAdd = {
                "message": data.message.message,
                "user": {
                    "name": data.sender
                },
                "user_id": data.message.user_id,
                "created_at": data.message.created_at
            };
            addMessageToDiv(toAdd);
        });

        socket.on('chat-channel.'+chat_id+':App\\Events\\LeftChannel', function(data){
            removeUserFromTable(data.user.id);
        });

        socket.on('chat-channel.'+chat_id+':App\\Events\\JoinedChannel', function(data){
            //console.log(data);
            toAdd = {
                "user_id": data.user.id,
                "name": data.user.name
            };
            addUserToTable(toAdd);
        });

        socket.on('chat-channel.'+chat_id+':App\\Events\\Typing', function(data){
            makeTyping(data.user.id);
        });

        sendMessageLaddaButton = Ladda.create(document.querySelector('#sendMessageButton'));

        loadAndShowMessages();
        
    });

    /*in case the user opens the chatroom in multiple tabs, when one tab is closed
    the others tabs inform server of the existence*/
    function informExistence()
    {
        if (document.visibilityState !== 'visible') {
           return;
        }
        
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('informExistence')}}",
            data: {
                chat_id: "{{$chat->id}}",
                _token:_token
            },
            beforeSend: function(){
                //
            },
            complete: function() {
                //
            },
            success: function(data) {
                //
            },
            error: function (jqXHR, exception) {
                //
            }
        });
    }

    var typing_timers = [];

    function makeTyping(user_id)
    {
        var self_user_id = $("#user_id").val();
        if (user_id == self_user_id)
        {
            return;
        }

        if (typing_timers[user_id]) {
            clearTimeout(typing_timers[user_id]);
        }


        var elem = $("#user_list_typing_"+user_id);
        $(elem).html("Typing");
        $(elem).addClass("typing");

        typing_timers[user_id] = setTimeout(() => {
            $(elem).html("&nbsp");
            $(elem).removeClass("typing");
        }, 1500);
    }

    function removeUserFromTable(user_id)
    {
        var self_user_id = $("#user_id").val();
        if (user_id != self_user_id) 
        {
            $("#user_list_tr_"+user_id).remove();
        }
        else
        {
            //alert only after 10 seconds
            setTimeout(informExistence, 10000);
            //informExistence();
        }
    }

    function addUserToTable(data)
    {
        var self_user_id = $("#user_id").val();
        if (user_id != self_user_id)
        {
            var user_id = data.user_id;
            var name = data.name;

            var elem = $("#user_list_tr_"+user_id);
            if (!elem.length)
            {
                template = document.querySelector("#user_list_template");
                var clone = document.importNode(template.content, true);
                var user_list_tr = clone.querySelector(".user_list_tr");
                var user_list_name = clone.querySelector(".user_list_name");
                var user_list_typing = clone.querySelector(".user_list_typing");

                user_list_tr.setAttribute("id", "user_list_tr_"+user_id);
                user_list_name.setAttribute("id", "user_list_name_"+user_id);
                user_list_typing.setAttribute("id", "user_list_typing_"+user_id);

                user_list_name.textContent = name;

                var toShow_div = document.querySelector("#user_list_table > tbody");
                toShow_div.appendChild(clone);
            }
        }
    }

    function loadAndShowMessages()
    {
        $.ajax({
            url: "{{route('fetchChatMessages')}}",
            dataType: "json",
            data: {
                chat_id: "{{$chat->id}}"
            },
            beforeSend: function() {
                $("#chatMessages").empty();
                $("#chatPanelLoader").css("display", "block");
            },
            success: function(data) {
               if (data.length == 0)
               {
                    $("#chatMessages").html("This chat room is quiet...");
               } 
               else
               {
                    for (var i = 0; i < data.length; i++)
                    {
                        addMessageToDiv(data[i])
                    }
               }
            },
            complete: function() {
                $("#chatPanelLoader").css("display", "none");
            },
            error: function (jqXHR, exception) {
                $("#chatMessages").html("Error loading messages.");
            }
        });
    }

    function addMessageToDiv(data)
    {
        var template;
        var user_id = $("#user_id").val();
        if (data.user_id === parseInt(user_id)) {
            template = document.querySelector("#chatTemplate_me");
        }
        else {
            template = document.querySelector("#chatTemplate_other");
        }
        var clone = document.importNode(template.content, true);
        var sender = clone.querySelector(".chatTemplate_message_user");
        var content = clone.querySelector(".chatTemplate_message_content");
        var datetime = clone.querySelector(".chatTemplate_message_datetime");

        sender.textContent = data.user.name;
        content.textContent = data.message;
        datetime.textContent = data.created_at;

        var toShow_div = document.querySelector("#chat");
        toShow_div.appendChild(clone);

        var panelBody = document.getElementById("chatPanel");
        panelBody.scrollTo(0,panelBody.scrollHeight);
    }

    function sendMessage()
    {
        var message = $("#btn-input").val();
        message = message.trim();
        if (!message)
        {
            $("#btn-input").val("").trigger("input");
            return;
        }
        
        var _token = $('input[name="_token"]').val();

        $.ajax({
            type: "POST",
            url: "{{route('sendChatMessage')}}",
            data: {
                message: message,
                chat_id: "{{$chat->id}}",
                _token:_token
            },
            beforeSend: function(){
                sendMessageLaddaButton.start();
            },
            complete: function() {
                sendMessageLaddaButton.stop();
            },
            success: function(data) {
                toAdd = {
                    "message": message,
                    "user": data.user
                };
                //addMessageToDiv(toAdd)
                $("#btn-input").val("").trigger("input");
            },
            error: function (jqXHR, exception) {
                //
            }
        });
    }

    function leaveChatRoom()
    {
        var _token = $('input[name="_token"]').val();
        /*$.ajax({
            type: "POST",
            url: "{{route('leaveChatRoom')}}",
            data: {
                chat_id: "{{$chat->id}}",
                _token:_token
            },
            beforeSend: function(){
                //
            },
            complete: function() {
                //
            },
            success: function(data) {
                //
            },
            error: function (jqXHR, exception) {
                //
            }
        });*/
	   var fdata = new FormData();
	   fdata.append('chat_id', "{{$chat->id}}");
	   fdata.append('_token', _token);
	   navigator.sendBeacon("{{route('leaveChatRoom')}}", fdata);
    }

    function toggleVisibility()
    {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('toggleVisibility')}}",
            data: {
                chat_id: "{{$chat->id}}",
                _token:_token
            }
        });
    }

    var selfTypingTimer = false;

    function sendTypingEvent()
    {
        if (selfTypingTimer)
        {
            //dont send within 1 second interval to prevent causing overhead.
            return;
        }

        console.log("Sending Typing Event");

        selfTypingTimer = setTimeout(() => {
            selfTypingTimer = false;
        }, 1000);

        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('sendTypingEvent')}}",
            data: {
                chat_id: "{{$chat->id}}",
                _token:_token
            },
            beforeSend: function(){
            },
            complete: function() {
            },
            success: function(data) {
            },
            error: function (jqXHR, exception) {
            }
        });
    }

    $(document).on("click", "#sendMessageButton", function() {
        sendMessage();
    });

    $(document).on("keypress", "#btn-input", function(e) {
        if(e.which == 13) {
            sendMessage();
        }
    });

    $(document).on("keydown", "#btn-input", function(e){
        sendTypingEvent();
    });
</script>
@endsection
