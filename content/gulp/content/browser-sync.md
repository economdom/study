# Browsersync

**Browsersync** - утилита, позволяющая текстировать ваш сайт одновременно на нескольких браузерах или устройствах.

```bash
npm install browser-sync --save-dev
```

Browsersync можно запустить без Gulp используя примерно такую команду с консоли.

```bash
browser-sync start --server --files "*.*"
```

Таким образом мы будем отслеживать все файлы с текущей директории.

* В вотчере нам нужно добавить `browsersync.reload`
* Свойство `baseDir:'./'` объекта `server` указывает путь к точке входа веб-сервера. На данный момент он указывает на текущую директорию (папка где находится *gulpfile.js*), но вы можете указать другой, если в этой есть необходимость.

*gulpfile.js*

```js
"use scrict"

var gulp = require('gulp'),
    browsersync = require('browser-sync');

gulp.task('html', function() {
  gulp.src('index.html')
});

gulp.task('browsersync', function() {
  return browsersync({
    server: {
      baseDir:'./'
    }
  });
});

gulp.task('watch', function() {
  gulp.watch("index.html", ['html', browsersync.reload]);
});

gulp.task('default', ['watch', 'browsersync', 'html']);
```

Можно написать вотчер таким образом

*gulpfile.js*

```js
gulp.task('watch', function () {
  gulp.watch([
    '*.html',
  ]).on('change', browserSync.reload);
});
```

В консоли регистрируютс все изменения, а также при запуске Browsersync были записаны URL доступа. Первый из них (`Local: http://localhost:3000`) для доступа к вашему сайту с вашей машины, второй (`External: http://192.168.1.100:3000`) для подключения с внешних устройств, например смартфоны или планшеты находящиеся с вам в одной сети.

Вторая важная составляющая Browsersync это графический интерфейс с настройками - он доступен по двум другим адресам из вашей консоли (`UI: http://localhost:3001` и `UI External: http://192.168.1.100:3001`).

На верхней панели есть кнопки для быстрой навигации и управления проектом. 

* **Overview** - здесь продублированны пути для доступа с локальных и внешних устройств, путь с которым работает Browsersync - в данном случае это кореневая папка, а в случае с реальным проектом это может быть папка *app*. Далее мы видим с каких браузеров установлено соединение.
* **Sync Options** - позволяет настроить по каким действиям будет производится синхронизация между браузерами, например мы можем отключить какие-то опции - по кликам, прокрутке и действиям формы.
* **History** - показывает историю переходов по вашему сайту. В ней очень удобно реализован механизм перехода на конкретную страничку с синхронизацией во всех браузерах.
* **Plugins** - отображаются все подключенные вами плагины - их можно написать самостоятельно или скачать с npm с ключевыми словами `browser-sync plugin`.
* **Remote Debug** - содержится информация для отладки вашего проекта. Remote Degugger - это аналог инструментов разработчика Chrome. Остальные настройки позволяют подсветить нам различные элементы на странице и наложение сетки.
* **Network Throttle** - имитация соединений. Вы можете создать несколько веб-серверов, которые будут, которые будут имитировать различные соединения - 3G, 4G и т. д., тем самым вы сможете протестировать вашу страницу на оптимизацию загрузки.

Browsersync можно использовать в проектах любой сложности - работаете ли вы с CMS, используете сборщики проектов или предпроцессоры.

## Browsersync API

