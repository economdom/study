# Компиляция Less

Для этих целей можно использовать менеджер задач Gulp.

*gulpfile.js*

```
"use scrict"

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    cleancss = require('gulp-clean-css'),
    rename = require('gulp-rename'),
    watch = require('gulp-watch'),
    autoprefixer = require('gulp-autoprefixer'),
    less = require('gulp-less'),
    browsersync = require('browser-sync'),
    sourcemaps = require('gulp-sourcemaps');

gulp.task('html', function() {
  gulp.src('index.html')
});

gulp.task('css', function () {
  return gulp.src('app/less/*.less')
    .pipe(sourcemaps.init())
    .pipe(less())
    .pipe(concat("all.css"))
    .pipe(autoprefixer({ browsers: ['> 1%', 'IE 7'], cascade: false }))
    .pipe(cleancss())
    .pipe(rename("all.min.css"))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest("dist/css"))
});

gulp.task('browsersync', function() {
  return browsersync({
    server: {
      baseDir:'./'
    }
  });
});

gulp.task('watch', function() {
  gulp.watch("app/less/*.less", ['css', browsersync.reload]);
});

gulp.task('default', ['watch', 'browsersync', 'html', 'css', 'js']);
```