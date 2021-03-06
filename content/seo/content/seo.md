# SEO или техническая оптимизация сайта

Цель технической оптимизации - это сделать сайт максимально быстрым, технически правильным и удобным для пользовалей и поисковых роботов.

* Сайт должен быть адаптивным
* Набирать не менее 85% на Google Pagespeed Insights
* Вес страниц не должен превышать ...
* Скорость загрузки не более ...
* Пользователи должны мгновенно находить необходимую информацию
* Интерфейс сайта или приложения должен быть простым и понятным
* Cайт должен быть максимально релевантен продвигаемым запросам

- [Верстка](#frontend)
    - Семантика HTML5
    - Вынесение CSS и JS в отдельные файлы
    - Теги `title` и `meta` должны располагаться сразу после открытия тега `head`
    - Единственный тег `h1` на странице и правильное использование заголовков
    - Объязательное указание атрибута `alt` в теге `img` у картинок
    - Наличие фавикона
    - Важный контент должен находиться как можно выше
    - Ссылки на внешние ресурсы и внутренние странцы должны быть абсолютными
    - По возможности не использовать флеш и фреймы
    - Избегайте сквозных ссылок
    - Проверка и исправление ошибок валидации
    - Добавляем социальные кнопки на сайт
    - Асинхронная загрузка скриптов
    - Сжатие файлов в формат GZIP
    - Использование SVG графики
    - Использование кастомного набора иконок (генерация только тех которые используются в проекте)
    - Предварительная оптимизация изображений без потери качества до загрузки на сервер
    - Обрезка исходных изображений до размеров применяемых в верстке
- [Настройка ответов сервера и системы управления](#setting_respond)
    - Уменьшение количества HTTP запросов
        - Минификация и объединение CSS в один файл
        - Минификация и объединение JavaScript в один файл
        - Использование CSS спрайтов
    - Сжатие HTML
    - Помещайте javascript в конец страницы
    - Используйте CDN для загрузки популярных JavaScript библиотек
    - Используйте Gzip- сжатие
    - Используйте ассинхронный JavaScript
    - Избегайте правила @import
    - В `head` нужно добавлять самые важные CSS стили (обнуление стилей, сетка, первый экран)
    - Делаем 301 редирект и удаляем зеркало без `www`
    - Переход на протокол HTTPS и удаление зеркала без HTTP
    - Убираем дубли страниц
    - Создание и использование страницы ошибки 404
    - Создание HTML карты сайта
    - Rel "canonical" (если есть дубликаты)
    - Закрываем технические разделы и дубли страниц в *robots.txt* 
    - Присваиваем регион сайта
    - Автоматическая генерация XML карты сайта *sitemap.xml*
    - Микроразметка [schema.org](http://schema.org)
    - Настройка ЧПУ для сокращения URL и указания ключевого слова
    - Ключевые слова и описание для SEO
    - `title` и `h1` должны отличаться
    - Установка виджетов аналитики в админ-панели CMS
    - Внутренняя перелинковка
- [Уменьшение времени ответа сервера](#server)
    - Кешировать статических ресурсов на сервере (изображения, скрипты, стили)
    - Использование веб-сервера nginx
    - Использование OpCache
    - Оптимизация запросов к БД
    - Используйте простую логику обработки данных
    - Старайтесь не использовать данные со сторонних ресурсов
    - Применение отложенной загрузки второстепенных данных
- [Работа с поисковыми системами](#search_engine)
    - Регистрация сайта в Google Webmaster Tools и Яндекс Вебмастер
    - Настройка аналитики от Google и Yandex
    - Микроданные и сниппеты
    - Google Maps и Яндекс адреса
    - [Фильтры и санкции поисковых систем](#filters_search_systems)

<a name="setting_respond"></a>
## Настройка ответов сервера

* Поисковые машины воспринимают сайт с `www` и без `www`, как два различных ресурса и индексируют как два отдельных сайта. Если продвигается сайт без `www` – а поисковая система первым нашла сайт с `www`, то сайт без `www` будет восприниматься как дубликат –  все старания будут напрасны, так как он просто выпадет из рейтинга. Поэтому необходимо, чтобы было выбрано главное зеркало и настроен 301 редирект.
* Каждая страница сайта, независимо от того, статична она или генерируется динамически, должна  быть доступна только по одному уникальному URL-адресу. При этом все остальные его вариации (генерируемые CMS) должны перенаправляться на основной URL-адрес посредством 301-го редиректа. 
    - `site.ua/game-character/genetics` - нет `/`
    - `site.ua/game-character/Genetics` - разный регистр
    - `site.ua/index.php` - присутствует `index.php`
    - `site.ua/index.html` - присутствует `index.html`
    - `site.ua/index.asp` - присутствует `index.asp`
    - `site.ua/game-character/geneetics/` - ошибка в названии раздела
* Если сервер понял запрос, но не нашёл соответствующего ресурса по указанному URL, пользователь должен быть проинформирован, что запрашиваемой страницы больше (или вообще) не существует, также должна быть предоставлена возможность дальнейшего взаимодействия с сайтом. Донесение информации об отсутствии страницы для поискового робота также очень важно. Это необходимо для того, чтобы не плодились дубли и не размывалась релевантность страниц в индексе поисковых систем. Страница с ответом сервера `404` должна быть оформлена в дизайне сайта. Важно, чтобы внутренние ссылки не вели на страницу с ошибкой `404`.
* Наличие robots.txt, файла sitemap.xml и микроразметки schema.org
* Переход на протокол HTTPS – это протокол, который обеспечивает безопасность и конфиденциальность при обмене информацией между сайтом и устройством пользователя. В августе 2015 года Google официально объявил, что использование сайтами протокола HTTPS будет учитываться в качестве одного из факторов ранжирования именно из-за фактора безопасности.
* Наличие человекопонятного URL. Преимущества: использование ключевых слов в названии страниц упрощает навигацию на сайте и понимание содержимого ссылки на сайт; поисковые системы понимают ключевые слова в ЧПУ, выделяют ЧПУ адреса в поиске, что в целом повышает релевантность страницы; ЧПУ повышает CTR сниппета страницы в поиске, что улучшает поведенческие факторы; ЧПУ улучшает SEO страниц, изображений и других документов, так как является значительным фактором поисковой оптимизации.

<a name="frontend"></a>
## Верстка

* Вынесение файлов JavaScript и CSS со страниц сайта в отдельные файлы. Это дает возможность сделать код страниц сайта более простым и легким. Также уменьшится вес кода. Т.е., если поисковый робот возьмет и съест первые 20Кб кода веб-страницы, 15Кб из которых будут выделены под JavaScript и CSS, то полезного кода поисковик получит всего 5Кб. 
* Сжатие HTML-кода (в том числе встроенного кода JavaScript или CSS) позволяет сократить объем данных, чтобы ускорить загрузку и обработку. Для поддержки кода, лучше оставлять комментарии и расставлять отступы в нем, но браузеру они совершенно не нужны, поэтому целесообразней его cжать. Это сократит размер страницы и ускорит загрузку сайта. 
* Сжатие кода CSS также проходит путем удаления ненужных байтов - лишних пробелов, переносов строки и отступов. При сокращении CSS ускоряется загрузка, синтаксический анализ и отображение страницы.
* Также как и в случае с CSS и HTML, браузеру совершенно безразличны комментарии и пробелы в скриптах JavaScript.
* Желательно всегда проверять каждую страницу сайта на соответствие веб-стандартам W3C. Ошибки в коде веб-страниц обычно приводят к значительному снижению их доступности, что плохо как для посетителей сайта, так и для поисковых систем.
* Основные теги `title` и `meta` должны располагаться сразу после открытия тега `head`.
* Использование заголовка `h1` только 1 раз на странице. Правильная расстановка (иерархия) тегов заголовков `h1`, `h2`, `h3`. Подзаголовки не должны быть оформлены тегами `b` и `strong`.
* Обязательно наличие фавикона. При просмотре сайтов в браузере и открытии множества вкладок, заголовок страниц может быть не виден полностью, зато по фавикону можно с легкостью определить необходимую вкладку.
* Обязательно должен быть указан атрибут `alt` и `title` в теге `img` у картинок. Данный атрибут рассказывает поисковым системам, что изображено на картинке. Он используется для оптимизации изображений по запросу, и может помочь изображениям сайта попасть на видимые позиции в сервисы вроде Яндекс.Картинки и Google Картинки, которые в итоге могут стать дополнительным источником траффика.
* Одним из главных пунктов в оптимизации контента, является его размещение на странице. Чем дальше важный и полезный контент находится от начала страницы, тем менее он интересен поисковому роботу. Поэтому структура всех страниц с контентом должна быть составлена так, чтобы в первоначальном ответе сервера содержалась необходимая информация для отображения наиболее важной части страницы. Сразу после хедера не должно быть длинного меню с кнопками репостов и других элементов, а сразу должен идти контент.
* Желательно не использовать флеш и фреймы, которые очень не дружелюбны с поисковыми системами. 
* Внутренние ссылки, ссылки на другие страницы сайта, должны быть абсолютными, то есть начинаться с `http://` и содержать имя домена. Имя домена должно быть основным зеркалом.
* Использование кеша браузера. Благодаря кешированию пользователи, повторно посещающие сайт, тратят меньше времени на загрузку страниц. Заголовки кеширования должны применяться ко всем кешируемым статическим ресурсам, а не только к некоторым из них (например, изображениям). Кешируемые ресурсы включают файлы JavaScript и CSS, графические и другие файлы (мультимедийное содержание, файлы PDF и т. д.). 
* Ускорить загрузку ресурсов, необходимых для отображения веб-сайта может сжатие файлов в формат GZIP, используя собственные процедуры или сторонние модули.
* Для минификации и объединения JS файлов лучше использовать Gulp или любой другой таск раннер. 

<a name="server"></a>
## Уменьшение времени ответа сервера:

**Время ответа сервера** - это время, за которое после команды пользователя открыть определенную страницу на сервере происходит выполнение всех команд программного кода, сбор информации из базы данных, формирование html страницы, которую отобразит браузер, и передача ее пользователю.
Этот параметр влияет на позиции в поисковой выдаче. Google считает, что время ответа сервера должно составлять менее 200 мс.

* Используемый веб-сервер (Apache, IIS). Ряд веб-серверов архитектурно не предназначены для обработки большого количества запросов, поэтому могут создавать дополнительные задержки даже при выдаче статических файлов. Для быстрой работы веб-сервера необходимо использовать nginx (в связке с Apache, php-fpm или другими серверами приложений для обработки серверных вычислений).
* Использование OpCache (акселератора PHP). Кэширование исполняемого кода (скриптов сайта) — обычно первый шаг к быстрому серверу. Кэширование позволяет не переводить каждый раз PHP-инструкции в бинарный код, а использовать уже готовый результат. Это кэширование не имеет ничего общего с кэшированием результата выполнения PHP-скриптов (например, кэширование HTML-страниц или MySQL-запросов).
* Оптимизируйте запросы к базе данных. Как минимум, половина всех задержек на стороне сервера складывается из запросов к базе данных. При правильной настройке таблиц (индексов) в базе данных и структуры запросов, а также кэшированию наиболее часто используемых результатов или пересчету промежуточных результатов в отдельные таблицы возможно снизить потребляемые серверные ресурсы в несколько (десятков или даже сотен) раз.
* Сложная логика обработки данных. Может быть уже идеально настроенная база данных, но выборка большого количество элементов и произведение над ними многочисленных операций (перебор в цикле) способны существенно затормозить сайт. Профилирование времени выполнения серверных скриптов и устранения ненужных операций (упрощения серверной логики) может также дать существенный результат в плане серверной производительности.
* Обращение к сторонним сервисам. Если в коде серверных скриптов есть запросы к сторонним сервисам для получения данных, могут возникнуть проблемы. Если не контролируется производительность источников данных, которые запрашиваются, то время ответа сервера может непредсказуемо изменяться — в зависимости от времени ответа сторонних сервисов. Хорошей практикой является использование в серверных запросах только внутренних источников данных (производительность которых контролируется), либо запрос данных на клиентской стороне в отложенном режиме.

<a name="filters_search_systems"></a>
### Фильтры и санкции поисковых систем

Этот пункт проверяется в Яндекс.Вебмастере: зайдите в раздел *Диагностика / Безопасность и нарушения*. В Google такую проверку можно осуществить в разделе *Поисковый трафик / Меры*, принятые вручную.

Как правило, некоторые даже не догадываются, что их сайт находится под фильтрами.

<a name="images"></a>
## Оптимизация изображений

Рекомендуется использовать следующие сервисы:

- https://compressor.io/
- https://tinypng.com/

Уменьшить размеры изображений до минимума без понижения их качества. Например, если на сайте нам нужна картинка 150x150px, то и на сервере картинка должна быть соответствующих размеров. Параметры изображений не должны подгоняться при помощи CSS или HTML-тегов.

PageSpeed Insights предлагает опцию загрузки уже оптимизированных изображений, поэтому их можно загрузить на сервер непосредственно с данного сервиса. То же самое можно сделать и с JavaScript и CSS.

### Микроданные

Под разные задачи сайта используем расширенные сниппеты:

- для Google
- для Яндекс

Расширенные сниппеты привлекают больше внимания для посетителей, и в итоге дают больше переходов, чем обычные описания (сниппеты) в результатах поиска.

### Google Maps и Яндекс адреса

Если сайт коммерческий, то добавляем его в Google Places, а так же в Яндекс адреса.
Это может дать так же дополнительных посетителей, у Google точно даст, если страницу грамотно прописать и оптимизировать.

### Добавляем социальные кнопки на сайт

Социальные факторы важны для seo продвижения, поэтому делаем так, что бы на сайте присутствовали социальные кнопки, причем в первом экране страницы и в видном месте.

### Внутренняя перелинковка

Внутренняя перелинковка всегда делается исходя из задач, например:

- Распределение веса по сайту
- Индексация страниц сайта

### Мета теги

* `title` – основной мета тег, на который  обращают внимание поисковые системы, и учитывают в факторах ранжирования сайтов. Это первое что необходимо прописывать на сайте, перед началом продвижения. Длина `title`: в поисковой системе Google и Яндекс отображается в результатах поиска 70 символов, но можно делать и длиннее тайтлы, как 125 символов. Не рекомендуется делать тег `title` заспамленным, то есть в нем повторять много раз нужное ключевое слово.
* `description` - мета тег `description` нужен больше для повышения CTR (количество заходов на просмотры) с результатов поиска. Но так же в нем прописать основные ключевые слова можно, с маленькой долей вероятности, но может это и влияет на ранжирование.
* `keywords` - мета тег `keywords` можно прописывать, а можно и нет, с маленькой (очень маленькой) долей вероятности это влияет на ранжирование, но если не прописывать, то ничего страшного нет. Когда для страницы прописаны `keywords`, при анализе сайтов конкурентов легко определить, по каким словам продвигается конкурент.

### Теги

* `img` - для изображений важно прописывать атрибут `alt`, который предназначен для прописывания картинок, означает альтернативный текст. Нужно прописывать для всех картинок иначе будут ошибки валидации, а также как по картинкам тоже есть переходы на сайт, ведь у Google и Яндекс есть поиск по картинкам. Так же в картинке стоит прописывать `title`. Что бы увидеть его, нужно просто навести курсив мышки на картинку, и отобразиться `title` картинки.
* `h1` - желательно использовать не более 1-го раза на странице, обычно это название страницы, которое показывает поисковой системе, что это нужно учитывать.
* `strong` - выделение жирным в тексте раньше точно влияло, сейчас тоже дает определенный вес, но не такой значительный. В тексте можно 1-2 раза выделить жирным основные ключевые слова, но не более, так как это будет выглядеть подозрительным и спамным.

### Текст

Для разных типов сайтов тексты оптимизируются по-разному, самое главное не повторять слишком много раз в нем ключевые слова, так как поисковые системы могут посчитать это за спам и наложить санкции или фильтр на сайт.

Текст, который не несет какой то ценности для пользователя, должен находится в самом подвале странице, где его мало просматривают, и в основном предназначен для ботов поисковых систем.

### Структура

**Уровень вложенности**

В первую очередь нужно сделать так, что бы все страницы сайта были не дальше 3-го уровня вложенности, максимум 4-го.

* Главная страница – первый уровень вложенности
* Раздел сайта — второй уровень вложенности
* Под раздел – третий уровень вложенности

Для простых сайтов, с количеством страниц до 100 это просто, а если это большие интернет магазины, с десятками тысяч товаров, или СМИ сайты с сотнями тысяч страниц, то обязательно необходимо:

- Создавать карту сайта (или карты, не одну а несколько)
- Специальные страницы для индексации страниц с далеким уровнем вложенности

**Дублированный контент**

Это довольно распространённая ошибка, которая негативно влияет на продвижение сайта. Если посмотреть на Google Panda, то он сильно штрафует сайты за дублированный контент.

Дубль страницы – это когда на двух разных страницах, находится одинаковая информация.

Что нужно делать:

- Убирать дублированный контент
- Закрывать такие страницы от индексации
- Использовать тег `rel canonical` для обозначения важности страниц

### Микро данные

Существует ряд микро данных, которые можно использовать с пользой для продвижения сайта, а так же увеличивать CTR из выдачи.

С помощью микро данных можно делать красивые сниппеты как для SEO, так и для контекстной рекламы.

Детальнее про форматы микро данных можно прочитать и изучить на сайте [schema.org](http://schema.org/docs/schemas.html)

### Перелинковка сайта

Перелинковка сайта важна при продвижении сайта, и мы ее обязательно используем во внутренней оптимизации сайта, когда у проекта есть множество страниц. Это зачастую крупные контентные проекты и интернет магазины, с большим ядром запросов, и на них нужно грамотно распределять вес ключевых слов по сайту.

Внутренняя перелинковка может быть блоками (например блок "С этим товаром покупают" в интернет-магазине) и в статьях (посмотрите Википедию), где все страницы грамотно между собой перелинкованы и передают вес. Важно не ссылаться на одну и ту же страницу более 1-го раза на странице, так как вес поисковые системы учитывают только с одной ссылки. Если же ссылок со страницы более 2-х на одну и ту же страницу, необходимо использовать хеш теги, тогда вес будет передаваться, например:

```html
<a href="http://sitename.com">продвижение сайтов</a>
<a href="http://sitename.com/#раскрутка сайта">раскрутка сайта</a>
```

### Социальные факторы

Социальные факторы в последнее время влияют все больше на поисковое продвижение, поэтому нужно обязательно делать так, что бы кнопки социальных сетей были на видном месте.

На продвижение сайтов влияют: лайки (facebook), твиты (twitter), +1 (google plus). Лайки, твиты должны указывать на доменное имя, а не на страницу социальной сети, тогда это дает отдачу и передает определенный вес, который влияет на ранжирование.

### Юзабилити

Дизайн и удобство сайтом (юзабилити), так же влияет на просмотры страниц, конверсии, заказы, продажи. Чем удобней сайт, чем лучше его структура, и восприятие, тем лучше это в целом для продвижения, так как будет давать больше отдачу, от привлечённого трафика на сайт.

Удобство сайта это постоянная работа, тестирование на альтернативные варианты, результатом чего является максимальная конверсия посетителей в покупателей (для коммерческих сайтов).

### Код

Важно оптимизировать код для того чтобы сайт работал быстро и не замедлял скорость загрузки сайта. Скорость загрузки влияет на ранжирование и если сайт грузится медленно, то поисковые системы могут его понизить в поисковой выдаче.

Часто в системе управлении бывает много лишнего, как с точки зрения работы движка, так и поисковой оптимизации.

### Инструменты поисковых систем

Нужно обязательно добавлять сайт в Google Webmaster Tools и в Яндекс Вебмастер, что бы понимать, как сканирует сайт поисковые системы, как они его видят, смотреть ошибки и устранять их.

Чем лучше внутренняя оптимизация сайта, тем проще его продвигать в поисковых системах.