# Подключение стилей, скриптов

Сделать это можно несколькими путями, но самым правильный способ - это использовать файл *functions.php*, внутри которого мы можем прописать функции, которые будут автоматически выполнятся при загрузке страницы.

Для того чтобы наши стили и скрипты подгружались в тему, нужно в шаблоне вызвать функции `wp_head()` перед закрывающим тегом `head` и `wp_footer()` перед закрывающим тегом `body` - эти функции обеспечивают корректную работу темы и отдельных плагинов. Таким образом мы сообщаем WordPress где находится хедер и где футер и он сможет автоматически подгружать необходимые данные в хедер и футер нашей темы.

Как только мы добавим эти функции, то у нас тут же подгрузятся системные файлы стилей и скриптов, которые например выведут тулбар WordPress. Но теперь нам нужно сообщить WordPress, что нам нужно подгрузить свои собственные стили и скрипты - для этого нам нужно написать собственную функцию.

Для этого мы будем использовать функции `wp_enqueue_script()` для подключения JavaScript файла и `wp_enqueue_style()` для подключения файла стилей. На вход данных функции нужно передавать уникальный идентификатор скрипта (хендлер), путь к нему и массив зависимостей (например jQuery для Bootstrap), если таковые имеются.

```php
wp_enqueue_style( $handle, $src, $deps, $ver, $media );
wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer);
```

Примеры использования:

```php
wp_enqueue_style( 'slider', get_template_directory_uri() . '/css/slider.css',false,'1.1','all');
wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array ( 'jquery' ), 1.1, true);
```

Экшн `wp_enqueue_scripts()` загружает во фронт-энд необходимые скрипты и стили WordPress. Навесим на неё нашу пользовательскую функцию `themename_load_cssjs()` с помощью `add_action()`, чтобы добавить в тему наши собственные скрипты и стили.

Чтобы загрузить основую таблицу стилей можно использовать `wp_enqueue_style( 'style', get_stylesheet_uri() )`.

Если вызвать функцию `wp_enqueue_script('jquery')`, то библиотека jQuery будет загружаться из системной папки WordPress *wp_includes/js/jquery/*.

Можно вызов всех функций поместить в условие, где мы проверяем что мы находимся именно во фронт-энд части сайта, а не админке:

*wp-content/themes/bootstrap/functions.php*

```php
if(!is_admin()) {
    // All functions here
}
```

Чтобы комментарии WordPress работали так как нужно (деревовидные комментарии, расширенные формы комментариев), следует подключать определённый JavaScript файл WordPress для каждой темы, которая использует комментарии. При этом, чтобы этот файл не вызывался в тех местах где он не нужен, следует сделать несколько проверок:

```php
if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
}
```

А теперь всё вместе:

*wp-content/themes/bootstrap/functions.php*

```php
<?php
if(!is_admin()) {

    /**
     * Loaded custom styles and scripts
     */

    function themename_load_cssjs() {
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/css/bootstrap.css');
        wp_enqueue_style('main-style', get_template_directory_uri() . '/css/main.css');
        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/js/bootstrap.min.js', ['jquery']);
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    add_action('wp_enqueue_scripts', 'themename_load_cssjs');
}
```