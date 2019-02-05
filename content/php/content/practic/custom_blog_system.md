# Простое MVC приложение на PHP

## Начало работы и схема базы данных

Создавать мы его будем на базе созданного ранее фреймворка. Для начала создадим новую базу данных, изменить параметры подключения к БД в конфиге и создадим две таблицы в БД:

```sql
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Базовая настройка

В начале почистим контроллер:

*app/controllers/Pages.php*

```php
<?php

class Pages extends Controller{
    public function __construct(){
    }

    public function index(){
        $data = [
            'title' => 'Simple MVC',
            'description' => 'Simple social network built on the Custom PHP framework'
        ];

        $this->view("pages/index", $data);
    }

    public function about(){
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];

        $this->view("pages/about", $data);
    }
}
```

В конфиг добавим ещё одну константу, которая будет выводить версию нашего приложения:

*app/config/config.php*

```php
<?php

// DB params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'simplemvc');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root
define('URLROOT', 'http://simplemvc.loc');
// Site Name
define('SITENAME', 'Simple MVC');
// App Version
define('APPVERSION', '1.0.0');
```

Мы будем использовать Bootstrap и Font Awesome, поэтому подключим их, а также добавим подключение отдельного шаблона с навигацией:

*app/views/inc/header.php*

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URLROOT ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URLROOT ?>/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT ?>/css/style.css">
    <title>
        <?php echo SITENAME; ?>
    </title>
</head>

<body>
    <?php require APPROOT . '/views/inc/navbar.php'; ?>
    <div class="container">
```

Теперь добавим разметку для навигации:

*app/views/inc/navbar.php*

```php
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
                </li>
            </ul>
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

В футере подключим необходимые скрипты:

*app/views/inc/footer.php*

```php
    </div>

<script src="<?php echo URLROOT ?>/js/jquery.min.js"></script>
<script src="<?php echo URLROOT ?>/js/popper.min.js"></script>
<script src="<?php echo URLROOT ?>/js/bootstrap.min.js"></script>
<script src="<?php echo URLROOT ?>/js/main.js"></script>
</body>
</html>
```

Осталось поправить виды:

*app/views/pages/index.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-flud text-center">
        <div class="container">
        <h1 class="display-3"><?php echo $data['title']; ?></h1>
        <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div> 
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

*app/views/pages/about.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-flud text-center">
        <div class="container">
        <h1 class="display-3"><?php echo $data['title']; ?></h1>
        <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div> 
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Создание контроллера Users

Создадим контроллер, в котором мы пока что просто проверим, если данные не отправляются методом POST, тогда просто выводим форму (пока что просто сообщение).

*app/controllers/Users.php*

```php
<?php

class Users extends Controller{
    public function __construct(){

    }

    public function index(){
        echo 'User index';
    }

    public function register(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process the form
        }
        else{
            // Load form
            echo 'User register form';
        }
    }
}
```

По клику на ссылку регистрации в меню мы должны обращатся к нашему контроллеру и методу `register()`. Теперь давайте инициализируем данные и подгрузим вид:

*app/controllers/Users.php*

```php
<?php

class Users extends Controller{
    public function __construct(){

    }

    public function index(){
        echo 'User index';
    }

