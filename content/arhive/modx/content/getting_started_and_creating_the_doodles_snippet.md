# Начало работы и создание сниппета Doodles

* [Создание структуры каталогов](#structure)
* [Создание сниппета Doodles](#snippet)
    * [Настройка путей](#path)
    * [Создание базового класса ](#base-class)
    * [Настройка модели](#model)
    * [Скрипт парсинга XML схемы](#parsing-schema)
    * [Создание запроса](#building-query)
    * [Метод getChunk класса Doodles](#getchunk)
* [Итоги](#summary)

<div id="structure"></div>

## Создание структуры каталогов

Есть много способов как начать разрабатывать собственное дополнение - вы могли бы создавать ваши плагины, сниппеты и т. д. внутри сайта с MODX и затем упаковать его с помощью `PackMan` или можете разрабатывать ваш проект за пределами админ-панели MODX и отлеживать изменения в ваших файлов с помощью системы контроля версий.

Мы будем использовать второй способ и на это есть несколько причин:

* Это позволит выполнять разработку проекта из Git репозитория
* Что в свою очередь позволяет легко огранизовать совместную работу над проектом

В качестве среды разработки будет использоватся локальный сервер XAMPP под Windows, поэтому пути к директориям проекта у вас могут отличатся от тех которые указанны здесь - будьте внимательны. При работе с кодом удобней использовать IDE и как по мне, лучшим выбором на данный момент является PhpStorm.

Предварительно я создал новый сайт на MODX, который будет находится в папке *xampp/htdocs/modxtest.loc/*. Он будет доступен по адресу [http://modxtest.loc](http://modxtest.loc). Для этого мне нужно настроить хосты.

В начале необходимо внести правки в файл *hosts* - добавим следующую строку:

*C:\Windows\System32\drivers\etc\hosts*

```
127.0.0.1 modxtest.loc
```

А также в файл настроек хостов Apache *httpd-vhosts.conf*

*D:\xampp\apache\conf\extra\httpd-vhosts.conf*

```
<VirtualHost *:80>
  DocumentRoot "/xampp/htdocs/modxtest.loc"
  ServerName modxtest.loc
</VirtualHost>
```

Осталось перезагрузить веб-сервер и установить систему MODX, чтобы ваш сайт был доступен по адресу [http://modxtest.loc](http://modxtest.loc).

Запускаем терминал (под Windows удобней всего использовать Git Bash или эмулятор Console2) и перемещаеся в кореневую папку сайта, чтобы клонировать необходимый нам репозиторий. Делаем это следующей командой:

```
git clone https://github.com/splittingred/Doodles.git doodles
```

Git создаст для нас папку *doodles/* и загрузит туда всё содержимое репозитория.

Обычно пакет состоит из 3-х основных директорий.

* *_build/* - скрипты для сборки компонента в транспортный пакет. При этом эта директория не упаковывается в zip архив при создании пакета.
* *assets/* - файлы, которые должны быть доступны из интернет (CSS, JS, изображения). Здесь единственный неочевидный файл - это *connector.php*, который позволит нам использовать кастомные процессоры на нашей пользовательской странице админ-панели (CMP).
* *core/* - файлы, которые нужны для внутренней логики компонента.

При этом готовые дополнения в MODX Revolution разделены на два каталога, которые будут находится приблизительно в таких директориях: *core/components/myextra/* и *assets/components/myextra/*

В директории *core/components/doodles/* у нас уже имеется несколько каталогов:

* `controllers` — контроллеры для CMP;
* `docs` — содержит только файл истории изменений (*changelog.txt*), инструкции по установке (*readme.txt*) и файл лицензии (*license.txt*);
* `elements` — сниппеты, плагины, чанки и т.д.;
* `lexicon` — языковые файлы;
* `model` — наши классы, а также XML-файл схемы для наших кастомных таблиц базы данных;
* `processors` — все наши процессоры для CMP.

<div id="snippet"></div>

## Создание сниппета Doodles

У нас уже имеется сниппет, который мы почистим и будем переписывать его заново, чтобы лучше в нём разобратся. Добавим туда следующий код:

*doodles/core/components/doodles/elements/snippets/snippet.doodles.php*

```
<?php
$dood = $modx->getService('doodles','Doodles',$modx->getOption('doodles.core_path',null,$modx->getOption('core_path').'components/doodles/').'model/doodles/',$scriptProperties);
if (!($dood instanceof Doodles)) return '';
```

Здесь мы вызываем метод `getService()`. Это короткая запись, поэтому давайте, разобьем эту строку на несколько частей для более удобного чтения:

```
$defaultDoodlesCorePath = $modx->getOption('core_path').'components/doodles/';
$doodlesCorePath = $modx->getOption('doodles.core_path',null,$defaultDoodlesCorePath);
$doodles = $modx->getService('doodles','Doodles',$doodlesCorePath.'model/doodles/',$scriptProperties);
```

`getOption()` - позволяет получить указанную системную настройки по его ключу (первый параметр). В первой строчке мы получаем путь по-умолчанию к нашему сниппету `Doodles` (подразумеваем, что он находиться в папке `core/`). Полный путь будет: `/D:/xampp/htdocs/modxtest.loc/doodles/core/components/doodles/`

Далее мы передаём это в качестве запасного значения следующему вызову `getOption()`. Этот метод содержит 3 параметра: ключ с именем `doodles.core_path`, `null` и путь по-умолчанию, который мы создали строкой выше. В `getOption()` второй параметр - это массив для поиска ключа (который мы не используем, поэтому установили ему значение `null`), а 3-й параметр - это значение по умолчанию, на тот случай, если ключ не был найден.

Для чего это нужно? Дело в том, что пока мы разрабатываем приложение - мы пишем наш код в одном месте, при этом на продакшене - он уже будет находится в другом.

Давайте создадим статический сниппет в админке с именем `Doodles` и укажем путь к нашему файлу сниппету *snippet.doodles.php*, в который пока что поместим данный код:

```
<?php

/**
 * @var modX $modx
 */

$defaultDoodlesCorePath = $modx->getOption('core_path').'components/doodles/';
$doodlesCorePath = $modx->getOption('doodles.core_path',null,$defaultDoodlesCorePath);
return $doodlesCorePath;
```

В самом верху PHP файлов, вы можете наблюдать добавлены мною комментарии для phpDocumentor, которые позволят использовать автодополнение кода в PhpStorm.

И затем вызовем его:

```
[[!Doodles]]
```

То мы получим примерно такой путь к нашему компоненту `D:/xampp/htdocs/modxtest.loc/core/components/doodles/`. Это бы нам подошло, если бы пакет был уже создан и установлен. Но если мы только разрабатываем дополнение, при этом желаем отслеживать изменения и хранить историю в Git, то там нужно чтобы папки *core/* и *assset/* были в одном месте, как мы сделали ранее, тоесть клонировали заготовку *doodles/* в корень нашего приложения.

<div id="path"></div>

## Настройка путей

Чтобы вместо *core/components/doodles/* у нас стало *doodles/core/components/doodles/* следует добавить пару настроек в разделе *Настройки / Системные настройки*, которые позволят нам указать правильные (на данный момент) пути.

Раз

* **Ключ**: `doodles.core_path`
* **Значение**: `{base_path}doodles/core/components/doodles/`

Два

* **Ключ**: `doodles.assets_url`
* **Значение**: `{base_path}doodles/assets/components/doodles/`

Теперь если мы обновим страницу с созданным нами сниппетом `Doodles`, то мы увидим уже то что нам нужно - `D:/xampp/htdocs/modxtest.loc/doodles/core/components/doodles/`.

Теперь давайте разберёмся с последней строкой:

```
$dood = $modx->getService('doodles','Doodles',$doodlesCorePath.'model/doodles/',$scriptProperties);
```

Метод `getService()` загружает класс и создаёт экземпляр этого объекта (если он существует), тем самым устанавливает его в `$modx->doodles` (первый параметр). Больше информации о методе `getService()` можно найти [здесь](https://docs.modx.com/revolution/2.x/developing-in-modx/other-development-resources/class-reference/modx/modx.getservice). Но у нас пока ещё нет класса `Doodles` - давайте это поправим.

<div id="base-class"></div>

## Создание базового класса

В этом классе мы можем определить некоторые базовые пути, а также методы, которые будем использовать в нашем компоненте.

*doodles/core/components/doodles/model/doodles/doodles.class.php*

```
<?php

/**
 * @var modX $modx
 */

class Doodles {
  public $modx;
  public $config = array();
  function __construct(modX &$modx,array $config = array()) {
    $this->modx =& $modx;
    $basePath = $this->modx->getOption('doodles.core_path',$config,$this->modx->getOption('core_path').'components/doodles/');
    $assetsUrl = $this->modx->getOption('doodles.assets_url',$config,$this->modx->getOption('assets_url').'components/doodles/');
    $this->config = array_merge(array(
      'basePath' => $basePath,
      'corePath' => $basePath,
      'modelPath' => $basePath.'model/',
      'processorsPath' => $basePath.'processors/',
      'templatesPath' => $basePath.'templates/',
      'chunksPath' => $basePath.'elements/chunks/',
      'jsUrl' => $assetsUrl.'js/',
      'cssUrl' => $assetsUrl.'css/',
      'assetsUrl' => $assetsUrl,
      'connectorUrl' => $assetsUrl.'connector.php',
    ),$config);
  }
}
```

Здесь мы создаём объект класса, у которого имееется конструктор устанавливающий ссылку на объект `modX` в `$doodles->modx` - это нам позже пригодится. Также он создаём несколько базовых путей, которые мы можем использовать в массиве `$doodles->config` и хак с системными настройками приведёт нас к желаемому пути с нашим компонентом.

Теперь добавим несколько свойств по умолчанию в наш сниппет и теперь он приобретает следующий вид:

*doodles/core/components/doodles/elements/snippets/snippet.doodles.php*

```
<?php

/**
 * @var modX $modx
 */

$dood = $modx->getService('doodles','Doodles',$modx->getOption('doodles.core_path',null,$modx->getOption('core_path').'components/doodles/').'model/doodles/',$scriptProperties);
if (!($dood instanceof Doodles)) return '';

// setup default properties
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$output = '';

return $output;
```

Теперь мы можем использовать xPDO для получения записей из базы данных, но мы ещё не создали модель xPDO - давайте этим сейчас и займёмся.

<div id="model"></div>

## Настройка модели

xPDO использует PHP объекты для выполнения запросов к базе данных. На текущий момент она уже поддерживает несколько баз данных. Кроме этого, она позволяет превратить ваш SQL код в более компактный и читаемый вид.

Но чтобы сделать это, нам нужно добавить xPDO модель к нашему сниппету (через метод `$modx->addPackage`). Но вначале нам нужно создать модель, используя схему xPDO. Есть [отличный но длинный урок](https://docs.modx.com/xpdo/2.x/getting-started/creating-a-model-with-xpdo) по созданию xPDO модели, но мы сделаем это быстрее.

Создадим XML файл со следующим содержимым:

*doodles/core/components/doodles/model/schema/doodles.mysql.schema.xml*

```
<?xml version="1.0" encoding="UTF-8"?>
<model package="doodles" baseClass="xPDOObject" platform="mysql" defaultEngine="MyISAM" version="1.0">
  <object class="Doodle" table="doodles" extends="xPDOSimpleObject">
    <field key="name" dbtype="varchar" precision="255" phptype="string" null="false" default=""/>
    <field key="description" dbtype="text" phptype="string" null="false" default=""/>
    <field key="createdon" dbtype="datetime" phptype="datetime" null="true"/>
    <field key="createdby" dbtype="int" precision="10" attributes="unsigned" phptype="integer" null="false" default="0" />
    <field key="editedon" dbtype="datetime" phptype="datetime" null="true"/>
    <field key="editedby" dbtype="int" precision="10" attributes="unsigned" phptype="integer" null="false" default="0" />
    <aggregate alias="CreatedBy" class="modUser" local="createdby" foreign="id" cardinality="one" owner="foreign"/>
    <aggregate alias="EditedBy" class="modUser" local="editedby" foreign="id" cardinality="one" owner="foreign"/>
  </object>
</model>
```

Если это ваше первое знакомство с xPDO или же вы не понимаете как с ней работать, то вы можете рассмотреть еще [несколько примеров файлов XML схемы](https://docs.modx.com/xpdo/2.x/getting-started/creating-a-model-with-xpdo/defining-a-schema/more-examples-of-xpdo-xml-schema-files).

В первой строке:

```
<model package="doodles" baseClass="xPDOObject" platform="mysql" defaultEngine="MyISAM">
```

Мы говорим схеме что наш пакет xPDO называется `doodles`. Это то, на что мы будем ссылаться в нашем  вызове `addPackage()`. `baseClass` указывает на то что базовый класс для всех объектов определён здесь как `xPDOObject`, и что схема сделана для MySQL. И в конце мы указываем систему хранения данных MySQL - `MyISAM`

```
<object class="Doodle" table="doodles" extends="xPDOSimpleObject">
```

В разделе `object` схемы xPDO определяется таблица в БД. Эта строка указывает xPDO имя вызываемой таблицы `'{table_prefix}doodles'`. Как правило, префикс таблицы после установки MODX это `modx`, тоесть в итоге это конвертируется в `modx_doodles`. После чего мы сообщаем что расширяем `xPDOSimpleObject`. `xPDOObject` это базовый объект для любых классов таблиц в xPDO. `xPDOSimpleObject` расширяет его, но добавляет поле `id` и устанавливает auto-increment. Поэтому, если вам нужно поле `id` в таблице, тогда стоит выбрать `xPDOSimpleObject`.

```
<field key="name" dbtype="varchar" precision="255" phptype="string" null="false" default=""/>
<field key="description" dbtype="text" phptype="string" null="false" default=""/>
<field key="createdon" dbtype="datetime" phptype="datetime" null="true"/>
<field key="createdby" dbtype="int" precision="10" attributes="unsigned" phptype="integer" null="false" default="0" />
<field key="editedon" dbtype="datetime" phptype="datetime" null="true"/>
<field key="editedby" dbtype="int" precision="10" attributes="unsigned" phptype="integer" null="false" default="0" />
```

Здесь определяются поля таблицы.

```
<aggregate alias="CreatedBy" class="modUser" local="createdby" foreign="id" cardinality="one" owner="foreign"/>
<aggregate alias="EditedBy" class="modUser" local="editedby" foreign="id" cardinality="one" owner="foreign"/>
```

Тут создается связь с другими объектами xPDO (в данном случае с объектом пользователей). Здесь мы сообщаем xPDO, что поле `createdby` привязано к `modUser`, а поле `editedby` привязанно к другому `modUser`. Теперь давайте перейдём к парсингу этого XML файла и к созданию наших классов и связей.

<div id="parsing-schema"></div>

## Скрипт парсинга XML схемы

Самое время взглянуть на наш подзабытый каталог *_build*. Создадим файл парсера:

*doodles/_build/build.schema.php*

```
<?php
require_once dirname(__FILE__).'/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
$sources = array(
  'model' => $modx->getOption('doodles.core_path').'model/',
  'schema_file' => $modx->getOption('doodles.core_path').'model/schema/doodles.mysql.schema.xml'
);
$manager= $modx->getManager();
$generator= $manager->getGenerator();

if (!is_dir($sources['model'])) { $modx->log(modX::LOG_LEVEL_ERROR,'Model directory not found!'); die(); }
if (!file_exists($sources['schema_file'])) { $modx->log(modX::LOG_LEVEL_ERROR,'Schema file not found!'); die(); }
$generator->parseSchema($sources['schema_file'],$sources['model']);
$modx->addPackage('doodles', $sources['model']); // add package to make all models available
$manager->createObjectContainer('Doodle'); // created the database table
$modx->log(modX::LOG_LEVEL_INFO, 'Done!');
```

В основном этот файл парсит вашу XML схему и создаёт xPDO классы и связи (PHP представление XML файла) для вашего компонента. Но так просто он не запустится, так как для его работы ещё потребуется файл *doodles/_build/build.config.php*. Давайте его создадим:

*doodles/_build/build.config.php*

```
<?php

define('MODX_BASE_PATH', 'D:/xampp/htdocs/modxtest.loc/');
define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

define('MODX_BASE_URL','/modx/');
// There isn't a core URL
// define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');
define('MODX_ASSETS_URL', MODX_BASE_URL . 'assets/');
```

Возможно вам понадобится понадобятся изменить эти пути, чтобы указать место где установлен ваш сайт MODX.

Теперь нужно запустить из адресной строки браузера файл *_build/build.schema.php*, в моём случае  [это](http://modxtest.loc/doodles/_build/build.schema.php).

Если всё гуд, то у вас должно получится такое сообщение:

```
[2016-11-27 08:15:05] (INFO @ D:\xampp\htdocs\modxtest.loc\doodles\_build\build.schema.php : 22)

Done!
```

В итоге у вас должны сгенерироваться следующие файлы:

```
doodles/core/components/doodles/model/doodles/doodle.class.php
doodles/core/components/doodles/model/doodles/mysql/doodle.class.php
doodles/core/components/doodles/model/doodles/mysql/doodle.map.inc.php
```

Давайте ещё сделаем корректировку нашего базового класса `Doodles`, чтобы автоматически добавлялся xPDO пакет всякий раз при загрузке этого класса. Нам необходимо добавить дополнительную строку после нашего массива `$this->config = array_merge` в конце конструктора:

*doodles/core/components/doodles/model/doodles/doodles.class.php*

```
$this->modx->addPackage('doodles',$this->config['modelPath']);
```

Это сообщает xPDO, что мы хотим добавить `doodles` в xPDO пакет, позволяя нам делать запросы к пользовательской таблице.

Наш сниппет до сих пор выглядит таким образом:

*doodles/core/components/doodles/elements/snippets/snippet.doodles.php*

```
<?php
/**
 * @var modX $modx
 */

$dood = $modx->getService('doodles','Doodles',$modx->getOption('doodles.core_path',null,$modx->getOption('core_path').'components/doodles/').'model/doodles/',$scriptProperties);
if (!($dood instanceof Doodles)) return '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$output = '';

return $output;
```

Всё что мы сейчас делаем, так это устанавливаем объект  класса `Doodles` в переменную `$dood` и настраиваем некоторые значения по-умолчанию для свойств, которые мы будем использовать в будущем. `$scriptProperties` - это массив всех передаваемых в сниппет параметров. Метод `getOption()`, который мы здесь вызываем, парсит этот массив, чтобы найти необходимые параметры и если они не установлены, то берёт свойства по-умолчанию.

<div id="building-query"></div>

## Создание запроса

Давайте добавим данный код в конце нашего сниппета

```
$doodles = $modx->getCollection('Doodle');
return $output = count($doodles);
```

В этом примере мы извлекаем коллекцию объектов `Doodle`. Большую часть времени при использовании `xPDO.getCollection`, вы будете извлекать встроенные объекты MODX (например, страницы `modResource`, шаблоны `modTemplate` и т. д.), так что иногда очень удобно держать открытым файл *core/model/schema/modx.mysql.schema.xml*, где вы можете просмотреть имена объектов.

Сейчас мы хотим получить массив объектов `Doodles`, тоесть кучу строк из базы данных. Запускаем страницу, где вы вызываете данный сниппет в браузере и вы должны получить пока что `0`.

Это нормально, потому что в таблице пока что нет никаких данных. Давайте добавим пару строк - мне лично удобно использовать для этих целей программу Navicat, ну а вы можете сделать это в phpMyAdmin или что-то ещё.

Открываем таблицу `modx_doodles` в вашей БД и добавим несколько строк. Это должно выдать вам какие-то данные. Допустим вы добавили 2 ряда. И теперь если вы снова запустите ваш сниппет, то вы уже получите `2`.

Если ваш запрос отработал, тогда мы можем усложнить его, для это воспользуемся использовать класс `xPDOQuery` для создания более сложных запросов. Добавьте команду сортировки:

```
$c = $modx->newQuery('Doodle');
$c->sortby($sort,$dir);
$doodles = $modx->getCollection('Doodle',$c);
return $output = count($doodles);
```

<div id="getchunk"></div>

## Метод getChunk класса Doodles

Можно добавлять пару вспомогательных методов к базовому классу `getChunk()`, которые позволяют использовать чанки основанные на файлах. Откроем класс `Doodles` и добавим эти методы:

*doodles/core/components/doodles/model/doodles/doodles.class.php*

```
public function getChunk($name,$properties = array()) {
  $chunk = null;
  if (!isset($this->chunks[$name])) {
    $chunk = $this->modx->getObject('modChunk',array('name' => $name));
    if (empty($chunk) || !is_object($chunk)) {
      $chunk = $this->_getTplChunk($name);
      if ($chunk == false) return false;
    }
    $this->chunks[$name] = $chunk->getContent();
  } else {
    $o = $this->chunks[$name];
    $chunk = $this->modx->newObject('modChunk');
    $chunk->setContent($o);
  }
  $chunk->setCacheable(false);
  return $chunk->process($properties);
}

private function _getTplChunk($name,$postfix = '.chunk.tpl') {
  $chunk = false;
  $f = $this->config['chunksPath'].strtolower($name).$postfix;
  if (file_exists($f)) {
    $o = file_get_contents($f);
    $chunk = $this->modx->newObject('modChunk');
    $chunk->set('name',$name);
    $chunk->setContent($o);
  }
  return $chunk;
}
```

Эти методы будут искать чанки в папке *doodles/core/components/doodles/elements/chunks/* с постфиксами `.chunk.tpl` в нижнем регистре. Если чанки не найдутся в файловой системе, то будет продолжен поиск в MODX. Поэтому, когда мы вызываем:

```
$o = $dood->getChunk('hello',array('name' => 'Joe'));
```

Этот код установит для `$o` содержимое *doodles/core/components/doodles/elements/chunks/hello.chunk.tpl* со свойством `[[+name]]` и значением `Joe`. Это позволит вам отредактировать ваши чанки в IDE, а не в админке MODX. Это также позволит вам упаковать ваш компонент без необходимости создания чанков по-умолчанию в админке MODX во время установки пакета (которые как правило перезаписываются при обновлении компонента).

Создадим файл чанка и вставим туда следующий код:

*doodles/core/components/doodles/elements/chunks/rowtpl.chunk.tpl*

```
<li><strong>[[+name]]</strong> - [[+description]]</li>
```

Теперь добавим цикл `foreach` после вашего запроса, но перед `return` в cниппете:

```
foreach ($doodles as $doodle) {
  $doodleArray = $doodle->toArray();
  $output .= $dood->getChunk($tpl,$doodleArray);
}
```

Цикл `foreach` перебирает все объекты `Doodle`, которые мы получаем с помощью `getCollection()` и создаёт массив из их значений методом `toArray()`. Далее мы используем метод `getChunk()`, в который передаём название чанка (`$tpl`) и массив данных, который мы передаём в чанк. Заносим и конкатенируем полученный результат в переменную `$output`, которую в итоге возвращаем пользователю. Таким образом у нас должен получится не нумерованный список с данными из БД.

Вы можете внести любые изменения в чанк, но кроме этого пользователи могут самостоятельно переопределять его, передавая имя чанка с помощью параметра `&tpl` во время вызова сниппета.

В итоге наш сниппет теперь выглядит так:

*doodles/core/components/doodles/elements/snippets/snippet.doodles.php*

```
<?php

/**
 * @var modX $modx
 * @var Doodle $doodle
 */

$dood = $modx->getService('doodles','Doodles',$modx->getOption('doodles.core_path',null,$modx->getOption('core_path').'components/doodles/').'model/doodles/',$scriptProperties);
if (!($dood instanceof Doodles)) return '';

// setup default properties
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

// build query

$c = $modx->newQuery('Doodle');
$c->sortby($sort,$dir);
$doodles = $modx->getCollection('Doodle',$c);

$output = '';

// iterate

foreach ($doodles as $doodle) {
  $doodleArray = $doodle->toArray();
  echo '<pre>';
  print_r($doodleArray) ;
  echo '</pre>';
  $output .= $dood->getChunk($tpl,$doodleArray);
}

return($output) ;
```

Для наглядности я добавил временный код, который будет выводить в форматированном виде полученные массивы.

Попробуем переопределить чанк по время вызова сниппета.

```
[[!Doodles? &tpl=`custom`]]
```

И теперь нужно создать чанк `custom` примерно с таким содержимым:

```
<p><strong>[[+name]]</strong></p>
```

<div id="summary"></div>

## Итоги

В итоге мы загружаем собственный базовый класс из наших системных настроек, добавляем пользовательский xPDO пакет, извлекаем данные из созданной нами таблицы и выводим их через чанк.