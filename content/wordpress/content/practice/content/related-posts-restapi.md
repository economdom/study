# Похожие записи с использованием REST API

Чтобы найти похожие записи нам нужно подставлять посты из той же категории что и текущий пост. Для этого нам подойдёт примерно такой URL для WP REST API: `GET /wp-json/wp/v2/posts?categories=198,4&per_page=2`.

В самом начале создадим хедер для основного файла плагина:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
<?php
/*
Plugin Name: KMZ Related Posts REST
Description: Display links to related posts through the WP REST API
Version: 0.1
Author: Vladimir Kamuz
Author URI: https://wpdev.pp.ua
Plugin URI: https://wpdev.pp.ua/kmzrel_rest
Licence: GPL2
Text Domain: kmzrelrest
*/
```

Подключим CSS:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Load CSS and JavaScript files
 */
function kmzrelrest_css_js() {
    if( is_single() && is_main_query() ) {
        // Get plugin styles
        wp_enqueue_style( 'kmzrelres_main_css', plugin_dir_url(__FILE__) . 'css/style.css', '0.1', 'all' );
    }
}
add_action( 'wp_enqueue_scripts', 'kmzrelrest_css_js' );
```

Выведем пока что тестовый блок после контента статьи:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Output HTML onto bottom of sinle post
 */
function kmzrelrest_display($content){
    if( is_single() && is_main_query() ) {
        $content .= '<h3>Hello, REST API</h3>';
    }
    return $content;
}
add_filter( 'the_content', 'kmzrelrest_display' );
```

Теперь на нескольких строках выведем уже кнопку, которая нам уже будет полезная:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Output HTML onto bottom of sinle post
 */
function kmzrelrest_display($content){
    if( is_single() && is_main_query() ) {
        $content  = '<section id="related-posts" class="related-posts">';
        $content .= '<a href="#" class="get-related-posts">Get related posts</a>';
        $content .= '<div class="ajax-loader"><img src="' . plugin_dir_url( __FILE__ ) . 'css/spinner.svg" width="32" height="32" /></div>';
        $content .= '</section><!-- .related-posts -->';
    }
    return $content;
}
add_filter( 'the_content', 'kmzrelrest_display' );
```

Видим что уже применились наши стили, также анимационный спинер у нас по умолчанию скрывается.

Теперь сформируем URL для REST API, которым будет генерировать динамически для текущей страницы:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Create REST API URL
 * - Get the current categories
 * - Get the category IDs
 * - Create the arguments for categories and pagination
 * - Create URL (example - /wp-json/wp/v2/posts?categories=198,4&per_page=5)
 */
function kmzrelrest_get_json_query(){
    $cats = get_the_category();
    $cat_ids = array();
    foreach( $cats as $cat ) {
        $cat_ids[] = $cat->term_id;
    }

    $args = array(
        'categories' => implode(",", $cat_ids),
        'per_page' => 5
    );

    $url = add_query_arg( $args, rest_url('wp/v2/posts') );

    return $url;
}
```

Проверим результат:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
function kmzrelrest_display($content){
    if( is_single() && is_main_query() ) {
        $content .= '<a href="' . kmzrelrest_get_json_query() . '">' . kmzrelrest_get_json_query() . '</a>';
        $content .= '<section id="related-posts" class="related-posts">';
        //..
```

Создадим файл скриптов, в котором пока что создадим обработчик кнопки по клику:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
(function($){
    $('.get-related-posts').on('click', function(event){
        event.preventDefault();
        console.log("Click!!!");
    })
})(jQuery);
```

Подключим скрипт и проверим работу по клику:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
function kmzrelrest_css_js() {
    if( is_single() && is_main_query() ) {
        // Get plugin styles
        wp_enqueue_style( 'kmzrelres_main_css', plugin_dir_url(__FILE__) . 'css/style.css', '0.1', 'all' );
        wp_enqueue_script( 'kmzrelres_main_js', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '0.1', true );
    }
}
```