    public function register(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process the form
        }
        else{
            // Load form
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('users/register', $data);
        }
    }
}
```

Создадим вид:

*app/views/users/register.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create An Account</h2>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Виды для формы регистрации и авторизации

По сути это просто разметка Bootstrap и единственное, что здесь может вызвать вопросы это конструкция `<?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>`, где мы с помощью тернарного оператора присваиваем класс `is-invalid` для инпута, когда в массив попадает значение с ошибкой. С валидацией формы мы поработаем чуть позже и всё станей яснее:

*app/views/users/register.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create An Account</h2>
                <p>Please fill out this form to register with us</p>
                <form action="<?php echo URLROOT; ?>/users/register" method="POST">
                    <div class="form-group">
                        <label for="name">Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control form-control-lg <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name'] ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email'] ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup>*</sup></label>
                        <input type="password" name="password" class="form-control form-control-lg <?php echo !empty($data['password_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo !empty($data['confirm_password_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err'] ?></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Register" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT ?>/users/login" class="btn btn-light btn-block">Have an account? Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

Добавим метод `login()` в контроллер `User`:

*app/controllers/Users.php*

```php
public function login(){
    // Check for POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process the form
    }
    else{
        // Load form
        $data = [
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => '',
        ];
        $this->view('users/login', $data);
    }
}
```

Вид, очень поход на предыдущий - мы просто убрали лишнее:

*app/views/users/login.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Login</h2>
                <p>Please fill in your credentials to log in</p>
                <form action="<?php echo URLROOT; ?>/users/login" method="POST">
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email'] ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup>*</sup></label>
                        <input type="password" name="password" class="form-control form-control-lg <?php echo !empty($data['password_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err'] ?></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Login" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT ?>/users/register" class="btn btn-light btn-block">No account? Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Валидация формы

Во время отправки и обработки данных нам нужно чтобы данные были заполненны, а поля с ошибками оставались пустыми. Мы проверяем что именно вводит пользователь и если это не соответствует нашим требованиям, то мы заполняем массив ошибками и затем выводим их в вид. Не забываем предварительно фильтровать данные.

*app/controllers/Users.php*

```php
<?php

class Users extends Controller{
    public function __construct(){

    }

    public function index(){
        echo 'User index';
    }

    public function register(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Process the form
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }
            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }
            else if(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            // Validate Confirm Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            }
            else if($data['password'] != $data['confirm_password']){
                $data['confirm_password_err'] = 'Password do not match';
            }
            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                die('VALIDATED');
            }
            else{
                // Load view with errors
                $this->view('users/register', $data);
            }
        }
        else{
            // Load form
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('users/register', $data);
        }
    }

    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Process the form
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];
            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }
            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }
            else if(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                die('VALIDATED');
            }
            else{
                // Load view with errors
                $this->view('users/login', $data);
            }
        }
        else{
            // Load form
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data);
        }
    }
}
```

## Модель User и проверка email

Во время регистрации нам нужно проверять не существует ли уже пользователь с указанным email. Чтобы это проверть нам нужно создать предварительно тестовые данные:

```sql
INSERT INTO `users` VALUES ('1', 'Jonh Doe', 'j.doe@gmail.com', '123456', '2018-05-04 12:16:35');
INSERT INTO `users` VALUES ('2', 'Vladimir Kamuz', 'v.kamuz@gmail.com', '123456', '2018-05-04 12:16:30');
```

Тепер создадим модель `User`:

*app/models/User.php*

```php
<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT email FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
```

Здесь мы описываем функцию `findUserByEmail()` в котором проверим переданный email с тем если такой же в БД и если такой email уже имеется в БД, то есть мы находим совпадение в БД, то мы создадим новое сообщение об ошибке валидации:

*app/controllers/Users.php*

```php
//..
// Validate Email
if(empty($data['email'])){
    $data['email_err'] = 'Please enter email';
}
else if($this->userModel->findUserByEmail($data['email'])){
    $data['email_err'] = 'Email is already taken';
}
```

## Регистрация пользователя

Перед тем как двигаться дальше, давайте вначале захешируем наш пароль, а затем в случае успешной записи в БД  сделаем редирект на страницу логина с помощью собственной функции, которую мы определим в хелпере.

*app/controllers/Users.php*

```php
    // Validated

    // Hash Password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Register users
    if($this->userModel->register($data)){
        redirect('/users/login');
    }
    else{
        die('Something went wrong');
    }
}
```

За запись пользователя в БД отвечает метод `register()`, которую нам нужно определить в нашей модели:

*app/models/User.php*

```php
// Register user
public function register($data){
    $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['password']);

    if($this->db->execute()){
        return true;
    }
    else{
        return false;
    }
}
```

Здесь мы просто добавляем запись в базу данных и если запись успешно добавилась в БД, то мы возвращаем `true`, а если произошли какие-то сбои или ошибки, тогда `false`.

В файле будстрапа добавим вызов нашего хелпера:

*app/bootstrap.php*

```php
<?php

