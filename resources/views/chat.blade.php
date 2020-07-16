@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chats</div>

                <div class="panel-body" id="chatPanel">
                    <div class="chatMessages" id="chatMessages">  
                    </div>
                    

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

<template id="chatTemplate">
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
</template>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>

<script type="text/javascript">
    var sendMessageLaddaButton = null;

    $(function() {
        var socket = io('http://13.229.95.23/:3000');
        socket.on('message-channel:App\\Events\\MessageSent', function(data){
            console.log(data);
            toAdd = {
                "message": data.message.message,
                "user": {
                    "name": data.sender
                }
            };
            addMessageToDiv(toAdd);
        });

        sendMessageLaddaButton = Ladda.create(document.querySelector('#sendMessageButton'));

        loadAndShowMessages();
        
    });

    function loadAndShowMessages()
    {
        $.ajax({
            url: "{{route('fetchChatMessages')}}",
            dataType: "json",
            data: {
            },
            beforeSend: function() {
                $("#chatMessages").empty();
                $("#chatPanelLoader").css("display", "block");
            },
            success: function(data) {
               if (data.length == 0)
               {
                    //$("#chatMessages").html("There are no comments.");
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
        var template = document.querySelector("#chatTemplate");
        var clone = document.importNode(template.content, true);
        var sender = clone.querySelector(".chatTemplate_message_user");
        var content = clone.querySelector(".chatTemplate_message_content");

        sender.textContent = data.user.name;
        content.textContent = data.message;

        var toShow_div = document.querySelector("#chatMessages");
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
                addMessageToDiv(toAdd)
                $("#btn-input").val("").trigger("input");
            },
            error: function (jqXHR, exception) {
                //
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
</script>
@endsection