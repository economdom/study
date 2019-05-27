# Создание галереи изображений с оверлей эффектом

В этом уроке мы попрактикуемся в работе с DOM создавая простую галлерею изображений с оверлей эффектом на обычном JavaScript без применения каких либо JavaScript библиотек или фреймворков.

## Добавление обработчика событий

Для начала нам необходимо создать узел (нода), целью которого будет определённая область, а затем добавить обработчик для узла, который будет следить за кликами и если на одном из изображений будет произведен клик - должен будет исполнится некоторый код.

Создадим разметку.

*index.html*

```
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index page</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <style>
  ul.my-image-list{
    margin: 0;
    padding: 0;
  }
  ul.my-image-list li{
    list-style-type: none;
    float: left;
    margin-right: 15px;
  }
  </style>
</head>
<body>

<div class="container">
  <h1>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit, recusandae.</h1>
  <hr>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi dolorum quo doloribus explicabo, aliquam eos aspernatur deserunt fuga vitae vero minima deleniti quas, consequuntur tenetur molestiae accusantium. Libero assumenda nulla reiciendis fugiat esse non temporibus, optio nam, aut dolorem repellendus magnam cumque molestiae, est recusandae quae. Unde nulla dolorem rem quia praesentium expedita, molestias ipsam at aliquid aspernatur accusantium ullam inventore, odio fuga doloribus natus, suscipit aliquam voluptas quod, aut provident dicta possimus nobis. Doloribus quia, nam repellendus, et animi quibusdam tempore necessitatibus atque sint fugit ducimus reiciendis accusamus inventore iusto neque aliquid. Autem laudantium reprehenderit suscipit quam, praesentium officia.</p>
  <div class="row">
    <div class="col-md-12 one">
      <ul class="my-image-list">
        <li><img class="thumbnail" src="img/01_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/02_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/03_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/04_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/05_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/06_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
        <li><img class="thumbnail" src="img/07_tn.jpg" alt="Lorem ipsum dolor sit amet."></li>
      </ul>
    </div>
  </div>
</div>

<script type="text/javascript" src="main.js"></script>

</body>
</html>
```

Начнём с создания с создания самоисполняющиеся функции, которая защитит все наши переменные от всего остального, что выполняется на нашей странице.

* Отбираем необходимый узел
* Создаём обработчик события с помощью функции `addEventListener()` на событие клик, возникающее на этом узле. Когда это событие произойдёт, нам нужно исполнить функцию, параметрами которой станет событие - сюда передаём значение `false`, это позволит нам быть уверенным что событие будет верно обработанно. Это означает что нам нужно событие расположенное внутри упорядоченного списка, а не за его пределами.
* Выведем событие в консоль для того чтобы видеть что происходит
* Обратите внимание что вне зависимости от того на чём мы кликаем, происходит вывод информации о событии в консоль. Нам нужно отследить только клик на изображении. Если мы развернём список свойств в консоли, то мы можем найти то которое нас интересует, а именно - `target`. Мы можем развернуть данное свойство и далее нам понадобится свойсво `tagName`. Мы можем обратится к данному свойству и если оно окажется изображением, то мы сможем выполнить свою функцию - пока что просто вывод сообщения в консоль.

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

   if(e.target.tagName === 'IMG'){
      console.log(e);
    } // target name is an image

  }, false); // image is clicked

})(); // self executing function
```

Таким образом, события появляются только если мы кликаем внутри неупорядоченного списка. Мы видим что с помощью создания узла, мы можем очень точно обратится к элементам, находящимся внутри этого узла

## Создание оверлея с помощью JavaScript

* Удалим команду `console.log()`
* Создаём переменную `myOverlay` для создания нового элемента с помощью `createElement()` - создадим новый `div` в нашем документе.
* Чтобы упростить процедуру обращения к этому элементу, создадим этот элемент с ID `#overlay`
* Теперь нужно добавить его в текущий документ, чтобы его можно было добавить в тег `<body>` и применив метод `appendChild` передав наш `overlay`. Это приведет к тому что при клике на изображение к концу тега `<body>` добавится этот `overlay`

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

    } // target name is an image
    
  }, false); // image is clicked

})(); // self executing function
```

Теперь если перейти в браузер и кликнуть по одному из изображений, то мы увидим что у нас добавляется новый блок `<div id="overlay"></div>`

* Добавим несколько стилей для данного блока непосредственно в файл скриптов, потому что это курс по JavaScript, хотя правильней бы было сделать это в таблице стилей
* Ширина оверлея должна быть ровна внутренней ширине окна, для этого используем свойство `window.innerWidth`. Учитывая особенности CSS необходимо добавить единицы метрической системы. Тоже самое нужно сделать и с высотой.
* Если мы сейчас кликнем по изображению, то мы увидим что наш код работает, но если мы используем скролл, то оверлей находится всегда в верхней части страницы, а нам нужно чтобы он совпадал с позицией окно. Для этого нам нужно добавить `myOverlay.style.top`. Таким образом мы задали правило для скроллинга.

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

    } // target name is an image
    
  }, false); // image is clicked

})(); // self executing function
```

