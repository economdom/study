# Метаданные шаблона

В файле *style.css* необходимо указать мета информацию внутри комментариев. Эта информация позволит вывести дополнительную информацию о теме в разделе *Appearance* админ-панели.

Формат комментариев должен быть следующим:

*style.css*

```css
/*
Theme Name: Twenty Seventeen
Theme URI: https://wordpress.org/themes/twentyseventeen/
Author: the WordPress team
Author URI: https://wordpress.org/
Description: Twenty Seventeen brings your site to life with immersive featured images and subtle animations. With a focus on business sites, it features multiple sections on the front page as well as widgets, navigation and social menus, a logo, and more. Personalize its asymmetrical grid with a custom color scheme and showcase your multimedia content with post formats. Our default theme for 2017 works great in many languages, for any abilities, and on any device.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: twentyseventeen
Tags: one-column, two-columns, right-sidebar, flexible-header, accessibility-ready, custom-colors, custom-header, custom-menu, custom-logo, editor-style, featured-images, footer-widgets, post-formats, rtl-language-support, sticky-post, theme-options, threaded-comments, translation-ready
This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned with others.
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