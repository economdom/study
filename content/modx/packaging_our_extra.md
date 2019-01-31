# Упаковка нашего дополнения

* [Введение](#overview)
* [Настройка директории для сборки](#setting-up-our-build-directory)
    * [Создание скрипта упаковщика](#the-build-script)
* [Добавление данных](#adding-in-the-data)
    * [Добавление сниппета](#adding-the-snippet)
    * [Добавление свойств сниппета](#adding-in-snippet-properties)
    * [Добавление файловых резольверов](#adding-the-file-resolvers)
    * [Добавление пункта меню и действия](#adding-the-menu-and-action)
* [Добавление резольвера](#adding-a-resolver)
* [Добавление файлов cangelog, readme, лицензии и параметров установки](#adding-the-changelog-readme-license-and-setup-options)

<div id="overview"></div>

## Введение

В этом уроке будет рассказано как упаковать дополнение в транспортный пакет (Transport Package или TP), который затем можно будет легко установить через *Приложения / Установщик*.

Упаковывать будем всё, что относится к, разработанному нами дополнению:

* Сниппет
* Файлы из *core/components/* и *assets/components/*
* Действия
* Пункт в меню и пространство имен нашей CMP (страницы компонента)
* Значения по умолчанию для сниппета с поддержкой интернационализации (i18n)
* Добавим резольвер, который создаст пользовательские таблицы в БД

Если вы не савсэм понимаете что собой представляют транспортные пакеты, рекомендуется прочитать [Package Management](https://docs.modx.com/revolution/2.x/developing-in-modx/advanced-development/package-management) и [Transport Packages](https://docs.modx.com/revolution/2.x/developing-in-modx/advanced-development/package-management/transport-packages) перед началом практической работы.

**Справка**: Для упаковки простых дополнений можно использовать [PackMan](https://docs.modx.com/extras/revo/packman). Но в данном случае мы хотим сделать это самостоятельно и полностью разобраться что из себя представляет транспортный пакет.

<div id="setting-up-our-build-directory"></div>

## Настройка директории для сборки

В конце урока каталог *_build* будет выглядеть так:

```
|   build.config.php
|   build.schema.php
|   build.transport.php
|   setup.options.php
|   
+---data
|   |   transport.menu.php
|   |   transport.snippets.php
|   |   
|   \---properties
|           properties.doodles.php
|           
\---resolvers
                resolve.tables.php
```

Мы уже знакомы с файлами *build.config.php* и *build.schema.php* из первой части урока, а сейчас давайте просто посмотрим на другие части:

* *data/* — здесь мы собираемся поместить все наши скрипты для упаковки данных пакета.
* *resolvers/* — папка содержит [резольверы](https://docs.modx.com/revolution/2.x/developing-in-modx/advanced-development/package-management/transport-packages#TransportPackages-AResolver) для транспортного пакета.
* *build.transport.php* — это главный скрипт упаковщика, который нужно будет запустить для создания пакета. По большей степени, мы будем заглядывать в этот файл.
* *setup.options.php* — настройки установщика. Позже кратко рассмотрим для чего это нужно.

<div id="the-build-script"></div>

### Создание скрипта упаковщика

Создадим файл упаковщика с таким содержанием:

*doodles/_build/build.transport.php*

```
<?php
$tstart = explode(' ', microtime());
$tstart = $tstart[1] + $tstart[0];
set_time_limit(0);
 
/* define package names */
define('PKG_NAME','Doodles');
define('PKG_NAME_LOWER','doodles');
define('PKG_VERSION','1.0');
define('PKG_RELEASE','beta4');
 
/* define build paths */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'resolvers' => $root . '_build/resolvers/',
    'chunks' => $root.'core/components/'.PKG_NAME_LOWER.'/chunks/',
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'elements' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/',
    'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
);
unset($root);
 
/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'] . 'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
 
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
 
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
 
/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();
 
$tend= explode(" ", microtime());
$tend= $tend[1] + $tend[0];
$totalTime= sprintf("%2.4f s",($tend - $tstart));
$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");
 
 
session_write_close();
exit ();
```

Тут довольно много всего, но заметим, что это всё, что нужно для упаковки нашего пространства имен и создания файла транспортного пакета *doodles-1.0-rc1.zip* (только основа). Разберем подробно.

```
$tstart = explode(' ', microtime());
$tstart = $tstart[1] + $tstart[0];
set_time_limit(0);
 
/* define package names */
define('PKG_NAME','Doodles');
define('PKG_NAME_LOWER','doodles');
define('PKG_VERSION','1.0');
define('PKG_RELEASE','beta4');
```

Во-первых мы собираемся получить время начала сборки, чтобы в конце вывести сколько времени потребовалось на сборку. Это совсем не обязательно, просто полезная информация. 

Затем мы указываем несколько констант, которые мы будем использовать позже для определения названия нашего пакета, версии и типа релиза.

Далее:

```
/* define build paths */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'resolvers' => $root . '_build/resolvers/',
    'chunks' => $root.'core/components/'.PKG_NAME_LOWER.'/chunks/',
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'elements' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/',
    'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
);
unset($root);
 
/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'] . 'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
```

Здесь мы определяем массу путей, чтобы найти все части нашего пакета для упаковки. Это будет полезно позже в нашем скрипте сборки, так как мы можем легко ссылатся на нужные нам местоположения файлов.

Обратите внимание на ключи `source_core` и `source_assets` - очень важно, что у них нет слэша (trailing slash). Это важно, когда мы позже их упакуем.

Наконец, мы подключили файл *build.config.php* и класс MODX. Теперь пришло время загрузить объект `modX`:

```
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
 
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
```

Здесь мы создаем объект `modX` и инициализируем контекст `mgr`. Далее мы просим MODX быть более многословным в его сообщениях об ощибках во время работы нашего скрипта при помощи метода `$modx->setLogLevel` и указываем выводить на экран с сообщением `setLogTarget`. 

Затем загружаем класс `modPackageBuilder` и получаем два полезных метода `createPackage` и `registerNamespace`.

```
$modx->createPackage(key,version,release)
```

Тут задается имя нашего пакета (оно должно быть в нижнем регистре и не должно содержать точку или дефис), версию и тип релиза. Теперь `modPackageBuilder` автоматически упакует наше пространство имен:

```
$builder->registerNamespace(namespace_name,autoincludes,packageNamespace,namespacePath)
```

* Первый параметр - это имя пространства имен (`doodles` в нашем случае).
* Второй — это авто-пакеты в массиве классов, связанных с нашим пространством имен (нам это не нужно, поэтому устанавливаем `false`).
* Третим параметром мы говорим, что хотим упаковать пространство имен в пакет (устанавливаем в `true`).
* Последний параметром мы задаем путь до нашего пространства имен. Этот последний параметр является ключевым. Обратите внимание на плейсхолдер `{core_path}`, он будет заменен на реальный путь во время установки пакета, что позволит сделать пакет более гибким. Не нужно указывать пути жестко.

И вот несколько последних строк нашего упаковщика:

```
/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();
 
$tend= explode(" ", microtime());
$tend= $tend[1] + $tend[0];
$totalTime= sprintf("%2.4f s",($tend - $tstart));
$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");
exit ();
```

Метод `pack()` говорит MODX, что нужно создать файл ZIP транспотного пакета. Остальные строки просто выводят время, которое потребовалось для сборки. Вот И всё. Если вы запустите 
это в браузере (у меня [адрес](http://modxtest.loc/doodles/_build/build.transport.php)), вы получите отладочную информацию и в папке *core/packages/* должны получить *doodles-1.0-beta4.transport.zip*. Это наш транспортный пакет, однако конкретно для нашего дополнения этого не достаточно.

<div id="adding-in-the-data"></div>

## Добавление данных

Нам нужно упаковать наш сниппет в отдельную категорию `Doodles`. В файле *build.transport.php* добавим ниже `registerNamespace` (42 строка) такой код:

*doodles/_build/build.transport.php*

```
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
    ),
);

$vehicle = $builder->createVehicle($category,$attr);
$builder->putVehicle($vehicle);
```

Во-первых мы создаем объект `modCategory` (категория) с именем `Doodles`. Обратите внимание, что мы не сохраняем `->save()`, а только создаем объект. Далее у нас есть код для упаковки снипета, но пока проигнорируем его, мы вернемся к нему позже.

Затем мы создали большой массив атрибутов — атрибутов транспортного средства (Vehicle) категории. Что за транспортное средство? Ну, это транспортное средство, которое несет объект к транспортному пакету. Каждый объект (сниппет, пункт меню, категория и т.п.) должен иметь транспортное средство для "перевозки" в транспортный пакет. Таким образом мы создали один из них, но сначало присвоили несколько атрибутов, которые говорят MODX как это транспортное средство должно вести себя когда пользователь устанавливает пакет.

* `xPDOTransport::UNIQUE_KEY => 'category'` - здесь мы говорим MODX, что уникальным ключём для этой категории является поле `category`. Поскольку мы устанавливаем это на другой машине, то идентификатор категории скорее всего будет отличаться от ID на нашей машине. Таким образом, MODX необходим способ идентификации нашей категории `Doodles`, на случай, если пользователь примет решение удалить наше дополнение `Doodles`. MODX использует свойство `UNIQUE_KEY` чтобы искать объект `modCategory` с `'category' => "Doodles"`, а затем его там удаляет.
* `xPDOTransport::PRESERVE_KEYS => false` - иногда, нам нужно чтобы первичный ключ нашего объекта был "консервирован", а точнее мог использоватся, когда пользователь устанавливает наш пакет. Это полезно для не автоинкрементных ключей (PKs), таких как меню, которое мы получим позже. Нашей категории это не нужно, поэтому устанавливаем в `false`.
* `xPDOTransport::UPDATE_OBJECT => true` - это говорит MODX, что если категория уже существует, нужно обновить её нашей версией. Если установить в `false`, MODX просто пропустит категорию, если найдет её. Мы хотим чтобы категория обновилась.
* `xPDOTransport::RELATED_OBJECTS => true` - это указывает MODX что у нас есть связанные объекты в нашей категории, которую мы хотим упаковать (делаем это, потому что у нас есть сниппет). Наш случай - это хороший пример. Любые сниппеты, которые будут установлены, будут помещены в данную категорию.
* `xPDOTransport::RELATED_OBJECT_ATTRIBUTES` - это ассоциативный массив с атрибутами связанных объектов. Это говорит MODX искать любые связанные с ним объекты в этой категории. В нашем случае это только сниппет, но это могут быть плагины, TV-параметры (дополнительные поля), чанки и т.д.

Задаем свойства объекту сниппета:

```
'Snippets' => array(
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'name',
),
```

Здесь мы говорим, что сохранять первичный ключ сниппета не требуется (аналогично категории). Затем мы хотим обновить объект, если он уже существует. И, наконец, мы говорим MODX, что поле `name` является первичным ключем.

Далее делаем так:

```
$vehicle = $builder->createVehicle($category,$attr);
$builder->putVehicle($vehicle);
```

Это упаковывает наш объект категории в небольшое транспортное средство с атрибутами, которые мы только что определили. После чего мы добавляем его в транспортный пакет. Наша категория упакована. Теперь добавим к ней сниппет.

<div id="adding-the-snippet"></div>

### Добавление сниппета

Создадим файл *transport.snippets.php* и поместим в него следующий код:

*doodles/_build/data/transport.snippets.php*

```
<?php

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = trim(str_replace(array('<?php','?>'),'',$o));
    return $o;
}

$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'Doodles',
    'description' => 'Displays a list of Doodles.',
    'snippet' => getSnippetContent($sources['elements'].'snippets/snippet.doodles.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.doodles.php';
$snippets[1]->setProperties($properties);
unset($properties);

return $snippets;
```

Во-первых мы создали небольшой вспомогательный метод, который будет захватывать наш сниппет, который мы создавали ранее и убирать из него теги `<?php`.

После чего мы создаём массив `$snippets` - фактические это массив всех сниппетов, которые мы хотим упаковать.

Затем мы создаем объект сниппета. Обратите внимание - мы не сохраняем, а просто создаем его. Также нам нужно включить в него некоторые свойства (об этом чуть позже). В конце мы возвращаем массив `$snippets`. Помните закомментированнную часть из файла *build.transport.php*?

*doodles/_build/build.transport.php*

```
/* add snippets */
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in snippets...');
$snippets = include $sources['data'].'transport.snippets.php';
if (empty($snippets)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in snippets.');
$category->addMany($snippets);
```

Убираем комментирование. Теперь наш сниппет загружается в транспортное средство категории.

<div id="adding-in-snippet-properties"></div>

### Добавление свойств сниппета

Создайте файл *properties.doodles.php* с таким содержанием:

*doodles/_build/data/properties/properties.doodles.php*

```
<?php

$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'prop_doodles.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rowTpl',
        'lexicon' => 'doodles:properties',
    ),
    array(
        'name' => 'sort',
        'desc' => 'prop_doodles.sort_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'name',
        'lexicon' => 'doodles:properties',
    ),
    array(
        'name' => 'dir',
        'desc' => 'prop_doodles.dir_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'prop_doodles.ascending','value' => 'ASC'),
            array('text' => 'prop_doodles.descending','value' => 'DESC'),
        ),
        'value' => 'DESC',
        'lexicon' => 'doodles:properties',
    ),
);
return $properties;
```

Это PHP-представление свойств (параметров) сниппета по умолчанию. Давайте рассмотрим все его ключи:

* `name` — имя сниппета. Именно это имя указывается в вызове: `[[Doodles? &tpl=`rowTpl`]]`
* `desc` — описание свойства сниппета Это может быть либо фактическое описание, либо ключ словаря этого свойства (Lexicon key). Мы будем использовать ключи словаря, потому что нам нужно чтобы наш сниппет поддерживал интернационализацию.
* `type` — это `xtype` свойства. В настоящее время доступны 4 типа: `textfield` (текстовое поле), `textarea` (текстовая область), `combo-boolean` (да/нет) и `list` (список значений). Мы будем использовать два типа - `textfield` и `list`.
* `options` — используется только для списка значений. Это многомерный массив, который содержит опции списка. Каждая опция имеет два ключа `text` и `value`, где `value` - это реальное сохраняемое значение, когда оно выбрано, а `text` - это текст, который отображается в списке (показывается пользователю). При желании `text` может быть ключом словаря.
* `value` — значение свойства по умолчанию.
* `lexicon` — свойства могут быть i18n-совместимыми. Просто укажите тему словаря (Lexicon Topic) и MODX сделает остальное.

Как видите мы сделали ссылку на новый раздел словаря `doodles:properties`. Давайте создадим файл словаря со следующим содержанием:

*doodles/core/components/doodles/lexicon/en/properties.inc.php*

```
<?php

$_lang['prop_doodles.ascending'] = 'Ascending';
$_lang['prop_doodles.descending'] = 'Descending';
$_lang['prop_doodles.dir_desc'] = 'The direction to sort by.';
$_lang['prop_doodles.sort_desc'] = 'The field to sort by.';
$_lang['prop_doodles.tpl_desc'] = 'The chunk for displaying each row.';
```

Как мы видите, здесь такой же формат как и теме словаря `default` (*default.inc.php*). Ключи в каждой строке здесь совпадают с значениями `desc` наших свойств (`$properties`). Это означает, что свойства нашего сниппета при отображении будут переводиться (полезно, если нужно переводить наше дополнение на другие языки).

Если вы запустите скрипт сейчас, то наша категория и сниппет со своими свойствами будут упакованы в пакет. Но пока что, сами файлы нашего дополнения не копируются. Давайте исправим это.

<div id="adding-the-file-resolvers"></div>

### Добавление файловых резольверов

Теперь нам нужно добавить *doodles/core/components/doodles/* и *doodles/assets/components/doodles/* в наш пакет. Нам нужно добавить эти файлы в нашу категорию транспортного средства (Category Vehicle) с помощью т.н. файловых резольверов (File Resolvers). Эти скрипты запускаются после установки пакета и могут использоваться для копирования файлов в проект с установленным MODX.

Итак, в *build.transport.php* сразу после того, как мы добавляем категорию транспортного средства на 67 строке:

*doodles/_build/build.transport.php*

```
$vehicle = $builder->createVehicle($category,$attr);
```

Добавляем этот код:

*doodles/_build/build.transport.php*

```
$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to category...');
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
```

Стоит разобрать два атрибута:

* `source` — это источник файлов или путь, по которому можно найти файлы. Это указывает на наши `source_assets` и `source_core`, которые были определены нами ранее. Обратите внимание на отсутствие слэшей.
* `target` — это eval-выражение, которое возвращает путь где будут находиться файлы нашего дополнения. Здесь мы определяем путь к активам и путь к ядру с установленным MODX.

Первый параметр в вызове `resolve()` указывает MODX, что это файловый резольвер. Более подробнее мы рассмотрим резольверы позже в этом же уроке.

Если вы запустите скрипты билдера сейчас, то это упакует данные в папки *doodles/core/* и *doodles/assets/* и установит их в соответствующие директории пользователя.

<div id="adding-the-menu-and-action"></div>

### Добавление пункта меню и действия

Теперь давайте добавим пункт меню (Menu) и действие (Action), которые будут создавать нашу пользователькую страницу компонента (Custom Manager Page или *сокр.* CMP).

Добавим такой код ниже скроки с вызовом `putVehicle()` на 80 строке нашей категории:

*doodles/_build/build.transport.php*

```
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in menu...');
$menu = include $sources['data'].'transport.menu.php';
if (empty($menu)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in menu.');
$vehicle= $builder->createVehicle($menu,array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        ),
    ),
));
$modx->log(modX::LOG_LEVEL_INFO,'Adding in PHP resolvers...');
$builder->putVehicle($vehicle);
unset($vehicle,$menu);
```

Здесь все очень похоже на код создания категории транстпортного средства (Category Vehicle). Помимо этого, мы получаем связанный объект нашего действия (Action).

* `PRESERVE_KEYS` установлено в `true` в нашем меню. Потому что ключи меню иникальны и мы хотим сохранить это для нашего установленного меню.
* `UNIQUE_KEY` связанного объекта действия является массивом. Это сообщает MODX, что нужно искать объект `modAction`, который имеет пространство имен `'namespace' => 'doodles'` и контроллер `'controllers/index'`.

Как вы, наверное, догадались, мы должны добавить файл *transport.menu.php*.

*doodles/_build/data/transport.menu.php*

```
<?php

$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => 'doodles',
    'parent' => 0,
    'controller' => 'index',
    'haslayout' => true,
    'lang_topics' => 'doodles:default',
    'assets' => '',
),'',true,true);

