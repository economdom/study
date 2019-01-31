# Структура папок и файлов

Перед началом работы у вас уже должна быть вёрстка для вашей темы.

Обьязательных файлов 2, которые должны находится в корне вашей темы и без них тема работать не будет:

* *index.php* - шаблон главной страницы вашего сайта
* *style.css* - файл стилей, отвечающий за внешнее оформление

Кроме этого желательно сделать скриншот темы, сохранить с именем *screenshot.png* и положить в в корень папки новой темой.

Почти в каждой теме вы можете встретить следующие шаблоны:

Шаблон, файл функции или директория    | Описание
---                                    |---
*page.php*                             | Шаблон отдельной страницы
*single.php*                           | Шаблон отдельной статьи
*search.php*                           | Шаблон результатов поиска
*404.php*                              | Шаблон страницы 404
*header.php*                           | Хедер
*footer.php*                           | Футер
*sidebar.php*                          | Сайдбар
*searchform.php*                       | Шаблон формы поиска
*comment.php*                          | Шаблон вывода комментариев и форму добавление нового комментария
*functions.php*                        | Функции темы
*img/* , *js/*, *css/*, *fonts/*       | Директории которые могут хранить наборы изображений, скриптов, стилей или шрифтов
*screenshot.png* или *screenshot.jpg*  | Превью темы в разделе *Appereance*

Структура вашей темы может быть любой, например как у темы Twenty Seventeen:

```
assets/
    css/
    images/
    js/
inc/
languages/
template-parts/
    footer/
    header/
    navigation/
    page/
    post/
404.php
archive.php
comments.php
footer.php
front-page.php
functions.php
header.php
index.php
page.php
README.txt
rtl.css
screenshot.png
search.php
searchform.php
sidebar.php
single.php
style.css
```

Папка *languages/* как правило содержит *.pot* файлы, которые используются для перевода темы на другие языки.