# Скорость сайта

Быстродействие сайта - один из самых важных показателей, который влияет не только на результаты поисковой выдачи, но и на удобство использования сайта и лояльность пользователей.

* Измерить скорость
    * Проганяйте разными тестам по несколько раз
        * https://gtmetrix.com/
        * https://tools.pingdom.com/
        * https://webpagetest.org/
    * Смотрите на разные цифры - ответ от сервера, отрисовка первого елемента/экрана, первого взаимодействия, полная загрузка страницы
    * Можно сделать видезапись и замерить время первого взаимодействия
    * Выбирайте правильную локацию/местоположение - расположение сервера, где находяться ваши пользователи (или ближайшее место)
    * Тип соединения (кабель, Wi-Fi, 3G)
    * Тестируйте разные типы страниц
* Какие показатели норма (рекомендации 2018 Google)?
    1. Speed Index < 3s
    2. Количество запросов < 50
    3. Размер страницы < 500KB
* Какие реальные цифры (средние цифры для Великобритании 2018 Google)?
    1. Speed Index = 5.98s
    2. Количество запросов = 111
    3. Размер страницы = 2.98MB
* Протестируйте свой сайт в сравнении с конкурентами в вашей нише - https://thinkwithgoogle.com/feature/testmysite
* Способы улучшения
    * Обновитесь до последней версии PHP
    * Хостинг на SSD
    * Сервера рядом с пользователями
    * Сжатие картинок
    * Конвертируйте Gif в HTML5 видео
    * HTTP/2 (паралельная загрузка файлов и приоритизация). Если ваш сервер поддерживает HTTP/2, тогда лучше размещать всю статику на своём сервере и исключить сторонние серверы
    * Минификация/конкатенация скриптов и стилей
    * gzip сжатие
    * Кеширование статики на сервере
* Кэшируем
    * Кэширование браузера (информация хранится в браузере)
    * Кэширование страниц (информация храниться на сервере)
    * WP Rocket делает прелоад, когда делается эмуляция первого посещения пользователя и реальный первый клиент уже получает закешированную страницу быстрее.
    * Opcode кеширует PHP
    * Объектный кэш кеширует запросы к БД
    * CDN кэширование. Нужно измерять скорость сайта чтобы точно знать что это реально поможет ускорить сайт