$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'doodles',
    'parent' => 'components',
    'description' => 'doodles.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);
$menu->addOne($action);
unset($menus);

return $menu;
```

Тут всё аналогично *transport.snippets.php*, за исключением того, что мы возвращаём одно меню и вызываем метод `addOne()` в меню объекта для добавления действия в качестве связанного объекта в меню. Обратите внимание, что поля в каждом вызове `fromArray()` являются полями таблиц `menus` и `actions` в БД. 

Итак, пункт меню и действие упакованы.

<div id="adding-a-resolver"></div>

## Добавление резольвера

Когда кто-то устанавливает наше дополнение, у них пока не будет создана таблица в базы данных `modx_doodles`. Давайте нашием PHP резольвер, который создаст её во время установки. PHP резольвер - это PHP скрипт, который запускается после установки транспортного средства (Vehicle). Добавим этот резольвер к нашему транспортному средству меню. Сразу после вызова `$builder->createVehicle` для нашего меню и перед запуском `putVehicle` для данного транспортного средства, добавим такой код:

*doodles/_build/build.transport.php*

```
$modx->log(modX::LOG_LEVEL_INFO,'Adding in PHP resolvers...');
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'resolve.tables.php',
));
```

Все, что передаётся в этот PHP резольвер это поле `source`, который указывает на этот PHP скрипт.

Давайте создадим файл *resolve.tables.php*, и поместим данный код:

*doodles/_build/resolvers/resolve.tables.php*

```
<?php

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('doodles.core_path',null,$modx->getOption('core_path').'components/doodles/').'model/';
            $modx->addPackage('doodles',$modelPath);
            $manager = $modx->getManager();
            $manager->createObjectContainer('Doodle');
            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;
