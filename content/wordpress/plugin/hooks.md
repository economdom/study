# Хуки - экшены и фильтры

При загрузке страницы WordPress одна за одной выполняются множество функций, часть из которых являются одновременно **хуками**. Для того чтобы мы могли расширять возможности WordPress без необходимости изменять ядро предоставляет различные API. В основе расширения возможностей WordPress лежит система хуков или крючков.

**Хуки** - это стандартные функции WordPress, на которые мы можем прицепить другую функцию (callback функция обратного вызова), которая будет выполнять для нас какие-то дополнительные полезные действия и срабатывать перед тем как выполнится та функция, на которую мы навесили эту функцию (хук). Например у нас есть стандартная функция `the_content()` которая просто выводит содержимое страницы или поста, а мы можем повесить/прицепить ещё одну функцию, которая например будет как-то изменять содержимое страницы перед тем как вывести его на экран.

Хуки бывают двух видов - фильтры и экшены. **Фильтры** просто принимают какой-то контент и могут его как-то изменять (изменение контента поста перед отправкой пользователю и т. д.). **Экшены** могут выполнять любые действия, которые мы поместим в нашу пользовательскую функцию (вывод контента, запись в файл, изменение БД и т. д.). С программной точки зрения и фильтры и экшены представляют собой одно и тоже, но разделяются условно по типу выполняемой работы.

```php
add_filter( $tag, $function_to_add, $priority, $accepted_args );
add_action( $tag, $function_to_add, $priority, $accepted_args );
```

* `$tag` - название той функции к которой мы хотим прицепится
* `$function_to_add` - функция, которую мы хотим добавить к текущей функции
* `$priority` - приоритет выполнения функции для данного хука
* `$accepted_args` - количество принимаемых аргументов

Чтобы мы могли тестировать разный код плагина мы будем подключать отдельные файлы внутри основного файла плагина:

*wp-content/plugins/kmz-simple/kmz-simple.php*

```php
include_once(plugin_dir_path(__FILE__) . '/action-hook.php');
```

При каждой загрузке страницы (экшн `init`) мы будем вешать созданную нами функцию, которая будет отправлять почту:

*wp-content/plugins/kmz-simple/action-hook.php*

```php
<?php

function kmz_action_hook_example(){
    wp_mail('email@example.com', 'Subject', 'Message...');
}
add_action('init', 'kmz_action_hook_example');
```

> [Plugin API/Filter Reference](https://codex.wordpress.org/Plugin_API/Action_Reference) - список популярных экшенов WordPress и порядок их срабатывания во время HTTP запроса во фронт энд и админ-части WordPress.

*wp-content/plugins/kmz-simple/kmz-simple.php*

```php
include_once(plugin_dir_path(__FILE__) . '/filter-hook.php');
```

Для каждого контента WordPress допишем дополнительный параграф в начало.

*wp-content/plugins/kmz-simple/filter-hook.php*

```php
<?php

function kmz_filter_hook_example( $content ){
    $content = '</p>Custom content...</p>' . $content;
    return $content;
}
add_filter('the_content', 'kmz_filter_hook_example');
```

> [Plugin API/Filter Reference](https://codex.wordpress.org/Plugin_API/Filter_Reference) - список популярных фильтров WordPress.