# Что такое PHP и как это работает

**PHP** - это серверный скриптовый язык. Технически PHP является языком программирования.

PHP скрипт – это набор инструкций для php-интерпретатора, которые выполняються на сервере и реализуют какую-то функциональность. Результатом работы PHP-скрипта обычно является HTML-код, который возвращается клиенту в браузер. Тем не менее, PHP-интерпретатор может выполнять не только генерацию HTML-кода, но и работать с базой данных или файловой системой сервера.

## Как работает HTML?

У нас есть пользователь и где-то у нас есть компьютер на которой стоит серверное програмное обеспечение. На этом сервере размещаються статичные html-страницы. Пользователь запрашивает нашу страничку - сервер забирает контент этой странички, возвращает пользователю, а браузер эту страничку обрабатывает и отбражает.

## Как работает PHP?

Есть тот же самый пользователь и сервер, но на этот раз у нас на сервере используеться какая-то серверная технология (PHP, Python, Ruby). Пользователь снова посылает запрос на сервер - если заправшиваеться html-страница - он сразу же ищет эту страницу и отдает её пользователю. Но если запрос пришёл на страницу с расширением *.php* или любой другой, которая указываеться в настройках сервера, то он берёт контент данной страницы и отдаёт его на обработку интерпретатору, который заходит на страничку и последовательно пробегаеться по содержимому данного файла в поисках своего кода, который имеет свой синтаксис. Далее интерпретатор обрабатывает/выполняет найденный программный код и возвращает необходимый результат, то есть сгенерированный HTML документ.

**Скрипт:**

* Запускается в ответ на событие
* Запускает набор команд, работающие сверху вниз от начала до конца
* После запуска события, действие со стороны пользователя почти не требуется или они не нужны вообще. PHP скрипт запустится тогда, когда получит запрос веб-страницы. После запуска интерпретатор выполнит команды от начала и до конца и завершится до того момента, когда другое действие снова не запустит скрипт

**Программа:**

* Остаётся запущенной, даже без ответа на событие. Она запущенна и ждёт запроса действий. Запрос на действие может поступить от пользователя, от другой программы или устройства
* Программа более гибко работает с командами, таким образом она очень часто не имеет четких начальных и конечных точек
* Часто с программой взаимодействует несколько пользователей. Например Photoshop, вы его запустили и он продолжает работать, ожидая от вас действий или выключения. Программа исполняет не линейный набор команд - она перескакивает с одной на другую, в зависимости от того что вы хотите сделать в данный момент.

Чем сложнее становится скрипт, тем больше причин называть его программой.

* Язык может быть серверным и клиенским - всё зависит от того где скрипт выполняет свою работу. Код запущенный на веб-сервере - серверный, на компьютере пользователя - клиенский (клиент бразузер, язык - JavaScript). JavaScript отправляется в браузер пользователя и исполняется интерпретатором JavaScript, который интегрированный в браузер. Код написанный на PHP никогда не отправляется в браузер пользователя - он полностью выполняется на сервере. А вот результат работы PHP скрипта (сгенерированный HTML) уже отправляется в браузер пользователя. Тот факт, что PHP запускается на сервере, говорит о том что он не может запуститься сам по себе - чтобы использовать PHP, нам нужен запущенный сервер
* PHP код не нужно компилировать, он выполняется сервером именно так как написан (интерпретируемый язык). Другие языки, такие как C или Java требуют чтобы код был скомпилирован или преобразован в другую форму (машинный байт код) перед использованием (компилированный язык).
* PHP разработан специально для использования совместно с HTML. Мы можем вставить PHP в HTML, а можем использовать его для создания/генерации HTML страниц. В итоге PHP возвращает HTML в браузер. PHP код это способ ввода, а HTML это способ вывода. Файлы с расширением *.php* скажут серверу о том что внутри может быть PHP код, который должен быть обработан интерпретатором PHP.
* PHP добавляет динамику на веб-страницу, чего не может сделать статический HTML. То есть содержимое страницы может меняться в зависимости от обстоятельств, таких как взаимодействие с пользователем или базой данных.
* Синтаксис PHP относится к C-подобным языкам и очень схож на C, Java, Perl.