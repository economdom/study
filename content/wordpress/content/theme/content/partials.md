# Части шаблона

Мы можем разбивать отдельные шаблоны на блоки:

* *header.php* - хедер
* *footer.php* - футер
* *sidebar.php* - сайдбар

Основной контент, который будет менятся от страницы к странице будет находится в других шаблонах (*index.php*, *page.php*, *single.php* и т. д.).

Для подключения данных файлов в шаблоне нужно использовать одноименные функции:

* `get_header()`
* `get_footer()`
* `get_sidebar()`

Вы можете создать пользовательские части шаблона `sidebar-{your_custom_template}.php`, `header-{your_custom_template}.php` и `footer-{your_custom_template}.php` и передавать их в качестве параметра:

```
get_header( 'your_custom_template' );
get_sidebar( 'your_custom_template' );
get_footer( 'your_custom_template' );
```

Кроме подключения хедера, футера и сайдбара, можно использовать функцию `get_template_part()` на вход которой нужно указать путь с названием файла шаблона `{slug-template}.php`. Фактически это аналог функции `include()` или `require()` в PHP.

```
<?php get_sidebar(); ?>
<?php get_template_part( 'slug-template' ); ?>
<?php get_footer(); ?>
```

Если вы хотите создать несколько файлов шаблона для контента для различных целей (например для различных типов или форматов постов), тогда можно создать базовый шаблон с названием *content.php*, а потом расширять название таким образом *content-product.php*. Подключить в шаблоне можно так:

```
get_template_part( 'content', 'product' );
```

Можно разложить шаблоны по директориям:

```
get_template_part( 'content-templates/content', 'location' );
get_template_part( 'content-templates/content', 'product' );
get_template_part( 'content-templates/content', 'profile' );
```