// Load Config
require_once "config/config.php";

// Load Helpers
require_once 'helpers/url_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
});
```

В хелпере мы создадим собственную функцию `redirect()`, которая будет использовать функцию `header()` чтобы сделать редирект:

*app/helpers/url_helper.php*

```php
<?php

// Simple page redirect
function redirect($page){
    header('location: ' . URLROOT . $page);
}
```

## Хелпер для флеш сообщений

Нам нужно создать функцию, которая будет при первом вызове в контроллере записывать данные в сессию, а при повторном вызове в виде, при условии что передан только первый параметр выводить флеш сообщение и удалять сессионные переменные, чтобы при повторном загрузке этой же страницы сообщение у нас уже не отображалось:

*app/helpers/session_helper.php*

```php
<?php

// $_SESSION['user'] = 'Brad';
session_start();

// Flash message helper
// EXAMPLE - flash('register_success', 'You are now register', 'alert alert-danger');
// DISPLAY IN VIEW - echo flash('register_success')
function flash($name='', $message='', $class='alert alert-success'){
    if(!empty($name)){
        // Execute on the Controller
        if(!empty($message) && empty($_SESSION[$name])){
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        }
        // Execute on the View
        else if(empty($message) && !empty($_SESSION[$name])){
            echo '<div class="' . $_SESSION[$name . '_class'] . '">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}
```


Не забываем подключить этой хелпер в файле бутстрапа:

*app/bootstrap.php*

```php
// Load Helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';
```

Теперь мы в контроллере вызываем этот метод с двумя или тремя (если хотим изменить стиль алерта) параметрами после записи в БД:

*app/controllers/Users.php*

```php
// Register users
if($this->userModel->register($data)){
    flash('register_success', 'You are registered and can log in');
    redirect('/users/login');
}
```

А в виде, куда мы редиректим пользователя, только с первым - это по сути ID сессионной переменной.

*app/views/users/login.php*

```php
<div class="card card-body bg-light mt-5">
    <?php flash('register_success') ?>
    <h2>Login</h2>
    <p>Please fill in your credentials to log in</p>
```

## Авторизация пользователя

В начале проверим существует ли наш пользователь в БД, иначе создадим сообщение для ошибки валидации email.

*app/controllers/Users.php*

```php
public function login(){
    // Check for POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //..
        // Validate Email
        if(empty($data['email'])){
            $data['email_err'] = 'Please enter email';
        }
        if($this->userModel->findUserByEmail($data['email'])){
            // User found
        }
        else{
            // User not found
            $data['email_err'] = 'No user found';
        }
```

Далее мы вызываем метод `login()`, которому мы передаём логин и пароль из формы и если логин и пароль соответствуют тем, которые имеются в БД, то мы пока что завершаем выполнение скрипта и выводим сообщени, в противном случае передаём в вид ошибку валидации о том что введённый пароль не корректный:

*app/controllers/Users.php*

```php
// Make sure errors are empty
if(empty($data['email_err']) && empty($data['password_err'])){
    // Validated
    // Check and set logged in user
    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
    if($loggedInUser){
        // Create Session
        die("LOGIN IN");
    }
    else{
        $data['password_err'] = 'Password incorrect';
        $this->view('users/login', $data);
    }
}
```

В модели соaдаём метод `login()` в котором мы получаем email польbователя, который соотвествует введённому в форму и с помощью функции `password_verify()` проверяем соответствует ли cахешированный пароль с тем, который мы ввели череd форму:

*app/models/User.php*

```php
// Login user
public function login($email, $password){
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();
    $hashed_password = $row->password;

    if(password_verify($password, $hashed_password)){
        return $row;
    }
    else{
        return false;
    }
}
```

## Записываем данные о пользователе в сессию

Пока что мы просто выводили сообщение, если в БД нашли совпадение email и паролю, введённому в форму авторизации. Теперь нам нужно записать эти данные в сессию и для этого мы напишем и вызовем метод `createUserSession()` на вход которой передадим данные о залогиненном пользователе, которые мы получили из БД. В методе `logout()` мы удаляем сессионные переменные и завершаем сессию. Метод `isLoggedIn()` нужен для того чтобы проверяеть залогиненный ли пользователь, что нам приходится в будущем, когда мы будет реализовововать уровни доступа:

*app/controllers/Users.php*

```php
$loggedInUser = $this->userModel->login($data['email'], $data['password']);
if($loggedInUser){
    // Create Session
    $this->createUserSession($loggedInUser);
}

//..

public function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    // debug($_SESSION);
}

public function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    redirect('/posts/index');
}

public function logout(){
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('/users/login');
}
```

В хелпер сессий мы добавим новую функцию, которая будет просто проверять залогиненный ли пользователь, а именно существует ли сессионная переменная с ID пользователя:

*app/helpers/session_helper.php*

```php
// Check User logined in
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    else{
        return false;
    }
}
```

Теперь нам нужно подкорректировать нашу навигацию, где мы проверим если, тогда выводим ссылку метод `logout()` нашего контроллера `Users`:

*app/views/navbar.php*

```php
<ul class="navbar-nav ml-auto">
<?php if(isLoggedIn()): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
    </li>
<?php endif; ?>
</ul>
```

## Контроллер Posts

Создадим новый контроллер и метод `index()`:

*app/controllers/Posts.php*

```php
<?php

class Posts extends Controller{
    public function index(){
        $data = [];

        $this->view('posts/index');
    }
}
```

Создадим вид:

*app/views/posts/index.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT ?>/posts/add" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i>Add Post</a>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Контроль доступа

В нашем случае доступ к постам будет только у залогиненных пользователей, поэтому:

*app/controllers/Posts.php*

```php
<?php

class Posts extends Controller{
    public function __construct(){
        if(!isLoggedIn()){
            redirect('/users/login');
        }
    }
    //..
```

## Модель Post

В конструкторе подгружаем модель, затем через эту модель получаем все посты и передаём в вид:

*app/controllers/Posts.php*

```php
<?php

class Posts extends Controller{
    public function __construct(){
        if(!isLoggedIn()){
            redirect('/users/login');
        }

        $this->postModel = $this->model('Post');
    }

    public function index(){

        // Get posts
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);
    }
}
```

Создаём модель и метод `getPosts()` в котором мы делаем запрос к БД:

*app/models/Post.php*

```php
<?php

class Post{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getPosts(){
        $this->db->query('
            SELECT *,
            posts.id as postId,
            users.id as userId,
            posts.created_at as postCreated,
            users.name as userCreated
            FROM posts
            INNER JOIN users
            ON posts.user_id = users.id
            ORDER BY posts.created_at DESC
        ');
        return $this->db->resultSet();
    }
}
```

Мы соединяем таблицы `users` и `posts` с помощью `INNER JOIN` чтобы по ID пользователя выводить его имя. Нам нужно сформировать псевдонимы, чтобы возможно было вывести переменные в виде:

*app/views/posts/index.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT ?>/posts/add" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Add Post</a>
    </div>
    <div class="col-md-12">
        <?php foreach($data['posts'] as $post): ?>
            <div class="card card-body mb-3">
                <h4 class="card-title"><?php echo $post->title ?></h4>
                <div class="bg-light p-2 mb-3">Written by <?php echo $post->userCreated ?> on <?php echo $post->postCreated ?></div>
                <p class="card-text"><?php echo $post->body ?></p>
                <a href="<?php echo URLROOT ?>/posts/show/<?php echo $post->postId ?>" class="btn btn-dark">Read More</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Добавление нового поста

Мы хотим чтобы залогиненные пользователи не видели страницу приветcвия (как в Facebook), а вместо этого список постов:

*app/controllers/Pages.php*

```php
public function index(){
    if(isLoggedIn()){
        redirect('/posts');
    }
    //..
```

Опишем метод `add()`:

```php
public function add(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'title' => trim($_POST['title']),
            'body' => trim($_POST['body']),
            'user_id' => $_SESSION['user_id'],
            'title_err' => '',
            'body_err' => ''
        ];

        // Validate title
        if(empty($data['title'])){
            $data['title_err'] = 'Please enter title';
        }
        if(empty($data['body'])){
            $data['body_err'] = 'Please enter body text';
        }

        // Make sure no errors
        if(empty($data['title_err']) && empty($data['body_err'])){
            if($this->postModel->addPost($data)){
                flash('post_message', 'Post Added');
                redirect('/posts');
            }
            else{
                die("Something wrong");
            }
        }
        else{
            // Load view with errors
            $this->view('posts/add', $data);
        }
    }
    else{
        $data = [
            'title' => '',
            'body' => ''
        ];

        $this->view('posts/add', $data);
    }
}
```

Если POST данных нет, тогда просто подгружаем вид с пустыми данными. Если же данные отправленны, тогда мы получаем данные с формы, если при этом отправляются пустые данные, мы делаем проверку и создаём сообщения об ошибках валидации. Если же ошибок валидации нет, тогда мы сохраняем данные с помощью метода модели `addPost()`, создаём флеш-сообщение и делаем редирект на страницу с постами.

*app/views/posts/add.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <div class="card card-body bg-light mt-5">
        <h2>Add Post</h2>
        <form action="<?php echo URLROOT; ?>/posts/add" method="POST">
            <div class="form-group">
                <label for="title">Title: <sup>*</sup></label>
                <input type="text" name="title" class="form-control form-control-lg <?php echo !empty($data['title_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title'] ?>">
                <span class="invalid-feedback"><?php echo $data['title_err'] ?></span>
            </div>
            <div class="form-group">
                <label for="body">Body: <sup>*</sup></label>
                <textarea name="body" class="form-control form-control-lg <?php echo !empty($data['body_err']) ? 'is-invalid' : ''; ?>"><?php echo $data['body'] ?></textarea>
                <span class="invalid-feedback"><?php echo $data['body_err'] ?></span>
            </div>
            <input type="submit" class="btn btn-success" value="Submit">
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

Метод `addPost()` очень похож на метод регистрации нового пользователя:

*app/models/Post.php*

```php
public function addPost($data){
    $this->db->query('INSERT INTO posts (title, body, user_id) VALUES(:title, :body, :user_id)');
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':body', $data['body']);
    $this->db->bind(':user_id', $data['user_id']);

    if($this->db->execute()){
        return true;
    }
    else{
        return false;
    }
}
```

В вид выводим флеш сообщение:

*app/views/posts/index.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('post_message'); ?>
//..
```

