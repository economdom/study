# Принцип работы

Процесс очень прост и повторяется от плагина к плагину, от задачи к задаче.

* В файле *package.json* будет содержатся различная мета информация и куда будут прописыватся зависимости для вашего проекта. Для установки всех модулей вам достаточно будет запустить команду `npm install`

```
{
  "name": "test",
  "version": "1.0.0",
  "description": "Some description",
  "private": true,
  "author": "Vladimir Kamuz",
  "license": "ISC",
  "devDependencies": {
    "gulp": "^3.9.1"
  }
}
```

* В файле *gulpfile.js* будут содержатся определение плагинов и задачи для Gulp.
* В начале файла определяем переменные для плагинов. Названия переменных могут иметь произвольные названия, но вы должны понимать какой плагин там хранится. Эти переменные будут использоваться дальше в пайпах (вспомогательных функциях) и сами будут вызыватся как функции, в которые можно будет передать какие-то дополнительные параметры.

*gulpfile.js*

```
var gulp = require('gulp');
```

* Отдельные таски или задачи как правило создаются для отдельных направлений, например для решение каких то задач с HTML, CSS, JavaScript, изображениями, запуска локального сервера и т. д.

*gulpfile.js*

```
gulp.task('css', function() {
  return gulp.src('css/style.css')
    .pipe(rename('rename.css'))
    .pipe(gulp.dest('app/css'));
}); 
```

* Можно выполнять (из терминала) отдельные задачи (`gulp task_name`), а можно выполнять задачу по-умолчанию `default` и в таком случае имя задачи указывать не объязательно (`gulp`). Запуск Gulp может также запускатся при наступлении какого-то события, например изменения в файлах (с помощью плагина `watch`).

```
gulp
```

* Задача по умолчанию может включать другие задачи - как правило так и делают. 

*gulpfile.js*

```
gulp.task('default', ['watch', 'connect', 'html', 'css']);
```

> После каждого изменения *gulpfile.js* нужно заново перезапускать Gulp.

## Задача по умолчанию

Задача по умолчанию должна называтся `default` и нам нужно просто в массиве (3 версия) перечислить задачи которые нужно запустить по умолчанию.

```
gulp.task('default', ['styles', 'scripts', 'images', 'watch']);
```

В 4 версии вы можете использовать методы `parallel()` или `series()`

```
gulp.task('default', gulp.parallel('styles', 'scripts', 'images', 'watch'));
```

## Запуск и остановка задач

Для запуска задачи по умолчанию нужно просто выполнить

```
gulp
```

Для запуска отдельной задачи, например сборки `css` следует выполнить:

```
gulp css
```

Для остановки задач всего лишь нужно использовать комбинацию клавиш <kbd>Ctrl</kbd>+<kbd>C</kbd>.