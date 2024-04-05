http = require('node:http');
listener = function (request, response) {
   // Send the HTTP header 
   // HTTP Status: 200 : OK
   // Content Type: text/html
   response.writeHead(200, {'Content-Type': 'text/html'});
  
   // Send the response body as "Hello World"
   response.end('<h2 style="text-align: center;">Hello World</h2>');
};

server = http.createServer(listener);
server.listen(50000);

// Console will print the message

console.log('Server running at http://127.0.0.1:50000/');