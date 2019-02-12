#     Паттерн MVCL

OpenCart реализует паттер MVCL (Model-View-Controller-Language). Компонент **Language** реализует мультязычность и если перейти в директорию *catalog/* то мы увидим четыре папки - *model/*, *view/*, *controller/*, *language/*.

Структура адресов в OpenCart представляет собой классический вариант, который используется в современных PHP фреймворках. Если мы перейдём в каталог, то увидим следующий URL:

```
http://opencart.loc/index.php?route=product/category&path=20
```

Где `product/category` это путь контроллеру - *catalog/controller/product/category.php*.