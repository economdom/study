# Вывод Hello, World!

Для этого в файле контроллера создадим функцию и по правилам именования функций контроллера названия каждой функции должно начинаться со слова `action`:

*controllers/SiteController.php*

```php
public function actionHello(){
    return "Hello, World!";
}
```