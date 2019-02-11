# SASS

```
npm -v
npm init
```

*package.json*

```
{
    "name": "sass-gulp",
    "version": "1.0.0",
    "description": "",
    "main": "index.js",
    "scripts": {
        "test": "echo \"Error: no test specified\" && exit 1"
    },
    "author": "",
    "license": "ISC"
}
```

*public/index.html*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/bundle.min.css">
</head>
<body>

</body>
</html>
```

*src/scss/index.scss*

```
body{
    background: red;
    color: white;
}
```

Устанавливаем Gulp глобально

```
npm i -g gulp
```

Устанавливаем пакеты для конкретного проекта, которые будут использоваться во время разработки:

```
npm i --save-dev gulp gulp-sass gulp-autoprefixer gulp-sourcemaps gulp-concat browser-sync
```

Создаём файл *gulpfile.js*, в котором:

* Подключим все установленные пакеты
* У объекта `browser-sync` мы сразу же вызываем метод `create()` чтобы проинициализировать новый сервер
* Прописываем пути к файлам SCSS и к папке *public* в свойстве-объекте `path`
* В свойстве объекте `output` мы укажем куда и в какой формат мы будем конкатенировать стили - `cssName` это имя CSS файла, а `path` это путь к директории из которой мы будем запускать Browsersync
* Создаём новую задачу и настраиваем пайпы
* Добавляем такс по умолчанию

*gulpfile.js*

```
var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    browsersync = require('browser-sync');

var config = {
    path: {
        scss: './src/scss/**/*.scss',
        html: './public/index.html'
    },
    output: {
        cssName: './css/bundle.min.css',
        path: './public'
    }
};

gulp.task('scss', function(){
    return gulp.src(config.path.scss)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(concat(config.output.cssName))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(config.output.path))
});

gulp.task('default', ['scss']);
```

Добавим стилей с вложенностью и чтобы добавлялись преффисы:

```
body {
    background-color: teal;
    p {
        box-shadow: #000 0 10px 10px;
    }
}
```

* Создадим задачу для сервера, в которой будем инициализировать Browsersync
* Чтобы синхронизировать задачу `scss` c Browsersync добавим ещё один пайп в котором будем вызовем метод Browsersync `stream()`.
* Добавим данную задачу в дефолтный такс