```

Обратите внимание, что здесь мы делаем начальную проверку `$object->xpdo`. Переменная `$object` является нашим меню, поскольку мы подключили это в меню транспортного средства. Далее мы проверяем переменную `xpdo`. После чего мы запускаем выражение `switch`, благодаря которому мы можем выполнять задачи в зависимости от текущего действия. В данном случае, мы выполняем проверку константы `PACKAGE_ACTION` в массиве `$options`. Этот небольшой `switch` говорит нам запускать этот код во время новых установок или `ACTION_INSTALL`.

Кроме того, в `switch`, мы присваиваем `$modx` в качестве ссылки на `$object->xpdo`, для облегчения ввода. Затем мы ищем путь к модели `Doodles` с помощью вызова `getOption()`, а затем запускаем вызов `addPackage()`, чтобы добавить нашу xpdo схему в базу данных (об этом мы говорили в первой части). В конце, мы запускаем `$modx->getManager()`, который получает экземпляр класса `xPDOManager` и вызываем `$manager->createObjectContainer('Doodle')`. Этот метод говорит MODX выполнять SQL код для создания таблицы базы данных для класса `Doodle`. И в конце резольвера, мы будем возвращать `true`, для того чтобы MODX знал что всё прошло гладко.

Если вы соберёте и установите пакет, то это создаст таблицу в нашей базе данных.

<div id="adding-the-changelog-readme-license-and-setup-options"></div>

## Добавление файлов cangelog, readme, лицензии и параметров установки

При установке пакетов MODX, очень часто можно видить диалоговое окно с лицензией, readme и логом изменений. Сделаем это и для нашего пакета. Для начала нам нужно создать эти файлы.

Давайте создадим файл *changelog.txt*:

*doodles/core/components/doodles/docs/changelog.txt*

```
Changelog file for Doodles component.
 