Первые четыре команды - `create()`, `get()`, `init()` и `reload() - это главные команды для управления сервером.

* `create()` - создаёт среду для сервера, делая доступными остальные методы
* `init()` - делает запуск сервера
* `get()` - даёт возможность получить доступ к уже запущенному серверу из других файлов
* `reload()` - обновляет странички во всех запущенных браузерах

Давайте проверим данные методы на примерах используя локальный сервер.

Создадим на локальном сервере папку с нашим проектом и добавим несколько файлов.

*index.php*

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
</head>
<body>
  <h1>Lorem ipsum dolor sit amet.</h1>
  <a href="clock.php">Get time</a>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non eligendi maiores mollitia adipisci recusandae ratione, cupiditate aliquam distinctio consequatur id dolorum voluptatibus odio harum animi minus nulla nostrum dolore doloremque.</p>
  <p>Nobis rem ipsum similique cumque aperiam, itaque, numquam qui expedita quisquam magni nisi eaque dolores. Ab dolorem nulla maiores laboriosam porro suscipit quasi! Officiis, possimus dicta quasi inventore earum dignissimos!</p>
  <p>Quidem quos corrupti voluptatum ab tenetur assumenda iure, ipsa sit architecto eos mollitia hic, fugiat rem aliquam deleniti. Sint totam dignissimos sequi voluptas debitis numquam consectetur quod unde autem consequuntur.</p>
  <p>Iste nisi consequatur ipsum fuga reiciendis possimus quia quidem eaque illo quibusdam est, a, nam eos laboriosam numquam laudantium nihil! Omnis ex corporis voluptatum sunt, sint nobis error culpa placeat.</p>
</body>
</html>
```

*clock.php*

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clock PHP</title>
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
</head>
<body>
  <p id="time"></p>
  <a href="index.php">Go back</a>
  <script>
    function show(){
      $.ajax({
        url: "time.php",
        cache: false,
        success: function(html){
          $('#time').html(html)
        }
      })
    }
    setInterval('show()', 1000);
  </script>
</body>
</html>
```

*time.php*

```php
<?php
echo date('H:i:s');
```

Если мы запустим наш проект в браузере (в моём случае это [http://sandbox.dev:8080/test](http://sandbox.dev:8080/test)), то мы увидим что всё прекрасно работает, тоесть при переходе по ссылке мы через Ajax получаем текущее время от сервера.

Добавим файл *package.json*

```bash
npm init
```

И установим Browsersync локально

```bash
npm install browser-sync --save-dev
```

Теперь *package.json* приобретает примерно такой вид

```json
{
  "name": "test",
  "version": "1.0.0",
  "description": "Browersync testing",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "repository": {
    "type": "git",
    "url": "git+https://github.com/kamuz/browser-sync.git"
  },
  "author": "Vladimir Kamuz",
  "license": "ISC",
  "bugs": {
    "url": "https://github.com/kamuz/browser-sync/issues"
  },
  "homepage": "https://github.com/kamuz/browser-sync#readme",
  "devDependencies": {
    "browser-sync": "^2.14.0"
  }
}
```

Чтобы запустить Browsersync нужно создать файл *app.js* и скопировать самый простой пример из документации.

* Создаём переменную, которая обращается к модулю `brower-sync` с методом `create()` - он создаёт окружение для сервера Browersync. Хотя раньше метод `create()` не использовался и сейчас всё будет работать, но его всё таки рекомендуется использовать для расширения наших возможностей.
* Методом `init()` мы инициализируем старт проекта. Свойство `server` указывает на кореневую папку для нашего сервера.

*app.js*

```js
// require the module as normal
var bs = require("browser-sync").create();

// .init starts the server
bs.init({
  server: "./"
});
```

Чтобы выполнить скрипт написанных в *app.js* достаточно выполнить команду.

```bash
node app.js
```

И получим сообщение `Cannot GET /` потому что по этому адресу PHP интерпретатор доступен не будет. Чтобы выйти из этой ситуации существует режим `proxy`.

* Свойство `proxy` принимает адрес сайта, который вы хотите чтобы был запущен - это может быть любой сайт даже на удалённом сервере. Я введу адрес своего локального сервера - [http://sandbox.dev:8080/test/](http://sandbox.dev:8080/test/)

*app.js*

```js
// require the module as normal
var bs = require("browser-sync").create();

