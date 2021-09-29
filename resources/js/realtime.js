import Echo from "laravel-echo"
window.io = require('socket.io-client');
// Have this in case you stop running your laravel echo server
window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: window.location.hostname + ':6001',
  csrfToken: $('meta[name="csrf-token"]').attr('content')
});