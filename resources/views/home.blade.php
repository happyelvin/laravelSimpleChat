@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="flex-center position-ref full-height">
                    <ul id="data"></ul>
                </div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>
    <script>
    $(function() {
        /*var socket = io('http://localhost:3000');
        socket.on('test-channel:App\\Events\\SocketTesterEvent', function(data){
            $('#data').append('<li>' + data.username + '</li>')
        });*/
    });
        
</script>
@endsection