В WordPress есть функция `wp_localize_script()`, которая как правило используется для того чтобы передавать переменные перевода в JavaScript, но в нашем случае эта функция удобна тем что с её помощью мы можем передавать данные из PHP в наш JavaScript файл, например URL для REST API и любую другую информацию. Данная функция принимает три параметра: идентификатор подключённого JavaScript файла, префикс передаваемый в переменную и массив передаваемых данных.

Передадим созданные ранее URL и ID текущего поста:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Load CSS and JavaScript files
 */
function kmzrelrest_css_js() {
    if( is_single() && is_main_query() ) {
        // Get plugin styles
        wp_enqueue_style( 'kmzrelres_main_css', plugin_dir_url(__FILE__) . 'css/style.css', '0.1', 'all' );
        wp_enqueue_script( 'kmzrelres_main_js', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '0.1', true );

        global $post;
        $post_id = $post->ID;

        // Send data to JavaScript
        wp_localize_script('kmzrelres_main_js', 'postdata',
            array(
                'json_url' => kmzrelrest_get_json_query(),
                'post_id' => $post_id
            )
        );
    }
}
```

Теперь выведем эти данные в консоль при клике:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
(function($){
    $('.get-related-posts').on('click', function(event){
        event.preventDefault();
        var jsonUrl = postdata.json_url;
        var postId = postdata.post_id;
        console.log(jsonUrl);
        console.log(postId);
    })
})(jQuery);
```

Теперь получим первый результат в результате AJAX запроса:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
(function($){
    $('.get-related-posts').on('click', function(event){
        event.preventDefault();
        var jsonUrl = postdata.json_url;
        var postId = postdata.post_id;

        // The AJAX request
        $.ajax({
            dataType: 'json',
            url: jsonUrl
        })

        .done(function(response){
            console.log(response);
            // Loop throught each of the related posts
            $.each(response, function(index, object){
                $('#related-posts').append('<h3>' + object.title.rendered + '</h3>');
            });
        })

        .fail(function(){
            console.log('Sorry, AJAX request failed!');
        })

        .always(function(){
            console.log('Complete!');
        });

    })
})(jQuery);
```

Добавим чуть больше деталей:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
(function($){
    $('.get-related-posts').on('click', function(event){
        event.preventDefault();
        var jsonUrl = postdata.json_url;
        var postId = postdata.post_id;

        // The AJAX request
        $.ajax({
            dataType: 'json',
            url: jsonUrl
        })

        .done(function(response){
            console.log(response);
            // Loop throught each of the related posts
            $.each(response, function(index, object){
                // Set up HTML to be added
                var related_loop =  '<aside class="related-post clear">' +
                                    '<a href="' + object.link + '">' +
                                    '<h3 class="related-post-title">' + object.title.rendered + '</h3>' +
                                    '<div class="related-excerpt">' +
                                    object.excerpt.rendered +
                                    '</div>' +
                                    '</a>' +
                                    '</aside>';

                // Append HTML to existing content
                $('#related-posts').append(related_loop);
            });
        })

        .fail(function(){
            console.log('Sorry, AJAX request failed!');
        })

        .always(function(){
            console.log('Complete!');
        });

    })
})(jQuery);
```

Скроем лишнее из анотации:

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
/**
 * Remove Read More links from all excerpts
 */
function custom_excerpt_more( $more ) {
    return '…';
}
add_filter( 'excerpt_more', 'custom_excerpt_more', 100);
```

Теперь нам нужно вывести имя автора поста и изображение, но пока что у нас к этой информации нет доступа - мы имеет только ID автора и ID вложения. Чтобы получить эту информацию нам нужно немного изменить наш REST запрос добавив `_embed=true` - `GET http://wordpress.loc/wp-json/wp/v2/posts?&categories=198,4&per_page=2&_embed=true`.

*wp-content/plugins/kmz-related-posts-restapi/kmz-related-posts-restapi.php*