## Добавление изображения

Теперь нам нужно расположить изображение поверх оверлея, для того чтобы сделать это нам нужно определить где находится наше изображение. Все большие изображения размещенны в той же папки что и остальные изображения без суфикса `_tn` (thumbnail).

* Для начала нужно найти имя элемента на котором я кликнул, того которое мы сохранили в объекте события. В условии мы можем выяснить `tagName` изображение запросив объект события и свойство `tagName`. В данном условии мы создадим новую переменную, которую назовём `imageSrc` и присвоим ей значение `e.target.src` что позволит нам получить доступ к атрибуту `src` изображения, по которому был произведет клик.
* Выведем сообщение в консоль

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      console.log(e.target);
      var imageSrc = e.target.src;

    } // target name is an image
    
  }, false); // image is clicked

})(); // self executing function
```

Переключимся в браузер и запускаем консоль, затем кликаем по изображению. Теперь мы видим что по клику мы получаем объект, указываеющий на это изображение.

* Теперь создадим новую переменную, где создадим новый элемент `img` с помощью метода `createElement()` и присвоим ему `id` и `src`. Атрибут `src` будет равен нашему старому пути с методом `substr()`, который берёт строку и возвращает её часть. Первым аргументом мы указываем `0`, тоесть нам нужно производить поиск с нулевой позиции. Вторым параметром мы указываем длинну имени обрезанного изображения `-7`, тоесть вырезаем последние 7 символов с конца - в нашем случае это `_tn.jpg` и в конце нам нужно добавить расширение для нашего изображения.
* Придадим стили с помощью JavaScript
* Добавляем новое изображение к оверлею с помощью метода `appendChild()`

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';
      myOverlay.appendChild(largeImage);

    } // target name is an image
    
  }, false); // image is clicked

})(); // self executing function
```

Теперь если мы перейдём в браузер и кликнем по нашему изображению, то мы увидим что отрывается увеличенное изображение, которое пока что не выровненное по центру.

## Изменение размера изображения в DOM

Нам нужно изменить размеры изображения, тоесть обрезать его, если оно сильно большое в исходном варианте. Но проблема состоит в том что это нельзя сделать до окончательной загрузки изображения и если попытатся рассчитать размеры изображения до загрузки, то у изображения не будет размеров. Поэтому нам нужно создать загрузчик, который будет отслеживать момент загрузки изображения и затем рассчитывает его размеры.

* Добавим обработчик событий. Нам нужно установить в качестве объекта наше изображение и привязать к нему обработчик событий - будем искать событие `load`, а после загрузки изображения нам нужно вызвать функцию
* Проверяем является ли изображени слишком высоким или широким, чтобы уменьшить размер, если оно превышает внутреннюю высоту окна и если это так нам нужно подсчитать соотношение размеров окна и того изображения, которое мы пытаемся загрузить. Если в условии мы получаем `true` значит оно слишком высокое и нам нужно преобразовать высоту во сколько раз, сколько было вычисленно в настоящий момент
* То же самое проделываем и для ширины - если значение шире, тогда там просто нужно отредактировать идентификаторы некоторых переменных

