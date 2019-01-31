# Кастомайзер

**Кастомайзер** - это фреймворк и раздел настроек WordPress темы, где вы можете вносить изменения в конфигурацию сайта или темы и в режиме реального времени видеть изменения на сайте.

## Пользовательский хэдер

Позволяет загружать изображение для заголовка страницы.

Для того чтобы подобавить поддержку пользовательського хедера в тему просто добавьте следующий код:

*functions.php*

```php
add_theme_support( 'custom-header' );
```

Можно использовать дополнительные параметры и несколько дефолтных изобржений:

*functions.php*

```php
/**
 * Theme Support
 */
function mytheme_support(){
    // Custom header via Customizer
    $args = array(
        'default-image' => get_template_directory_uri() . '/img/01.jpg',
        'flex-width' => true,
        'width' => 1000,
        'flex-height' => true,
        'height' => 250,
    );
    add_theme_support( 'custom-header', $args );
    // Register defaults images
    $header_images = array(
        'one' => array(
            'url' => get_template_directory_uri() . '/img/01.jpg',
            'thumbnail_url' => get_template_directory_uri() . '/img/01.jpg',
            'description' => 'Sunset',
        ),
        'two' => array(
            'url' => get_template_directory_uri() . '/img/02.jpg',
            'thumbnail_url' => get_template_directory_uri() . '/img/02.jpg',
            'description'  => 'Flower',
        ),
        'three' => array(
            'url' => get_template_directory_uri() . '/img/03.jpg',
            'thumbnail_url' => get_template_directory_uri() . '/img/03.jpg',
            'description' => 'Flower',
        ),
    );
    register_default_headers( $header_images );
}

add_action('after_setup_theme', 'mytheme_support');
```

* `width` и `height` устанавливают ширину и высоту для обрезки ображения после загрузки на сервер
* `flex-width` и `flex-height` позволяют изменять высоту и ширину обрезки изображения после загрузки изображения

Теперь у нас появился новый контейнер в кастомайзере и отдельный раздел меню *Appearance / Header* в админке WordPress.

Чтобы вывести изображение в шаблоне можно использовать такой код:

*header.php*

```php
<?php if ( get_header_image() ) : ?>
    <img src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
<?php endif; ?>
```

## Пользовательский логотип

Для того чтобы подобавить поддержку пользовательського логотипа в тему просто добавьте следующий код:

*functions.php*

```php
add_theme_support( 'custom-logo' );
```

Но можно передать дополнительные параметры:

*functions.php*

```php
function themename_custom_logo_setup() {
    $defaults = array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'themename_custom_logo_setup' );
```

Чтобы отобразить логотип нужно использовать функцию `the_custom_logo()`:

```php
if ( function_exists( 'the_custom_logo' ) ) {
    if(has_custom_logo()){
        the_custom_logo();
    }
    else{
        echo get_bloginfo('site_name');
    }
}
```

* `get_custom_logo()` - возвращает разметку пользовательського логотипа
* `the_custom_logo()` - отображает разметку пользовательського логотипа
* `has_custom_logo()` - возвращает `true` если пользовательский логотип задан