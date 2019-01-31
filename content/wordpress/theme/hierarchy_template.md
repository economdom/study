# Иерархия шаблонов

Иерархия шаблонов определяет какие файлы шаблоны к каким именно страницам будут применяться. WordPress использует HTTP запрос, чтобы определить какой шаблон использовать для отображения текущей страницы.

WordPress ищет специально именнованые файлы шаблонов в папке с текущей темой и использует первый подходящий. За исключением основного шаблона *index.php*, разработчики тем могут использовать или не использовать файлы шаблонов по желанию. Если WordPress не находит шаблон с указанным именем, он переходит к следущему по иерархии шаблону.

Например, если ваш сайт находится по адресу `http://sitename.com` и посетитель переходит на страницу рубрики `http://sitename.com/category/html/`, WordPress в папке текущей темы в первую очередь ищет шаблон, который отвечает за вывод этой рубрики (`html`). Если ID данной рубрики `4`, WordPress ищет шаблон с именем *category-4.php*. Если такой шаблон не найден, WordPress ищет общий для всех рубрик шаблон *category.php*. Если и такого шаблона нет, WordPress ищет шаблон для вывода архива *archive.php*. Если нет и этого файла, WordPress будет использовать основной шаблон темы *index.php*.

Примеры шаблонов, их описания и соответвия URL:

Файл                          | Описание                             | Пример URL
---                           |---                                   |---
*home.php*                    | страница блога со списком статей     | http://example.com/
*front-page.php*              | главная страница сайта               | http://example.com/
*single.php*                  | отдельная статья блога               | http://example.com/?p=1
*page.php*                    | отдельная страница                   | http://example.com/?page_id=2
*404.php*                     | страница 404                         | http://example.com/?dfdfdfdfd
*search.php*                  | страница результатов поиска          | http://example.com/?s=searchterm
*tag.php*                     | список статей определённой метки     | http://example.com/?tag=test
*category.php*                | список статей определённой категории | http://example.com/?cat=1
*author.php*                  | список статей определённого автора   | http://example.com/?author=1
*archive.php*                 | список статей указанного года        | http://example.com/?m=2014

Иерархия файлов шаблонов:

```html
index.php
├─ home.php
│  └─ front-page.php 
├─ 404.php
├─ search.php
├─ archive.php
│  ├─ date.php
│  ├─ author.php
│  │  └─ author-$id.php
│  │     └─ author-$nicename.php
│  ├─ category.php
│  │  └─ category-$id.php
│  │     └─ category-$slug.php
│  ├─ tag.php
│  │  └─ tag-$id.php
│  │     └─ tag-$slug.php
│  ├─ taxonomy.php
│  │  └─ taxonomy-$taxonomy.php
│  │     └─ taxonomy-$taxonomy-$term.php
│  └─ archive-$posttype.php
└─ singular.php
   ├─ single.php
   │  ├─ single-$posttype.php
   │  ├─ single-post.php
   │  └─ attachment.php
   │     └─ $mimetype-$subtype.php
   │        └─ $subtype.php
   │           └─ $mimetype.php
   └─ page.php
      └─ page-$id.php
         └─ page-$slug.php
            └─ $custom.php
```

Для проверки на какой странице вы сейчас находитесь есть одноименные функции, например `is_page()`, `is_category()`, `is_tax()` (taxonomy), `is_attachment()` и т. д.

![Hierarchy Template](img/template-hierarchy.png)