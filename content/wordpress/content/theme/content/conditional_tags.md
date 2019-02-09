# Условные теги

Это функции WordPress, которые удобно использовать в условиях:

Имя тега                      | Описание
---                           | ---
`is_admin()`                  | админ-панель WordPress
`is_home()`                   | страница блога
`is_front_page()`             | главная страница сайта
`is_single()`                 | страница одной статьи (вложения или CPT)
`is_singular()`               | любая статья, страница или вложение
`is_sticky()`                 | закреплённая статья
`is_page()`                   | статическая страница
`is_category()`               | страница со списком статей отдельной категории
`is_tag()`                    | страница со списком статей отдельной метки
`is_author()`                 | страница со списком статей отдельного автора
`is_search()`                 | страница с результатами поиска
`is_404()`                    | страница 404
`is_page_template()`          | используется ли шаблон страницы
`is_user_logged_in()`         | авторизированный пользователь
`is_post_type_hierarchical()` | тип поста поддерживает иерахию
`is_post_type_archive()`      | тип поста поддерживает архив
`is_comments_popup()`         | когда это всплывающее окно комментария
`is_tax()`                    | архив таксономии
`has_post_thumbnail()`        | имеет ли статья прикреплённое изображение
`has_excerpt()`               | имеет ли статья короткое описание
`has_tag()`                   | имеет ли статья тег
`has_term()`                  | имеет ли статья термин таксономии
`has_nav_menu()`              | назначено ли меню определённой области меню
`comments_open()`             | доступно ли комментирование
`get_post_type()`             | возвратит текущий тип поста
`post_type_exists()`          | проверит существует ли переданный тип поста
`in_the_loop()`               | находимся ли мы внутри цикла

Таких тегов очень много и можно проверить почти все сущости, которые есть в WordPress. В большинстве случаев на вход каждой функции можно передавать дополнительные параметры.

Простой пример использования:

```
if ( is_user_logged_in() ):
    echo 'Welcome, registered user!';
else:
    echo 'Welcome, visitor!';
endif;
```

Кроме этого в условиях можно использовать несколько тегов с помощью логических операторов:

```
if ( is_home() || is_single() ) {
   the_content();
}
else {
   the_excerpt();
}
```

Проверку можно делать сравнивая строки с тем что возвращает функция:

```
if ( 'book' == get_post_type() ) {
    the_title();
}
```

Пока что нет функции, которая бы могла проверить является ли текущая страница дочерней (имеет ли она родителя), хотя это можно сделать с помощью небольшого количества кода:

```
global $post; // if outside the loop
if ( is_page() && $post->post_parent ) {
    // This is a subpage
} else {
    // This is not a subpage
}
```

Можно создать свою собственную функцию `is_subpage()`:

```
function is_subpage() {
    global $post; // load details about this page
    if ( is_page() && $post->post_parent ) { // test to see if the page has a parent
        return $post->post_parent; // return the ID of the parent post
    } else { // there is no parent so ...
        return false; // ... the answer to the question is false
    }
}
```