Doodles 1.0
====================================
- Updating text, ready to build
- Added default properties to Doodles snippet in build
- Fixes to doodles class
- Fixed bugs with build, updated readme
- Initial commit
```

Дальше создадим файл лицензии:

*doodles/core/components/doodles/docs/license.txt*

```
GNU GENERAL PUBLIC LICENSE
     Version 2, June 1991
--------------------------

Copyright (C) 1989, 1991 Free Software Foundation, Inc.
59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

...

```

И файл логов изменений:

*doodles/core/components/doodles/docs/readme.txt*

```
--------------------
Extra: Doodles
--------------------
Version: 1.0
 
A simple demo extra for creating robust 3rd-Party Components in MODx Revolution.
```

Теперь давайте вернемся в скрипт *build.transport.php* и перед *$builder->pack()* добавим такие строки:

*doodles/_build/build.transport.php*

```
$modx->log(modX::LOG_LEVEL_INFO,'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => array(
        'source' => $sources['build'].'setup.options.php',
    ),
));
```

Как видите, вызывается метод `setPackageAttributes()`, который устанавливает атрибуты нашему упаковщику. Также тут есть новый для нас массив `setup-options`. У этого массива есть элемент с ключем `source` (как у резольвера), который указывает путь к PHP файлу (опять же, как у резольвера).

Создадим файл этот файл:

*doodles/_build/setup.options.php*

```
<?php

