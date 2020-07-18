var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis();


//redis.subscribe('test-channel');
redis.psubscribe('*');

redis.on('pmessage', function(subscribed, channel, message) { //multiple channels
    console.log(message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

/*redis.on('message', function(channel, message) {
	console.log(message);
	message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});*/
 
server.listen(3000);

/*var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var Redis = require('ioredis');
var redis = new Redis();

app.listen(6001, function() {
    console.log('Server is running!');
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function(socket) {
    //
});

redis.psubscribe('*', function(err, count) {
    //
});

redis.on('pmessage', function(subscribed, channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});*/