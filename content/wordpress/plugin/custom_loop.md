# Пользовательский цикл WordPress

Существует 3 основных техник для настройки цикла:

* `get_posts()` - получает список постов согласно переданных параметров
* `pre_get_posts()` - настройка основного цикла WordPress
* `WP_Query` - создания пользовательских и множественных циклов

Создадим три плагина для демонстрации работы.

*custom_loop_get_posts.php*

```php
<?php

/**
 * Plugin Name: Custom Loop with get_posts
 * Description: Demonstrate how to customize the WordPress Loop using get_posts()
 * Plugin URL: https://wpninja.pp.ua
 * Author: Vladimir Kamuz
 * Version: 1.0
 */

/**
 * Custom Loop shortcode with [get_posts_example]
 */
function custom_loop_shortcode_get_posts($atts){

    // get global post variable
    global $post;

    // define shortcode variable
    extract(shortcode_atts(array(
        'posts_per_page' => 5,
        'orderby' => 'date'
    ), $atts));

    // define get_posts parameters
    $args = array(
        'posts_per_page' => $posts_per_page,
        'orderby' => $orderby
    );

    // get the posts
    $posts = get_posts($args);

    // begin output variable
    $output = '<h3>Custom Loop Example: get_posts()</h3>';
    $output .= '<ul>';

    // loop thru posts
    foreach($posts as $post){

        // prepare post data
        setup_postdata($post);

        // continue output
        $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';

    }

    // reset post data
    wp_reset_postdata();

    // complete output variable
    $output .= '</ul>';

    // return output
    return $output;
}

// register shortcode function
add_shortcode('get_posts_example', 'custom_loop_shortcode_get_posts');
```

Этот плагин создаёт шорткод `[get_posts_example]` который будет выводить нам список постов в виде ссылок по заданным параметрам. Мы можем его вызвать в любом месте на странице и он вернёт нам список статей блога.

```
[get_posts_example posts_per_page="2" orderby="title"]
```

*custom_loop_pre_get_posts.php*

```php
<?php

/**
 * Plugin Name: Custom Loop with pre_get_posts
 * Description: Demonstrate how to customize the WordPress Standart Loop using pre_get_posts()
 * Plugin URL: https://wpninja.pp.ua
 * Author: Vladimir Kamuz
 * Version: 1.0
 */

/**
 * Custom Loop using pre_get_posts hook
 */
function custom_loop_pre_get_posts($query){

    if(!is_admin() && $query->is_main_query()){
        $query->set('posts_per_page', 1);
        $query->set('order', 'ASC');
        $query->set('cat', '5');
        $query->set('post_type', array('post', 'page', 'product', 'movie'));
    }
}

// add action
add_action('pre_get_posts', 'custom_loop_pre_get_posts');
```

Этот плагин просто переопределяет параметры стандартного цикла блога на вашем сайте.

*custom_loop_wp_query.php*

```php
<?php

/**
 * Plugin Name: Custom Loop with WP_Query class
 * Description: Demonstrate how to customize the WordPress Loop WP_Query class
 * Plugin URL: https://wpninja.pp.ua
 * Author: Vladimir Kamuz
 * Version: 1.0
 */

/**
 * Custom Loop shortcode with [wp_query_example]
 */
function custom_loop_pre_shortcode_wp_query($atts){

    // define shortcode variable
    extract(shortcode_atts(array(
        'posts_per_page' => 5,
        'orderby' => 'date',
        'cat' => array(3, 4, 5, 8)
    ), $atts));

    // define get_posts parameters
    $args = array(
        'posts_per_page' => $posts_per_page,
        'orderby' => $orderby,
        'cat' => $cat
    );

    // query the posts
    $posts = new WP_Query($args);

    // begin output variable
    $output = '<h3>' . esc_html__('Custom Loop Example: WP_Query', 'myplugin') . '</h3>';

    if($posts->have_posts()){
        while($posts->have_posts()): $posts->the_post(); 
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '<p>' . wp_trim_words(get_the_content(), 70, '...') . '</p>';
            $output .= '<a class="btn btn-primary" href="' . get_permalink() . '">Read more...</a>';
            $output .= '<hr>';
        endwhile;
    }
    else{
        $output .= '<div class="alert alert-danger">' . esc_html__('Sorry, no posts matched your criteria.', 'myplugin') . '</div>';
    }

    // reset post data
    wp_reset_postdata();

    // return output
    return $output;

}

// register shortcode function
add_shortcode('wp_query_example', 'custom_loop_pre_shortcode_wp_query');
```

Класс `WP_Query` считается самым гибким способом по работе с БД WordPress. Шорткод работает таким же самым образом как и первый плагин, но вывод постов формируется в другом виде.

```
[wp_query_example posts_per_page="4" orderby="rand" cat="3, 4, -8, -5"]
```