```
function kmzrelrest_get_json_query(){
    $cats = get_the_category();
    $cat_ids = array();
    foreach( $cats as $cat ) {
        $cat_ids[] = $cat->term_id;
    }

    $args = array(
        'categories' => implode(",", $cat_ids),
        'per_page' => 5,
        '_embed' => true
    );

    $url = add_query_arg( $args, rest_url('wp/v2/posts') );

    return $url;
}
```

И теперь осталось вывести имя автора при AJAX запросе:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
.done(function(response){
    console.log(response);
    // Loop throught each of the related posts
    $.each(response, function(index, object){
        // Set up HTML to be added
        var related_loop =  '<aside class="related-post clear">' +
                            '<a href="' + object.link + '">' +
                            '<h3 class="related-post-title">' + object.title.rendered + '</h3>' +
                            '<p class="related-author">by <em>' + object._embedded.author[0].name + '</em></p>' +
                            '<div class="related-excerpt">' +
                            object.excerpt.rendered +
                            '</div>' +
                            '</a>' +
                            '</aside>';

        // Append HTML to existing content
        $('#related-posts').append(related_loop);
    });
})
```

Для вывода изображения, создадим вспомагательную функцию и выведем результат:

*wp-content/plugins/kmz-related-posts-restapi/js/script.js*

```
$.each(response, function(index, object){
    function get_featured_image(){
        var feat_img;
        if(object.featured_media == 0){
            feat_img = '';
        } else {
            feat_img = '<figure class="related-featured"><img src="' + object._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail.source_url + '" alt="' + object.title.rendered +'"></figure>';
        }
        return feat_img;
    }
    // Set up HTML to be added
    var related_loop =  '<aside class="related-post clear">' +
                        '<a href="' + object.link + '">' +
                        '<h3 class="related-post-title">' + object.title.rendered + '</h3>' +
                        '<p class="related-author">by <em>' + object._embedded.author[0].name + '</em></div>' +
                        '<div class="related-excerpt">' +
                        get_featured_image() + 
                        object.excerpt.rendered +
                        '</div>' +
                        '</a>' +
                        '</aside>';

    // Append HTML to existing content
    $('#related-posts').append(related_loop);
});
```

У нас есть спиннер, который пока скрыт через CSS, мы же через JavaScript сначала его покажем, а потом скроем:

```
(function($){
    $('.get-related-posts').on('click', function(event){
        event.preventDefault();
        
        $('.ajax-loader').show();

        var jsonUrl = postdata.json_url;
        var postId = postdata.post_id;

        // The AJAX request
        $.ajax({
            dataType: 'json',
            url: jsonUrl
        })

        .done(function(response){
            console.log(response);
            // Loop throught each of the related posts
            $.each(response, function(index, object){
                function get_featured_image(){
                    var feat_img;
                    if(object.featured_media == 0){
                        feat_img = '';
                    } else {
                        feat_img = '<figure class="related-featured"><img src="' + object._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail.source_url + '" alt="' + object.title.rendered +'"></figure>';
                    }
                    return feat_img;
                }
                // Set up HTML to be added
                var related_loop =  '<aside class="related-post clear">' +
                                    '<a href="' + object.link + '">' +
                                    '<h3 class="related-post-title">' + object.title.rendered + '</h3>' +
                                    '<p class="related-author">by <em>' + object._embedded.author[0].name + '</em></div>' +
                                    '<div class="related-excerpt">' +
                                    get_featured_image() + 
                                    object.excerpt.rendered +
                                    '</div>' +
                                    '</a>' +
                                    '</aside>';
                $('.ajax-loader').hide();
                // Append HTML to existing content
                $('#related-posts').append(related_loop);
            });
        })

        .fail(function(){
            console.log('Sorry, AJAX request failed!');
        })

        .always(function(){
            console.log('Complete!');
        });

    })
})(jQuery);
```