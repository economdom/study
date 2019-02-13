# Настройка окружения

В корень ложим файл с конфигом Gulp:

*gulpfile.js*

```
// Add modules
var gulp = require('gulp'),
    gutil = require('gulp-util' ),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync'),
    cleanCSS = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    bourbon = require('node-bourbon'),
    ftp = require('vinyl-ftp');

// Update pages on local server
gulp.task('browser-sync', function() {
    browserSync({
        proxy: "opencart.loc",
        notify: false
    });
});

// Compile stylesheet.css
gulp.task('sass', function() {
    return gulp.src('catalog/view/theme/apple/stylesheet/stylesheet.scss')
        .pipe(sass({
            includePaths: bourbon.includePaths
        }).on('error', sass.logError))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(cleanCSS())
        .pipe(gulp.dest('catalog/view/theme/apple/stylesheet/'))
        .pipe(browserSync.reload({stream: true}))
});

// Watch files
gulp.task('watch', ['sass', 'browser-sync'], function() {
    gulp.watch('catalog/view/theme/apple/stylesheet/stylesheet.scss', ['sass']);
    gulp.watch('catalog/view/theme/apple/template/**/*.tpl', browserSync.reload);
    gulp.watch('catalog/view/theme/apple/js/**/*.js', browserSync.reload);
    gulp.watch('catalog/view/theme/apple/libs/**/*', browserSync.reload);
});

// Deploy to remote server
gulp.task('deploy', function() {
    var conn = ftp.create({
        host 'hostname.com',
        user: 'username',
        password: 'userpassword',
        parallel: 10,
        log: gutil.log
    });
    var globs = [
    'catalog/view/theme/apple/**'
    ];
    return gulp.src(globs, {buffer: false})
    .pipe(conn.dest('/path/to/folder/on/server'));
});

gulp.task('default', ['watch']);
```

Как видно из конфига, название темы у нас будет *catalog/view/theme/apple/*.

Добавим *package.json* чтобы установить необходимые пакеты:

*package.json*

```
{
  "name": "opencart-project",
  "version": "1.0.0",
  "description": "OpenCart Gulp Project",
  "main": "gulpfile.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "author": "Vladimir Kamuz",
  "license": "ISC",
  "devDependencies": {
    "browser-sync": "^2.15.0",
    "gulp": "^3.9.1",
    "gulp-autoprefixer": "^3.1.1",
    "gulp-clean-css": "^2.0.12",
    "gulp-sass": "^2.3.2",
    "gulp-util": "^3.0.7",
    "node-bourbon": "^4.2.8",
    "vinyl-ftp": "^0.5.0"
  }
}
```

Устанавливаем Node.js, если еще не стоит. Ставим Gulp глобально:

```
npm i -g gulp
```

Устанавливаем необходимые модули:

```
npm i
```

Если что-то неполучилось можно попробовать эту команду (обновляем старые версии):

```
npm i npm-check-updates -g
```

Проверим требуются ли обновления в папке *node_modules/* (`npm check updates`).

```
ncu
```

Добавим необходимые стили для старта:

*catalog/view/theme/apple/stylesheet/stylesheet.scss*

```
@import "bourbon";
@import "vars";
@import "fonts";

//Custom styles here

@import "media";
```

*catalog/view/theme/apple/stylesheet/_vars.scss*

```
@import "bourbon";

$default-font: "roboto", sans-serif;
$accent: orange;
```

*catalog/view/theme/apple/stylesheet/_media.scss*

```
@import "bourbon";
@import "vars";

/*==========  Desktop First  ==========*/

/* Large Devices, Wide Screens */
@media only screen and (max-width : 1200px)
    /**/

/* Medium Devices, Desktops */
@media only screen and (max-width : 992px)
    /**/

/* Small Devices, Tablets */
@media only screen and (max-width : 768px)
    /**/

/* Extra Small Devices, Phones */
@media only screen and (max-width : 480px)
    /**/

/* Custom, iPhone Retina */
@media only screen and (max-width : 320px)
    /**/

/*==========  Mobile First  ==========*/

/* Custom, iPhone Retina */
@media only screen and (min-width : 320px)
    /**/

/* Extra Small Devices, Phones */
@media only screen and (min-width : 480px)
    /**/

/* Small Devices, Tablets */
@media only screen and (min-width : 768px)
    /**/

/* Medium Devices, Desktops */
@media only screen and (min-width : 992px)
    /**/

/* Large Devices, Wide Screens */
@media only screen and (min-width : 1200px)
    /**/
```

*catalog/view/theme/apple/stylesheet/_fonts.scss*

```
@import "bourbon";

+font-face("roboto", "../fonts/RobotoRegular/RobotoRegular", $file-formats: eot woff ttf);
+font-face("roboto", "../fonts/RobotoBold/RobotoBold", bold, $file-formats: eot woff ttf);
```

Установим Bower:

```
npm install -g bower
```

Указываем Bower куда грузить библиотеки:

*bowerrc*

```
{
    "directory": "catalog/view/theme/apple/libs/"
}