# Обращение к файлам и папкам темы

Если в вашей теме встречаются изображения то их можно выводить используя `bloginfo('template_url')`.

```php
<img src="<?php bloginfo('template_url'); ?>/images/logo.png">
```

В качестве альтернативы можно использовать функцию `get_template_directory_uri()`:

```php
<img src="<?php echo get_template_directory_uri()?>/images/logo.png">
```

Или `get_theme_file_uri();`.

```php
<img src="echo get_theme_file_uri( 'images/logo.png' )">
```

Чтобы получить абсолютный путь к папке можно использовать функцию `get_theme_file_path()`.

Используя функции `get_theme_file_uri()` и `get_theme_file_path()` в случае, если файл не будет найден в дочерней теме, WordPress будет искать этот файл в родительской.

Чтобы получать файл в родительской теме можно использовать `get_parent_theme_file_uri()` и `get_parent_theme_file_path()`.

Функции `get_theme_file_uri()`, `get_theme_file_path()`, `get_parent_theme_file_uri()`, `get_parent_theme_file_path()` появились в WordPress 4.7, а до этого использовались функции `get_template_directory_uri()`, `get_template_directory()`, `get_stylesheet_directory_uri()`, `get_stylesheet_directory()`.