$output = '';
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $output = '<h2>Doodles Installer</h2>
<p>Thanks for installing Doodles! Please review the setup options below before proceeding.</p><br />';
        break;
    case xPDOTransport::ACTION_UPGRADE:
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}
return $output;
```

Знакомо выглядит, да? Этот кусок кода позволяет нам вывести `Параметры установки`, когда пользователь будет устанавливать пакет. Сейчас мы только выводим сообщение, чтобы сказать людям `Спасибо` за установку нашего дополнения.

Помните массив `$options` в нашем PHP резольвере? Если бы нам нужно поместить какие-то элементы форм в этот вывод, то они были бы найдены в этом массиве с таким же ключом (поле с именем `test` будет `$options['test']`). Это означает, что вы могли бы сделать резольвер, что будет обрабатывать поля формы, которые вы установите в настройках скрипта *setup.options.php*. Они будут выводиться при установке пакета и далее обрабатываться установщиком. Пример можно увидеть у компонента [Quip](http://github.com/splittingred/Quip/blob/develop/_build/resolvers/setupoptions.resolver.php).

Запустите билдер (файл *build.transport.php*) из адресной строки браузера и в папке *core/packages/* появится файл транспортного пакета *doodles-1.0-rc1.zip*. Этот файл можно загрузить в репозиторий дополнений MODX и потом установить через установщик.

**Внимание!**. Пока что, у меня всё работает, кроме того, что нормально не устанавливаются контроллер и пространство имён для меню.