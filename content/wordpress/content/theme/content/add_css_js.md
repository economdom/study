# Подключение стилей, скриптов

Сделать это можно несколькими путями, но самым правильный способ - это использовать файл *functions.php*, внутри которого мы можем прописать функции, которые будут автоматически выполнятся при загрузке страницы.

Для того чтобы наши стили и скрипты подгружались в тему, нужно в шаблоне вызвать функции `wp_head()` перед закрывающим тегом `head` и `wp_footer()` перед закрывающим тегом `body` - эти функции обеспечивают корректную работу темы и отдельных плагинов. Таким образом мы сообщаем WordPress где находится хедер и где футер и он сможет автоматически подгружать необходимые данные в хедер и футер нашей темы. По сути размещение этих функций в теме, указывает места, в каких местах нужно и можно выводить стили и скрипты, в том числе системные стили и скрипты WordPress.

Как только мы добавим эти функции, то у нас тут же подгрузятся системные файлы стилей и скриптов, которые например выведут тулбар WordPress. Но теперь нам нужно сообщить WordPress, что нам нужно подгрузить свои собственные стили и скрипты - для этого нам нужно написать собственную функцию.

Для этого мы будем использовать функции `wp_enqueue_script()` для подключения JavaScript файла и `wp_enqueue_style()` для подключения файла стилей. На вход данных функции нужно передавать уникальный идентификатор скрипта (хендлер), путь к нему, массив зависимостей, например jQuery для Bootstrap (если таковые имеются), версию и тип устройства (для стилей) и место подклчения скриптов (в футере или в хедере). Зависимости всегда подключаются ранее, чем скрипты, которые от него зависят (можете проверить в исходном коде).

```
wp_enqueue_style( $handle, $src, $deps, $ver, $media );
wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer);
```

Примеры использования:

*wp-content/themes/theme-name/functions.php*

```
wp_enqueue_style( 'slider', get_template_directory_uri() . '/css/slider.css',false,'1.1','all');
wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array ( 'jquery' ), 1.1, true);
```

Путь к папке с темой можно и указать вручную, но такой подход может вызвать проблемы в будущем, например при изменении названия темы, поэтому рекомендуется всегда использовать функцию, чтобы получать путь к папке с темой, например `get_template_directory_uri()`, которая вернёт путь к папке с нашей темой, при этом путь возвращается без слэша в конце.

Чтобы загрузить основую таблицу стилей можно использовать функцию `get_stylesheet_uri()`, которая вернёт путь к файлу *style.css* активной темы.

Если нужно подключить файлы с CDN, то это делается точно также в *functions.php*, но вместо каких либо функций для получения пути к файлу, мы просто использует абсолютный путь к файлу.

В комплекте с WordPress идёт огромное количество скриптов, которые мы можем вызывать по их идентификатору. Если вызвать функцию `wp_enqueue_script('jquery')`, то библиотека jQuery будет загружаться из системной папки WordPress *wp_includes/js/jquery/*.

Если вы хотите использовать другую версию jQuery используя этот же идентификатор, то нужно в начале разрегистрировать стандарный скрипт, а потом его заново зарегистрировать, но со своими параметрами.

```
add_action( 'wp_enqueue_scripts', 'my_scripts_method', 11 );
function my_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}
```

Чтобы вышеуказанные функции заработали, нам нужно оберунуть их в собственную функцию и повесить её на определённое событие WordPress. Экшн `wp_enqueue_scripts()` загружает во фронт-энд необходимые скрипты и стили WordPress. Навесим на неё нашу пользовательскую функцию `themename_load_cssjs()` с помощью `add_action()`, чтобы добавить в тему наши собственные скрипты и стили.

Можно вызов всех функций поместить в условие, где мы проверяем что мы находимся именно во фронт-энд части сайта, а не админке:

*wp-content/themes/bootstrap/functions.php*

```
if(!is_admin()) {
    // All functions here
}
```

Чтобы комментарии WordPress работали так как нужно (деревовидные комментарии, расширенные формы комментариев), следует подключать определённый JavaScript файл WordPress для каждой темы, которая использует комментарии. При этом, чтобы этот файл не вызывался в тех местах где он не нужен, следует сделать несколько проверок:

```
if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
}
```

А теперь всё вместе:

*wp-content/themes/bootstrap/functions.php*

```
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
        wp_enqueue_script('bootstrap-script', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array('jquery'));
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    add_action('wp_enqueue_scripts', 'themename_load_cssjs');
}
```