Подсчитанные значения можно определить в `myImage`, чтобы можно было их использовать для новых оверлейов простым добавлением дочерних элементов для `largeImage`

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';

      // wait until the image has loaded
      largeImage.addEventListener('load', function(){

         // resize if taller
        if(this.height > window.innerHeight){
          this.ratio = window.innerHeight / this.height;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        // resize if wider
        if(this.width > window.innerWidth){
          this.ratio = window.innerWidth / this.width;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        myOverlay.appendChild(largeImage);

      });

    } // target name is an image
    
  }, false); // image is clicked

})(); // self executing function
```

## Центрирование изображения

Для этого создадим отдельную функцию, которую напишем в самом конце, перед закрытием самозапускающийся функции. Это функция будет ожидать передаваемое ей изображение.

* Нам нужно посчитать разницу между шириной окна и шириной изображения. Разделим полученное значение на два и получим растояние, на которое нужно переместить объект - оно переместится на половину разницы размеров - именно таким образом его можно разместить в центре.
* Тоже самое делаем и с высотой изображения
* Добавляем стили к нашему изображению с помощью JavaScript
* Вызываем нашу функцию `centerImage()` куда передаём ссылку на выбранное изображение

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';

      // wait until the image has loaded
      largeImage.addEventListener('load', function(){

         // resize if taller
        if(this.height > window.innerHeight){
          this.ratio = window.innerHeight / this.height;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        // resize if wider
        if(this.width > window.innerWidth){
          this.ratio = window.innerWidth / this.width;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        centerImage(this);
        myOverlay.appendChild(largeImage);

      });

    } // target name is an image
    
  }, false); // image is clicked

  // center image
  function centerImage(theImage){
    var myDifX = (window.innerWidth - theImage.width) / 2;
    var myDifY = (window.innerHeight - theImage.height) / 2;

    theImage.style.top = myDifY + 'px';
    theImage.style.left = myDifX + 'px';

    return theImage;
  }

})(); // self executing function
```

## Обработка кликов

Нам нужно сделать так чтобы изображение и оверлей исчезал. Для этого нам нужно написать обработчик события, который бы закрывал наше изображение при клике на нём.

* Используем обработчик события, который бы вызывался после загрузки большого изображения. Нам нужно убедится что в нужный  момент ей передаётся значение `false`
* Следует проверить что всё будет происходить только в случае существования оверлея. Если оверлей существует нам нужно удалить этот оверлей. Для этого нужно воспользоватся функцией `removeChild()`. Следует помнить что функция `removeChild()` не воздействует на сам узел - она срабатывает только в случае указания родительского узла.

*main.js*

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';

      // wait until the image has loaded
      largeImage.addEventListener('load', function(){

         // resize if taller
        if(this.height > window.innerHeight){
          this.ratio = window.innerHeight / this.height;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        // resize if wider
        if(this.width > window.innerWidth){
          this.ratio = window.innerWidth / this.width;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        centerImage(this);
        myOverlay.appendChild(largeImage);

      }); // image has loaded

      largeImage.addEventListener('click', function(){
        if(myOverlay){
          myOverlay.parentNode.removeChild(myOverlay);
        }
      }, false)

    } // target name is an image
    
  }, false); // image is clicked

  // center image
  function centerImage(theImage){
    var myDifX = (window.innerWidth - theImage.width) / 2;
    var myDifY = (window.innerHeight - theImage.height) / 2;

    theImage.style.top = myDifY + 'px';
    theImage.style.left = myDifX + 'px';

    return theImage;
  }

})(); // self executing function
```

Важно помнить что функция `removeChild()` всегда требует указания родительского узла.

## Настройка скроллинга

Если кликнуть по одному из изображений и прокрутить окно, но можно заметить что оверлей не перемещается при прокручивании

* Нам нужно привязатся к событию, когда кто-то прокручивает окно - нам нужно найти событие `scroll` и как только оно произойдёт - должна запуститься функция. Для правильной работы устанавливаем `false` в возвращаемое значение
* Нужно убедится что `myOverlay` существует, потому что иначе нам ничего не нужно изменять
* Добавляем стили через JavaScript, которые решат данную проблему

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';

      // wait until the image has loaded
      largeImage.addEventListener('load', function(){

         // resize if taller
        if(this.height > window.innerHeight){
          this.ratio = window.innerHeight / this.height;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        // resize if wider
        if(this.width > window.innerWidth){
          this.ratio = window.innerWidth / this.width;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        centerImage(this);
        myOverlay.appendChild(largeImage);

      }); // image has loaded

      // close image if click to large image
      largeImage.addEventListener('click', function(){
        if(myOverlay){
          myOverlay.parentNode.removeChild(myOverlay);
        }
      }, false);

      // setting scroll
      window.addEventListener('scroll', function(){
        if(myOverlay){
          myOverlay.style.top = window.pageYOffset + 'px';
          myOverlay.style.left = window.pageXOffset + 'px';
        }
      }, false);

    } // target name is an image
    
  }, false); // image is clicked

  // center image
  function centerImage(theImage){
    var myDifX = (window.innerWidth - theImage.width) / 2;
    var myDifY = (window.innerHeight - theImage.height) / 2;

    theImage.style.top = myDifY + 'px';
    theImage.style.left = myDifX + 'px';

    return theImage;
  }

})(); // self executing function
```

