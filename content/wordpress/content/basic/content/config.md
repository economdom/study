# Конфиг WordPress

Cодер­жит все настройки соединения с БД, включая имя БД, имя пользователя и пароль для доступа к БД, а также другие тонкие настройки.

Файл *wp-config. php* обычно хранится в корневой директории WordPress. Вы также можете переместить файл *wp-config* в родительскую директорию. WordPress сначала ищет файл *wp-config* в корневой директории, а потом, если не может найти его там, тогда в родительской. Это происходит автоматически, поэтому никаких изменений вносить не нужно. Такой подход делает практически невозможным доступ к данному файлу через браузер.

Некоторые параметры в WordPress хранятся  как константы, у всех констант одинаковый формат:

```
define('OPTION_NAME', 'value');
```

При добавлении новых параметров в файл *wp-config.php* важно помещать их выше следующей строки:

```
/* That's all, stop editing! Happy blogging. */
```

Безопасность WordPress можно усилить, установив в файле *wp-config.php* секрет­ные ключи (`Authentication Unique Keys and Salts`). Ключ безопасности — соль для хэширования, усложняющая взлом ва­шего сайта посредством добавления случайных элементов (соли) к установленному вами паролю. Эти ключи не требуются для функционирования WordPress, но они создают дополнительный слой безопасности на сайте. Для генерирования этих хешей можно использовать [https://api.wordpress.org/secret-key/1.1/salt/](https://api.wordpress.org/secret-key/1.1/salt/).

Вы можете добавлять или менять ключи в любое время. Единственное, что может произойти: текущие куки WordPress станут недействительным, и вашим пользо­вателям потребуется повторная авторизация.

Вы также можете определять префикс таблиц в БД, изменяя значение переменной `$table_prefix`:

```
$table_prefix  = 'wp_';
```

Таким образом если хакер хочет взломать ваш сайт, используя SQL-инъекцию, ему будет куда слож­нее выяснить имена таблиц. Выбор уникального префикса таблицы также делает воз­можным запуск нескольких копий WordPress в одной базе данных.

У  WordPress  есть  встроенная  способность для  использования  на различных языках. Значение переменной `WPLANG` определяет язык WordPress по умолчанию. Чтобы этот параметр работал, в *wp-content/languages* должен быть установлен соответствующий файл МО (машинный объект). Файлы МО представляют собой сжатые файлы РО (портативный объект), которые содержат перевод сообщений WordPress и текстовые строки на том или ином языке. Файлы МО и РО являются компонентами подсистемы GNU "gettext", которая лежит в основе мультиязычности WordPress.

Отладку ошибок в WordPress можно сделать проще, используя параметр `WP_DEBUG`, который отображает ошибки WordPress на экране,  вместо того чтобы замещать их белым экраном. Чтобы включить `WP_DEBUG`, установите его значение на `true`:

```
define('WP_DEBUG', true);
```