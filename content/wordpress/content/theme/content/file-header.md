# Хедер шаблона

При открытии новой темы в админке, мы увидим что там нет никакой информации о теме, хотя если открыть какую-либо из стандартных тем, то мы увидим, что там очень много разной информации о теме.

В файле *style.css* необходимо указать мета информацию внутри комментариев. Эта информация позволит вывести дополнительную информацию о теме в разделе *Appearance* админ-панели.

Формат комментариев должен быть следующим:

*style.css*

```
/*
Theme Name: WordPress Theme
Theme URI: https://github.com/kamuz/wptheme
Author: Vladimir Kamuz
Author URI: https://wpdev.pp.ua/
Description: Starter Theme based on Twitter Bootstrap
Version: 1.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: kmz-wptheme
Tags: bootstrap, starter
*/
```

Чтобы добавить тему в резиторий WordPress нужно чтобы были заполненны Theme Name, Author, Description, Version, License, License URL, Text Domain

* **Theme Name** - название темы
* **Theme URI** - URL веб-страницы, где пользователи смогут найти больше информации о теме
* **Author** - имя автора темы, рекомендуется использовать имя пользователя с [https://wordpress.org](https://wordpress.org)
* **Author URI** - URL веб-странички автора темы
* **Description** - короткое описание темы
* **Version** - версия темы в формате X.X или X.X.X
* **License** - лицензия темы
* **License URI** - URL лицензии
* **Text Domain** - строка использующаяся `textdomain` для перевода
* **Tags** - слова или фразы, которые позволяют пользователям находить тему используя фильтр тегов
* **Domain Path** - используется чтобы WordPress знал где искать перевод, когда тема не активна (по умолчанию `/languages`)