## Обнаружение изменения размеров окна

Если мы изменить/уменьшим размеры окна, а затем попробуем кликнуть по изображению, после чего снова изменим/увеличим размеры окна, то мы увидим что оверлей не развернулся на полную ширину окна.

* Добавим обработчик событий для другого события в окне, в этом раз это `resize`, после чего определяем функцию и передаём в обработчик `false` для стабильной работы
* Убеждаемся в том что оверлей существует и если это так, то изменяем это внесением правок в стили CSS с помощью JavaScript. Нам нужно изменить размеры оверлея, которые будут ровнятся ширине и высоте окна браузера, а также нам нужно изменить расположение нижней и верхней границы оверлея.
* Далее нужно отцентрировать изображение - для этого просто выполним уже созданную функцию `centerImage()` передав в неё `largeImage`
* Когда мы удаляем оверлей, нам нужно убедится что в окне больше не отслеживаются события, которые мы создали. Для этого используем функцию `removeEventListener()`, в которую мы передаём необходимые события. Этот блок кода нужно разместить внутри обработчика, который отслеживает клик по большому изображению. Это просто функция очистки - всё будет работать и без неё, но так правильнее.

```
(function(){

  // Selecting our node
  var myNode = document.querySelector('ul.my-image-list');

  myNode.addEventListener("click", function(e){

    if(e.target.tagName === 'IMG'){

      var myOverlay = document.createElement('div');
      myOverlay.id = 'overlay';
      document.body.appendChild(myOverlay);

      // set up overlay styles
      myOverlay.style.position = 'absolute'; 
      myOverlay.style.top = '0';
      myOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
      myOverlay.style.cursor = 'pointer';

      // resize and position overlay
      myOverlay.style.width = window.innerWidth + 'px';
      myOverlay.style.height = window.innerHeight + 'px';
      myOverlay.style.top = window.pageYOffset + 'px';
      myOverlay.style.left = window.pageXOffset + 'px';

      // get src large image and output to overlay
      var imageSrc = e.target.src;
      var largeImage = document.createElement('img');
      largeImage.id = 'largeImage';
      largeImage.src = imageSrc.substr(0, imageSrc.length - 7) + '.jpg';
      largeImage.style.display = 'block';
      largeImage.style.position = 'absolute';

      // wait until the image has loaded
      largeImage.addEventListener('load', function(){

         // resize if taller
        if(this.height > window.innerHeight){
          this.ratio = window.innerHeight / this.height;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        // resize if wider
        if(this.width > window.innerWidth){
          this.ratio = window.innerWidth / this.width;
          this.height = this.height * this.ratio;
          this.width = this.width * this.ratio;
        }

        centerImage(this);
        myOverlay.appendChild(largeImage);

      }); // image has loaded

      // close image if click to large image
      largeImage.addEventListener('click', function(){
        if(myOverlay){
          // remove unuses events
          window.removeEventListener('resize', window, false);
          window.removeEventListener('scroll', window, false);
          myOverlay.parentNode.removeChild(myOverlay);
        }
      }, false);

      // setting scroll
      window.addEventListener('scroll', function(){
        if(myOverlay){
          myOverlay.style.top = window.pageYOffset + 'px';
          myOverlay.style.left = window.pageXOffset + 'px';
        }
      }, false);

      window.addEventListener('resize', function(){
        if(myOverlay){
          myOverlay.style.width = window.innerWidth + 'px';
          myOverlay.style.height = window.innerHeight + 'px';
          myOverlay.style.top = window.pageYOffset + 'px';
          myOverlay.style.left = window.pageXOffset + 'px';
          centerImage(largeImage);
        }
      }, false);

    } // target name is an image
    
  }, false); // image is clicked

  // center image
  function centerImage(theImage){
    var myDifX = (window.innerWidth - theImage.width) / 2;
    var myDifY = (window.innerHeight - theImage.height) / 2;

    theImage.style.top = myDifY + 'px';
    theImage.style.left = myDifX + 'px';

    return theImage;
  }

})(); // self executing function
```