// .init starts the server
bs.init({
  proxy: "http://sandbox.dev:8080/test/"
});
```

Пробуем снова запустить наш скрипт

```bash
node app.js
```

И теперь видим что наш проект запустился на сервере Browsersync и теперь все PHP скрипты работают так как нужно.

Таким образом вы можете запустить любой сложности на вашем сервере и через прокси сервер Browersync тестировать его на всех устройствах.

`reload()` необходимый для отправки сигнала всем браузерам о перезагрузке страницы

`watch()` необходимый для отслеживания изменений в файлах проекта. Тоесть в Browsersync имеется встроенный вотчер, например для тех кто использует Grunt нужно ставить его дополнительно в виде плагина.

*app.js*

```js
// Listen to change events on HTML and reload
bs.watch("*.php").on("change", bs.reload);
```

В данном случае мы будем отслеживать все PHP файлы и при их изменениях браузер будет перезагружать страницу. Перезапускаем Browsersync и видим что при изменении PHP файлов будет автоматически перезагружатся браузер.

Методами `pause()` и `resume()` можно останавливать и возобновлять работу Browsersync.

`emitter()` - может выполнять какие-то действия, когда произошло определённое событие.

```js
var bs = require("browser-sync").create();

// Listen for the `init` event
bs.emitter.on("init", function () {
  console.log("Browsersync is running!");
});

bs.init(config);
```

В примере мы выводим в консоль сообщение когда был произведён запуск Browsersync.

`active()` и `paused()` возвращают `true` или `false` в зависимости от того работает сервер или нет.

Browsersync в своей работе использует механизм сокетов и с помощью метода `sockets()`, который описан в документации в разделе [Options](https://www.browsersync.io/docs/options#option-socket), мы можете гибко настраивать свои соединения под себя.

`stream()` служит для использования Browsersync с потоками, тоесть вы можете вызвать перезагрузку страницы в определённом месте в ходе выполнения вашего таска, если вы используете Gulp или Grunt.

Давайте установим `gulp` и `gulp-sass` локально

```bash
npm i gulp gulp-sass --save-dev
```

Создадим файл *gulpfile.js*

* Определим необходимые переменные
* Создадим таск для компиляции SASS - нам нужно указать исходную папку где лежат файлы и определяем куда нужно положить скопилированные файлы, после чего обновляем страницу с помощью метода `stream()`. Но данный такс будет выполнен всего один раз при запуске проекта.
* Чтобы таск `sass` выполнялся каждый раз, когда мы изменяем SASS файлы нам нужно повесить вотчер на это событие. Квадратные скобки в заголовке такска, говорят нам о том что сначала будет выполнен такс имя которого там записанно, а потом все остальные. Таким образом мы сначала компилируем наш CSS, затем запускаем Browsersync и следим, если будут внесены изменения в SASS файлы, тогда выполнится компиляция и обновится страница. Либо если изменения произошли в PHP, то мы сразу же перезагружаем страницу.
* Добавим дефолтный такс

*gulpfile.js*

```js
var gulp = require('gulp'),
sass = require('gulp-sass'),
browsersync = require('browser-sync').create();

// Static Server + watching scss/html files
gulp.task('serve', ['sass'], function() {
  browsersync.init({
    proxy: "sandbox.dev:8080/test"
  });
  gulp.watch("scss/*.scss", ['sass']);
  gulp.watch("*.php").on('change', browsersync.reload);
});

// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', function() {
  return gulp.src("scss/*.scss")
    .pipe(sass())
    .pipe(gulp.dest("css"))
    .pipe(browsersync.stream());
});

gulp.task('default', ['serve']);
```

Перед запуском Gulp нужно внеси кое-какие правки.

* Добавить ссылку на CSS файл в файле *index.php*
* Добавить папку и файл *scss/main.scss* примерно со следующим содержимым.

*scss/main.scss*

```
$light-gray: #ccc;

body{
  background-color: $light-gray;
  color: darken($light-gray, 80%);
}
```

И теперь для того чтобы запусить наш проект, достаточно просто в консоли набрать

```bash
gulp
````

SCSS файл должен скопилироватся в CSS и при изменении SCSS будет происходить компиляция и перезагрузка страницы, а при изменении любых PHP файлов просто перезагрузка.

В новом проекте от Google уже включён Browsersync - [Web Starter Kit](https://developers.google.com/web/tools/starter-kit/). Он представляет собой стартовый шаблон в котором уже есть заготовка *index.html* и всех папок проекта, созданные свои базовые стили, адаптивная сетка, настроенна работа с SASS и сборка проекта.