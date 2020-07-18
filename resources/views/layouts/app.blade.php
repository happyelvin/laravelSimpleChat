<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cacat Chat</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Loader CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/loader.css') }}">

    <!-- Ladda Js -->
    <script src="{{ URL::asset('assets/ladda-1.0.6/spin.min.js')}}"></script>
    <script src="{{ URL::asset('assets/ladda-1.0.6/ladda.jquery.min.js')}}"></script>
    <script src="{{ URL::asset('assets/ladda-1.0.6/ladda.min.js')}}"></script>

    <!-- Ladda CSS -->
    <link href="{{ URL::asset('assets/ladda-1.0.6/ladda-themeless.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato';
            height: 100vh;
        }

        .fa-btn {
            margin-right: 6px;
        }

        /*.chat {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chat li {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .chat li .chat-body p {
            margin: 0;
            color: #777777;
        }*/

        #chatPanel {
            overflow-y: scroll;
            height: 70vh;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }

        .intro-text {
            color:#636b6f;
        }

        .equal {
          display: flex;
          display: -webkit-flex;
          flex-wrap: wrap;
        }

        .center_span_within_div {
          display: inline-block;
          vertical-align: middle;
          line-height: normal;
        }

        .chatroom-table {
            margin-bottom: 0px;
        }

        #chat{
            padding-left:0;
            margin:0;
            list-style-type:none;
            border-top:2px solid #fff;
            border-bottom:2px solid #fff;
        }
        #chat li{
            padding:10px 30px;
        }
        #chat h2,#chat h3{
            display:inline-block;
            font-size:13px;
            font-weight:normal;
        }
        #chat h3{
            color:#bbb;
        }
        #chat .entete{
            margin-bottom:5px;
        }
        #chat .message{
            padding:10px;
            color:#fff;
            line-height:10px;
            max-width:90%;
            display:inline-block;
            text-align:left;
            border-radius:5px;
        }
        #chat .me{
            text-align:right;
        }
        #chat .you .message{
            background-color:#58b666;
        }
        #chat .me .message{
            background-color:#6fbced;
        }

        #chatPanel {
            padding: 0px;
        }

        #user_list {
            overflow-y: scroll;
            max-height: 70vh;
        }

        .typing:after {
            display: inline-block;
            animation: dotty steps(1,end) 1s infinite;
            content: '';
        }

        @keyframes dotty {
            0%   { content: ''; }
            25%  { content: '.'; }
            50%  { content: '..'; }
            75%  { content: '...'; }
            100% { content: ''; }
        }
 
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Cacat Chat
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/chatrooms') }}">Chat Rooms</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
