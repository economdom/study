# Отключение сообщения "JQMIGRATE: Migrate is installed, version 1.4.1"

Можно через тему или пользовательский плагином.

*functions.php*

```php
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});
```