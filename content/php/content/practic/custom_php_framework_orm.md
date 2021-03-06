# Создание собственного фреймворка на PHP

## Структура и базовый роутинг

**Фреймворк** - это каркас для создания будущих приложения, то есть определённые заготовки кода, которые решают рутинные задачи и используются в большинстве случаев. Это упрощает и ускоряет процесс создания будущего приложения. При написании приложения с полного нуля нам приходится решать множество вопросов, например организация подключения к БД, маршрутизация, безопасность и т. д. Все это уже решено во фреймворке и нам остаётся только пользоваться этим, кроме того во фреймворке предоставляется множество уже готовых библиотек кода (отправка с почты, работа с формами, валидация данных).

Наш фреймворк будет реализововать патерт **MVC**, поэтому мы сразу же создадим внутри рабочей директории папку *app/* внутри которой ещё 3 папки *models/*, *views/*, *controllers/* для нашего будущего приложения.

* Контроллеры обрабатывают входящие HTTP запросы и выступает в качестве посредника между моделями и видами - здесь находится основная логика приложения
* Модели - работают с БД, например получают данные из БД и передают в контролер, а тот в свою очередь перенаправляет их в вид
* Виды - это файлы шаблонов, где находится HTML верстка со вставками PHP кода или шаблонизатора PHP

В папке *public/* - это папка в которой будет находится точка входа или же фронт-контроллер и все запросы, которые будут приходить с сайта будут изначально попадать в этот файл. В этой же папки будут находится все активы сайты, то есть изображения, файлы стилей и скрипты, поэтому можно сразу же внутри этой папки создать директории *img/*, *js/*, *css/*.

В папке *vendor/* будет находится ядро нашего фреймворка и дополнительные библиотеки. Файлы ядра будут находится в папке *core/*, а под библиотеки создадим папку *libs/*.

У нас получилась следующая структура:

```plain
app/
	controllers/
	models/
	views/
public/
	css/
	js/
	img/
vendor/
	core/
	libs/
```

Для примера добавим следующий кода в *index.php*

*public/index.php*

```php
<?php

echo __FILE__;
```

Теперь нужно сделать так чтобы при обращении к нашему домену, автоматически загружался этот файл, то есть точкой входа должна быть именно папка *public/*. Для этого мы создадим 2 файла *.htaccess*, первый в корне в котором мы включим модуль `rewrite` и напишем правило перенаправление, которое возьмёт все запросы и будет отправлять их в папку *public/*. Всё что мы запомнили (`(.*)`), теперь находится в переменной `$1`. Плюс мы можем сразу же указать кодировку для нашего сайта с помощью директивы `AddDefaultCharset`, чтобы в будущем не было никаких проблем с отображением русских символов.

*.htaccess*

```plain
AddDefaultCharset utf-8
RewriteEngine On
RewriteRule ^(.*)$ /public/$1
```

Теперь в папке *public/* мы создадим ещё один файл *.htaccess* и здесь мы напишем 2 условия `RewriteCond` в которых указываем что если это не файл или папка, тогда мы выполним следующее правило переадресации - запомним всё что будет находится в строке запроса и отправим это всё на файл *index.php* и в конце добавим наши данные, которые мы занесли в `$1`. Кроме этого в это же правило мы добавим ещё два флага `[L,QSA]`. `QSA` позволяет не перезаписывать строку запроса, а добавлять параметры в строке запроса к уже имеющимся.

*public/.htaccess*

```plain
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule (.*) index.php?$1 [L,QSA]
```

## Маршрутизатор

В реальной жизни, вы можете зайти в большой магазин и там имеется тысячи товаров с десятками отделов. Вы можете подойти к менеджеру и он вас может направить в нужный раздел или сообщить что необходимого вам товара у них нет.

**Маршрутизатор** - в PHP делает фактически то же самое, то есть к нему могут попадать разные запросы и он должен решить что с ними делать и куда перенаправить. URL адрес с параметрами разбивается на сегменты, то есть название сайта или домен, а всё остальное - это сегменты. В большинстве фреймворков, адрес вида `http://example.com/posts/new` имеет два сегмента, первый из которых это название контроллера `posts` (класс PHP), а второй его экшн/действие с именем `new` (метод класса). Если маршрутизатор находит данное правило, тогда выполняются указанные действия. Трейтий параметр - это как правило GET параметры, которые передаются нашему экшену.

Чтобы понимать какой именно адрес был запрошен, мы обратимся к суперглобальному массиву `$_SERVER['QUERY_STRING']` (также можно использовать `$_SERVER['REQUEST_URI']`.

*public/index.php*

```php
<?php

echo $query = $_SERVER['QUERY_STRING'];
```

И теперь если мы введём любой запрос (например `http://fw.loc/posts/view/10`, то он у нас будет выводится на экран.

Создадим файл *vendor/core/Router.php*, внутри которого напишем класс `Router`, в котором в конструкторе пока что будем просто выводить приветствие:

*vendor/core/Router.php*

```php
<?php

class Router{
	public function __construct(){
		echo "Привет, Мир!";
	}
}
```

Теперь подключим его в файле *public/index.php*. Пока что будем использовать фукцию `require()` для подключения нашего класса, а также нам нужно инициализировать наш класс `Router`. Далее будем напишем класс автолоадинга, который будет подключать данный файл автоматически. Также мы пока что не используем пространсва имён, а далее мы их добавим и будем использовать.

*public/index.php*

```php
<?php

echo $query = $_SERVER['QUERY_STRING'];

require '../vendor/core/Router.php';

$route = new Router();
```

Мы должны получить на экране приветствие `Привет, Мир!`.

Далее нам уже конструктор не понадобится. Все свойства и методы у нас будут статичными и для начала создадим два свойства `$routes` и `$route`, которые будут массивами.

* В `$routes` будет содержаться массив всех наших маршрутов, часто этот массив ещё называют таблицей маршрутов и со временем в этот маршрут можно ещё будет добавлять пользовательские маршруты.
* В `$route` будет находится единственный маршрут, который будет находится по текущему URL адресу. Благодаря этому свойству мы всегда будем знать какой именно экшн отрабатывает в текущий момент времени.
* Чтобы наполнять таблицу маршрутов мы создадим статичный метод `add()`, который будет принимать 2 параметры
	* 1-й это регулярное выражение `$regexp` в котором мы будем проверять URL адрес
	* 2-й это маршрут `$route`, который будет соответствовать заявленному URL адресу. Кроме этого этот параметр будет не объязательным, то есть пользователь может просто указать маршрут и если он не укажет дополнительно контроллер и экшн, то они будут вызваны по умолчанию для данного маршрута, согласно нашим внутренним соглашениям. Поскольку этот параметр не объязательный мы оставим его пустым массивом.
	* В нашем методе `add()` мы будем просто нашу таблицу роутов `$routes[]` при этом в качестве ключа у нас будет наш первый параметр `$regexp`, а в качестве значения второй параметр `$route`;

*vendor/core/Router.php*

```php
<?php

class Router{

	protected static $routes = [];
	protected static $route = [];

	public static function add($regexp, $route = []){
		self::$routes[$regexp] = $route;
	}

}
```

Метод `getRoutes()` для тестирования будет распечатывать нашу таблицу маршрутов, а `getRoute()` текущий маршрут:

*vendor/core/Router.php*

```php
public static function getRoutes(){
	return self::$routes;
}

public static function getRoute(){
	return self::$route;
}
```

Теперь мы можем создать первое правило и вывести его на экран:

*public/index.php*

```php
<?php

echo $query = $_SERVER['QUERY_STRING'];

require '../vendor/core/Router.php';

Router::add('posts/add', ['controller' => 'Posts', 'action' => 'add']);

print_r(Router::getRoutes());
```

Пока что распечатка массива не очень удобочитаемая, поэтому чтобы нам удобно было распечатывать объекты и массивы мы создадим отдельную функцию `debug()`.

*vendor/libs/functions.php*

```php
<?php

function debug($arr){
	echo "<pre>" . print_r($arr, true) . "</pre>";
}
```

Не забываем подключить этот файл и вызовем нашу функцию для проверки:

*public/index.php*

```php
<?php

echo $query = $_SERVER['QUERY_STRING'];

require '../vendor/core/Router.php';
require '../vendor/libs/functions.php';

Router::add('posts/add', ['controller' => 'Posts', 'action' => 'add']);
Router::add('posts/', ['controller' => 'Posts', 'action' => 'index']);
Router::add('', ['controller' => 'Main', 'action' => 'index']);

debug(Router::getRoutes());
```

Теперь нам нужно обрабатывать то что пользователь у нас запросит. Напомню, что в переменной `$query` сохраняется то что будет запрашивать пользователь и который мы будем сравнивать с нашим ключём.

Пока что мы просто будем сравнивать, а потом уже будем использовать возможности регулярных выражений и для этого мы создадим ещё один метод `matchRoute()` в качестве агрумента эта функция будет принимать запрос, который будет запрашивать пользователь (`$query`) а внутри функции мы будем перебирать в цикле таблицу маршрутов `$routes` где мы будем получать ключ и значение, то есть мы будем брать регулярное выражение и наш маршрут. Дальше мы сравниваем наш паттер с URL, который запросил пользователь (в дальшейшем мы будем использовать функцию `preg_match()` чтобы сделать наше сравнение более гибким). В итоге если мы находим совпадение, тогда заносим текущий запрос в переменную `$route` нашего класса `Router` и возвращаем `true`. Если же мы не находим совпадение, то возвращаем `false`.

*vendor/core/Router.php*

```php
public static function matchRoute($url){
	foreach(self::$routes as $pattern => $route){
		if($url == $pattern){
			self::$route = $route;
			return true;
		}
	}
	return false;
}
```

Если для маршрута `posts/` мы уберём слеш, то вернутся `404` и чтобы пока решить этот вопрос мы будем использовать функцию `rtrim()`.

*public/index.php*

```php
<?php

$query = rtrim($_SERVER['QUERY_STRING'], '/');

require '../vendor/core/Router.php';
require '../vendor/libs/functions.php';

Router::add('posts/add', ['controller' => 'Posts', 'action' => 'add']);
Router::add('posts', ['controller' => 'Posts', 'action' => 'index']);
Router::add('', ['controller' => 'Main', 'action' => 'index']);

debug(Router::getRoutes());

if(Router::matchRoute($query)){
	debug(Router::getRoute());
}
else{
	echo '404';
}
```

Далее мы делаем проверку, если запрашиваемый маршрут найдет, тогда мы его возвращаем, а если нет, тогда возвращаем пока что `404`. Если совпадение найдено, то теперь мы знаем какой нужно подключить контроллер и какой экш у него нужно вызвать - нам теперь нужно создать объект этого контроллера и вызвать его метод.

## Дорабатываем класс Router

Пока что у нас наш роутинг может обрабатывать статичные URL запросы и если у нас на сайте будет 100 страниц, то нужно будет написать 100 правил роутинга и таким образом наш файл с роутами будет расти с увеличением к-ва страниц. Поэтому нам нужно придумать гибкие правила, которые будут обслуживать практически всю систему маршрутизации фреймворка.

* Валидация пустой строки - в этом случае должен отработан контроллер и определённое действие по умолчанию
* Валидация всех остальных URL - это правило должно обрабатывать остальные сегменты URL - контроллер, экшн, GET параметры

Гибкость роутинга достигается при помощи регулярных выражений.

Пустая строка в регулярных выражениях у нас помечается как `^$'`, где `^` начало строки, а `$` конец строки.

*public/index.php*

```php
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
```

Контроллер и экш по умолчанию можно будет изменить на те которые нужны вам.

Для остальных URL для контроллера мы допускаем все символы латиницы + тире (`[a-z-]`) и для экшена тоже самое. Посколько нам нужно сохранять наши роутеры в таблицу роутеров, то нам нужно их как-то отлавливать и для этого мы будем использовать групирующие скобки, которые как раз и будут запоминать наши сегменты.

*public/index.php*

```php
Router::add('^([a-z-]+)/([a-z-]+)$');
```

Контроллер и экшн нам уже здесь не нужны поскольку мы будем их брать то что мы заполнили в первом и втором совпадении.

В методе `matchRoute()` мы пока что просто сравниваем статичную информацию - паттерн и URL. Теперь же мы будем использовать для этого регулярное выражение, поэтому будем применять функцию `preg_match()`, которой на вход передаём наш паттерн, URL адресс, который хотим проверить и третим параметром будем передавать `$matches` в которой будут записываться наши сегменты, которые мы в дальнейшем будем использовать.

*vendor/core/Router.php*

```php
public static function matchRoute($url){
	foreach(self::$routes as $pattern => $route){
		if(preg_match("#$pattern#i", $url, $matches)){
			debug($matches);
			self::$route = $route;
			return true;
		}
	}
	return false;
}
```

При обращении к такому URL - `posts/add`, мы получаем следующий вывод

```plain
Array
(
	[0] => posts/add
	[1] => posts
	[2] => add
)
Array
(
)
```

То что мы получили это массив возвращаемый `debug($matches)` и теперь осталось эти сегменты. В нулевой элемент у нас записывается полностью строка, с которой было найденно совпадение, а в 1-й и 2-й элементы записались первый и второй сегменты. Чтобы вместо целочисленного массива у нас у нас были более осмысленные и понятные ключи мы можем использовать возможность именнования захваченных групп в регулярных выражениях:

*public/index.php*

```php
Router::add('^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)$');
```

Теперь мы можем сделать экш `index` необъязательным. Для этого мы просто будем использовать знак вопроса в конце слеша и групы шаблона с экшеном.

*public/index.php*

```php
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
```

Со временем мы сделаем, чтобы если не был указан экшн, автоматом подставлялся `index`.

Дальше если мы найдём совпадение с определённым контроллером и экшеном, тогда нам нужно создать объект найденного контроллера, если такой есть и вызвать его метод, если таковой присутствует. И для этих целей мы создадим новый метод, который очень часто в других фреймворках называют `dispatch()`, который на вход будет принимать тот же самый URL. Внутри мы проверяем если наш метод `matchRoute()` возвращает `true`, тогда выведем `Ok`, иначе возвращаем код ошибки и подгружаем наш шаблон 404 ошибки.

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		echo 'Ok';
	}
	else{
		http_response_code(404);
		require '404.html';
	}
}
```

Создадим файл шаблона 404:

*public/404.html*

```html
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404 not found</title>
</head>
<body>
	<h1>Page not found</h1>
</body>
</html>
```

Вызываем метод `dispatch()`:

*public/index.php*

```php
Router::dispatch($query);
```

Теперь можно протестировать, если введём допустимый адрес, то должно вернуть `Ok`, а если нет, например добавим в имени контроллера или экшена цифру (это не допустимо в нашем регулярном выражении), то шаблон и код ошибки в консоли браузера.

Теперь в текущий роут нам нужно положить контроллер и экш, для этого мы пройдёмся по нашему массиву `matches` и возмёт значения только тех ключей, которые являются строками:

*vendor/core/Router.php*

```php
public static function matchRoute($url){
	foreach(self::$routes as $pattern => $route){
		if(preg_match("#$pattern#i", $url, $matches)){
			debug($matches);
			foreach($matches as $k => $v){
				if(is_string($k)){
					$route[$k] = $v;
				}
			}
			self::$route = $route;
			debug($route);
			return true;
		}
	}
	return false;
}
```

Теперь давайте сделаем, чтобы если экшн у нас не указан, то мы будем использовать по умолчанию экш `index` для этого мы напишем ещё одну проверку, что если у нас не существует ключ `action` в массиве `$route[]`, то мы установим это значение самостоятельно в `index`.

*vendor/core/Router.php*

```php
public static function matchRoute($url){
	foreach(self::$routes as $pattern => $route){
		if(preg_match("#$pattern#i", $url, $matches)){
			debug($matches);
			foreach($matches as $k => $v){
				if(is_string($k)){
					$route[$k] = $v;
				}
			}
			if(!isset($route['action'])){
				$route['action'] = 'index';
			}
			self::$route = $route;
			debug($route);
			return true;
		}
	}
	return false;
}
```

В методе `dispatch()` у нас доступен массив `$route[]`:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		echo 'Ok';
		debug(self::$route);
	}
	else{
		http_response_code(404);
		require '404.html';
	}
}
```

Создадим переменную `$controller` и поместим в эту переменную вызванный котроллер, после чего сделаем проверку если запрошенный контроллер существует, используя функцию `class_exists()`, то выведем `Ok`, если же нет, сообщим что такого контроллера нет.

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$controller = self::$route['controller'];
		if(class_exists($controller)){
			echo 'Ok';
		}
		else{
			echo "Sorry, but controller <b>$controller</b> not found!";
		}
	}
	else{
		http_response_code(404);
		require '404.html';
	}
}
```

Создадим и подключим пока что вручную наши классы, а позже реализуем класс автозагрузки.

*app/controllers/Main.php*

```php
<?php

class Main{

}
```

*app/controllers/Posts.php*

```php
<?php

class Posts{

}
```

*public/index.php*

```php
require '../app/controllers/Main.php';
require '../app/controllers/Posts.php';
```

Тестируем, чтобы убедится что если обращаемся к существующим контроллерам.

Название контроллеров в URL у нас в нижнем регистре, при этом если название контроллера составное, то два слова разделяются дефисом. При этом названия классов в PHP/PSR принято использовать вариант **StudlyCaps**, то есть каждое слово начинается с большой буквы. OS Windows регистронезависимая и пробемы будут видны не сразу, а с Mac/Linux/Unix всё иначе, поэтому нам нужно конвертировать небходимый формат URL в требуемый формат именования классов PHP. Для решения этого вопроса нужно создать ещё одну функцию.

*vendor/core/Router.php*

```php
protected static function upperCamelCase($name){
	$name = str_replace('-', ' ', $name);
	$name = ucwords($name);
	$name = str_replace(' ', '', $name);
	return $name;
}
```

Проверяем всё ли работает, для этого создаём новый контроллер *app/controllers/PostsNew.php* и вызываем данный контроллер чере адресную строку через `posts-new`, если мы получим `Ok` значит всё гуд.

Теперь осталось эту функцию применить к нашему названию контроллера:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$controller = self::upperCamelCase(self::$route['controller']);
		//..
```

Ну что ж класс у нас есть, теперь осталось создать объект данного класса и запустить необходимый экш, если он существует, а если его нет, тогда нужно вывести соответствующее сообщение:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$controller = self::upperCamelCase(self::$route['controller']);
		if(class_exists($controller)){
			$newObject = new $controller;
			$action = self::$route['action'];
			if(method_exists($newObject, $action)){
				$newObject->$action();
			}
			else{
				echo "Sorry, but action <b>$action</b> not found!";
			}
		}
		else{
			echo "Sorry, but controller <b>$controller</b> not found!";
		}
	}
	else{
		http_response_code(404);
		require '404.html';
	}
}
```

В наш класс `PostsNew` можно поместить такой код для проверки результата:

*app/controllers/PostsNew.php*

```php
<?php

class PostsNew{

	public function __construct(){
		echo "PostsNew::__construct";
	}

	public function index(){
		echo "Index method";
	}

}
```

Такой вариант у нас работает, теперь нужно применить правила именования уже для наших экшенов, где согласно правил PSR нужно чтобы составное название экшена было в **camelCase** нотации. Для этого создадим ещё один метод:

*vendor/core/Router.php*

```php
protected static function lowerCamelCase($name){
	return lcfirst(self::upperCamelCase($name));
}
```

И не забываем его использовать данный метод:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$controller = self::upperCamelCase(self::$route['controller']);
		if(class_exists($controller)){
			$newObject = new $controller;
			$action = self::lowerCamelCase(self::$route['action']);
			//..
```

Так как часть методов в классе могут быть служебными, при этом чтобы каждый метод, который возможно будет вызвать через адресную строку имел постфикс `Action`, поэтому в роуте мы просто конкатенируем эту строку:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$action = self::lowerCamelCase(self::$route['action']) . 'Action';
		if(class_exists($controller)){
			$newObject = new $controller;
			$action = self::lowerCamelCase(self::$route['action']);
			//..
```

Теперь мы можем вызывать только те методы у которых есть постфикс `Action` - убедитесь что это работает в вашем случае:

*app/controllers/PostsNew.php*

```php
<?php

class PostsNew{

	public function indexAction(){
		echo "Index method";
	}

	public function testNewAction(){
		echo "testNew method";
	}

	public function before(){
		echo "before method";
	}

}
```

Теперь давайте реализуем функцию автозагрузки

Заведём несколько констант, которые будут заканчиваться без слеша в конце и для этого нам помогут магическая константа `__DIR__`, которая будет возвращать нам текущую директорию и функцию `dirname()`, которая будет указывать на родительскую директорию:

* `WWW` будет указывать на текущую папку *public/*
* `CORE` будет указывать на папку с нашим ядром *vendor/core/*
* `ROOT` будет вести в кореневую директорию */*
* `APP` будет вести в папку *app/*

*public/index.php*

```php
define('WWW', __DIR__);
define('CORE', dirname(__DIR__) . '/vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
```

Автозагрузку мы будем реализововать с помощью функции `spl_autoload_register()` которой на вход мы будем передавать анонимную функцию, которой на вход передадим название нашего файла. Внутри мы сформируем путь к нашему файлу и проверим, если такой файл существует, то мы его подключим.

*public/index.php*

```php
spl_autoload_register(function($class){
	$file = APP . "/controllers/$class.php";
	if(is_file($file)){
		require_once $file;
	}
});
```

Теперь осталось закоментировать подключение наших контроллеров, которые мы делали ранее с помощью `require()` и проверить загружаются ли автоматически наши контроллеры, когда мы вызываем их через адресную строку.

Теперь напишем наше собственное правило роутинга, но при этом это правило должно быть выше дефолных, чтобы иметь больший приоритет

*public/index.php*

```php
Router::add('^pages/?(?P<action>[a-z-]+)?$', ['controller' => 'Posts']);

// Default routes
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
```

## Автозагрузка

На данный момент мы пока что жёстко загружаем контроллеры приложения с папки *app/controllers/*, однако у нас нас есть множество других классов, при этом они нашим автозагрузчиком пока что не загружаются. Чтобы не писать цепочку функций автозагрузки, мы поступим по другому.

Если мы закоментируем подключение класса `Router` и выведем с помощью функции `debug()` наш класс в функции автозагрузке:

*public/index.php*

```php
spl_autoload_register(function($class){
	debug($class);
	$file = APP . "/controllers/$class.php";
	if(is_file($file)){
		require_once $file;
	}
});
```

То мы увидим наш класс `Router`. И теперь мы плавно переходим к понятию пространства имён.

**Пространство имён** - это путь к классу начиная от корня нашего приложения, поэтому мы можем так и прописать внутри класса `Router`:

*vendor/core/Router.php*

```php
<?php

namespace vendor\core;
//..
```

Теперь если мы обратимся к нашему классу `Router` используя пространство имён, то в `debug($class)` у нас уже попадёт другое значение.

*public/index.php*

```php
//..
vendor\core\Router::add('^pages/?(?P<action>[a-z-]+)?$', ['controller' => 'Posts']);
//..
```

Это нам даёт фактически полный путь к классу, который мы хотим подключить и нам остаётся дописать в конце расширение файла *.php* и заменить обратные слэши на прямые. Если OS Windows понимает и обратные и прямые слеши, то Linux только прямые, поэтому если нам нужно чтобы наш код был кроссплатформенный, то нам их нужно заменить.

Искать файлы мы будем от корня, поэтому будем использовать константу `ROOT`, затем конкатенируем прямой слеш, потому что все константы у нас не используют слеш в конце, затем с помощью функции `str_replace()` заменяем все обратные слэши на прямые и в конце добавляем имя класса и раширение файла. Чтобы каждый раз не писать полный путь к файлу класса `vendor\core\Router` мы можем в самом начале этот путь импортировать с помощью ключевого слова `use`. Также давайте будем выводить все ошибки, для этого в самом начале файла используем функцию `error_reporting()`.

*public/index.php*

```php
<?php

error_reporting(-1);

use vendor\core\Router;

//..

spl_autoload_register(function($class){
	debug($class);
	$file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
	//..
```

Класс `Router` у нас успешно подключён, но теперь есть проблема с классами контроллеров и для того чтобы исправить это нужно просто добавить пространство имён для наших контроллеров в нашем классе `Router`:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		$controller = 'app\controllers\\' . self::upperCamelCase(self::$route['controller']);
		//..
```

А также нужно добавить пространство имён внутри уже созданных контроллеров:

*app/controllers/Main.php*

```php
<?php

namespace app\controllers;
//..
```

Пространство имён нужно добавлять для всех пользовательских контроллеров.

Мы уже можем вызывать определённый контроллер и его экшен в зависимости от запрошенного URL и теперь пришло время выводить какие-то данные в шаблонах и видах.

**Шаблон** - это повторяющиеся части на сайте, например хедер, футер, навигация и т. д.

**Вид** - это контент страницы, который будет изменяться от страницы к странице.

Мы будем наследовать принципы шаблонизации CakePHP, в котором если шаблон не указан, то будет подключаться дефолтный шаблон, таким образом нам не нужно будет каждый раз вызывать вручную, если этого не требуется. При этом если вас не устраивает дефолный шаблон, то у вас будет возможность это переопределить. При этом мы сможем указывать отдельный шаблон или вид не только для отдельной страницы, но и для группы страниц, то есть для всего контроллера.

Удобно под каждый контроллер создавать в папке *app/controllers* отдельную папку - это связанно с тем что для одного контроллера могут использоваться несколько видов, при этом их часто можно называть одинаково, поэтому чтобы не было путаницы желательно раскладывать виды по отдельным папкам.

* Папки будут иметь такое же название как и имя контроллера
* Файлы видов будут иметь такое же название как и название экшенов в контроллере

Как указать контроллеру какой именно вид нужно подгружать из какой папки - на самом деле эта информация уже для нас доступна в переменной `$route`

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		debug( self::$route );
		//..
```

Теперь при обращении по адресу `http://fw.loc/posts/test/` мы должны получить следующий массив:

```
Array
(
    [controller] => posts
    [action] => test
)
```

При этом нам нужно чтобы название файла начиналось с большой буквы и каждое следующее также было с большой для этого нам нужно вызвать функцию `upperCamelCase()` внутри `foreach` функции `matchRoute()`.

*vendor/core/Router.php*

```php
public static function matchRoute($url){
	foreach(self::$routes as $pattern => $route){
		if(preg_match("#$pattern#i", $url, $matches)){
			foreach($matches as $k => $v){
				if(is_string($k)){
					$route[$k] = $v;
				}
			}
			if(!isset($route['action'])){
				$route['action'] = 'index';
			}
			$route['controller'] = self::upperCamelCase($route['controller']);
			self::$route = $route;
			return true;
		}
	}
	return false;
}
```

Мы теперь можем не использовать функцию `upperCamelCase()` внутри `dispatch()`:

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		debug(self::$route);
		$controller = 'app\controllers\\' . self::$route['controller'];
		//..
```

Теперь осталось передать полученную информацию (папка = название контроллера и вид = имя экшена) в наш контроллер. При создании объекта контроллера мы можем передать ему в этот момент параметры текущего роута.

*vendor/core/Router.php*

```php
public static function dispatch($url){
	if(self::matchRoute($url)){
		debug(self::$route);
		$controller = 'app\controllers\\' . self::$route['controller'];
		if(class_exists($controller)){
			$newObject = new $controller(self::$route);
			//..
```

Теперь мы можем получить эти данные в нужном нам контролле и для теста давайте сделаем это (этих данных не должно быть в контролле и позже мы это поправим).

* Будет свойство `$route`, которое у нас будет массивом
* Также мы определим метод `__construct()`, в котором мы будем обращаться к нашему свойству `$route` и заполнять его тем что приходит в наш контруктор параметром при создании объекта.
* Теперь в параметре `$this->route` содержится вся необходимая нам информация

```php
<?php

namespace app\controllers;

class PostsNew{

	public $route = [];

	public function __construct($route){
		$this->route = $route;
	}

	public function indexAction(){
		debug($this->route);
		echo "Index method";
	}
	//..
```

Чтобы не повторять эти строки кода для каждого класса контроллера, а дублирование кода это не правильно, поэтому нам нужно создать некоторый базовый класс контроллера, который будут наследовать остальные контроллеры и который будет находится не в приложении а в ядре, то есть в папке *vendor/core*.