## Отображение подробностей поста

Для отображения деталей поста нам понадобится уже доступ к двум моделям, поэтому в конструкторе мы подгрузим модель `User`.

```php
class Posts extends Controller{
    public function __construct(){
        if(!isLoggedIn()){
            redirect('/users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    //..

    public function show($id){
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }
}
```

Для получения деталей поста мы будем использовать метод `getPostById()` модели `Post`, для получения деталей о пользователей метод `getUserById()` модели `User`.

*app/models/Post.php*

```php
<?php

class Post{
    //..
    public function getPostById($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }
```

*app/models/User.php*

```php
<?php

class User{
    //..
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }
```

В виде выводим данные, при этом добавим кнопки редактирования и удаления поста, которые будут отображаться только тогда, когда ID текущего пользователя будет таким же как и ID поста.

*app/views/posts/show.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <hr>
    <h1><?php echo $data['post']->title ?></h1>
    <div class="bg-light p-2 mb-3">
        Written by <?php echo $data['user']->name ?> on <?php echo $data['post']->created_at ?>
    </div>
    <div>
        <?php echo $data['post']->body ?>
    </div>
    <?php if($data['post']->user_id == $_SESSION['user_id']): ?>
        <hr>
        <a href="<?php echo URLROOT ?>/posts/edit/<?php echo $data['post']->id ?>" class="btn btn-dark"><i class="fa fa-pencil"></i> Edit Post</a>
        <form action="<?php echo URLROOT ?>/posts/delete/<?php echo $data['post']->id ?>" method="POST" class="pull-right">
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </form>
    <?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Редактирование поста

