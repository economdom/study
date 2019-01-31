# Поиск

Шаблон отвечающий за результаты поиска *search.php*. Чтобы на странице с результатами поиска отображалось поисковая фраза можно воспользоваться функцией `get_search_query()`.

```php
<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyfifteen' ), get_search_query() ); ?></h1>
```