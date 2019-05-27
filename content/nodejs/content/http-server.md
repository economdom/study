# Простой HTTP-сервер

Создайте файл *server.js* в корневой директории вашего проекта и поместите туда следующий код:

*server.js*

```
var http = require("http");

http.createServer(function(request, response) {
  response.writeHead(200, {"Content-Type": "text/plain"});
  response.write("Hello World");
  response.end();
}).listen(8888);
```

Всё, вы написали работающий HTTP-сервер. Давайте проверим его:

```
node server.js
```

Теперь откройте ваш браузер и перейдите по адресу *http://localhost:8888/*. Должна вывестись веб-страница со строкой «Hello world».