Метод редактирования будет очень похож на метод добавления поста за тем лишь исключением что нам нужно передавать ID поста, которое мы хотим изменить. Кроме этого нам нужно проверять имя текущего пользователя и ID автора поста и если они не совпадают, то нужно делать редирект на страницу постов.

*app/controllers/Posts.php*

```php
public function edit($id){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'id' => $id,
            'title' => trim($_POST['title']),
            'body' => trim($_POST['body']),
            'user_id' => $_SESSION['user_id'],
            'title_err' => '',
            'body_err' => ''
        ];

        // Validate title
        if(empty($data['title'])){
            $data['title_err'] = 'Please enter title';
        }
        if(empty($data['body'])){
            $data['body_err'] = 'Please enter body text';
        }

        // Make sure no errors
        if(empty($data['title_err']) && empty($data['body_err'])){
            if($this->postModel->updatePost($data)){
                flash('post_message', 'Post Updated');
                redirect('/posts');
            }
            else{
                die("Something wrong");
            }
        }
        else{
            // Load view with errors
            $this->view('posts/edit', $data);
        }
    }
    else{
        // Get existing post from model
        $post = $this->postModel->getPostById($id);
        // Check for owner
        if($post->user_id != $_SESSION['user_id']){
            redirect('/posts');
        }
        $data = [
            'id' => $id,
            'title' => $post->title,
            'body' => $post->body
        ];

        $this->view('posts/edit', $data);
    }
}
```

