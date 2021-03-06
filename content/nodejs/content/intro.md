# Введение в Node.js

Перед началом работы нужно скачать с официального сайта *https://nodejs.org* и установить Node.js.

* Node.js это open source серверная среда
* Node.js запускается на разных ОС (Windows, Linux, Unix, Mac и т. д.)
* Node.js использует JavaScript на сервере

При написании JavaScript для браузера код получает доступ к глобальным объектам, таким как `document` и `window`, наряду с другими API и библиотеками. С помощью Node.js код может обращаться к жесткому диску, базам данных и сети.

При использовании платформы Node можно создать все что угодно, начиная от утилит командной строки и заканчивая веб-серверами.

## Почему Node.js?

Обычной задачей для веб-сервера может быть открытие файла на сервере и возврат содержимого клиенту.

Вот как PHP обрабатывает запрос файла:

* Отправляет задачу в файловую систему компьютера
* Ожидание открытия файловой системы и чтение файла
* Возвращает содержимое клиенту
* Готов обработать следующий запрос

Вот как Node.js обрабатывает запрос файла:

* Отправляет задачу в файловую систему компьютера
* Готов обработать следующий запрос
* Когда файловая система открыла и прочитала файл, сервер возвращает содержимое клиенту

Node.js исключает ожидание и просто продолжает следующий запрос.

Node.js выполняет однопоточное, неблокирующее, асинхронное программирование, которое очень эффективно использует память.

## Что может Node.js

* Node.js может генерировать динамическое содержимое страницы
* Node.js может создавать, открывать, читать, писать, удалять и закрывать файлы на сервере
* Node.js может получать данные из формы
* Node.js может добавлять, удалять, изменять данные в вашей базе данных