В методе `updatePost()` модели `Post` сформируем запрос для обновления записи:

*app/models/Post.php*

```php
public function updatePost($data){
    $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':title', $data['title']);
    $this->db->bind(':body', $data['body']);

    if($this->db->execute()){
        return true;
    }
    else{
        return false;
    }
}
```

В виде при отправке данных, нужно не забыть передать ID поста:

*app/views/posts/edit.php*

```php
<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <div class="card card-body bg-light mt-5">
        <h2>Edit Post</h2>
        <form action="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['id'] ?>" method="POST">
            <div class="form-group">
                <label for="title">Title: <sup>*</sup></label>
                <input type="text" name="title" class="form-control form-control-lg <?php echo !empty($data['title_err']) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title'] ?>">
                <span class="invalid-feedback"><?php echo $data['title_err'] ?></span>
            </div>
            <div class="form-group">
                <label for="body">Body: <sup>*</sup></label>
                <textarea name="body" class="form-control form-control-lg <?php echo !empty($data['body_err']) ? 'is-invalid' : ''; ?>"><?php echo $data['body'] ?></textarea>
                <span class="invalid-feedback"><?php echo $data['body_err'] ?></span>
            </div>
            <input type="submit" class="btn btn-success" value="Submit">
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
```

## Удаление поста

Создадим метод `delete()`:

*app/controllers/Posts.php*

```php
public function delete($id){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Get existing post from model
        $post = $this->postModel->getPostById($id);
        // Check for owner
        if($post->user_id != $_SESSION['user_id']){
            redirect('/posts');
        }
        if($this->postModel->deletePost($id)){
            flash('post_message', 'Post Removed');
            redirect('/posts');
        }
        else{
            die("Something wrong");
        }
    }
    else{
        redirect('/posts');
    }
}
```

Теперь метод удаления в модели:

*app/models/Post.php*

```php
public function deletePost($id){
    $this->db->query('DELETE FROM posts WHERE id = :id');
    $this->db->bind(':id', $id);

    if($this->db->execute()){
        return true;
    }
    else{
        return false;
    }
}
```

Чтобы при авторизации у нас было привествие можем вывести имя пользователя из сессионной переменной в меню:

*app/views/inc/navbar.php*

```php
<ul class="navbar-nav ml-auto">
<?php if(isLoggedIn()): ?>
    <li class="nav-item"><a class="nav-link" href="/">Welcome, <?php echo $_SESSION['user_name'] ?></a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a></li>
<?php else: ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a></li>
<?php endif; ?>
</ul>
```