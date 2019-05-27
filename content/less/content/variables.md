# Переменные

Переменные определяют содержимое значений, которые будут исопользоватся в нескольких местах в стилях CSS. Переменные могут иметь различные типы значений - цвет, строка, число. Переменные в LESS больше похожи на константы. Это значит, что они, в отличие от переменных, могут быть определены только один раз.

```
@myColor: #535353;
@myStringVar: " with an appended string";
@myFontSize: 24px;
@thinBorder: 4px solid @myColor1;
@paddingVar: 15px 15px 15px 15px;

h1, h2 {
  color: @myColor;
}

#core {
  font-size: @myFontSize;
  border: @thinBorder;
  padding: @paddingVar;
}

#core:after {
  content: @myStringVar;  
}

#core {
    color: @baseColor;
}
```

Получим

```
h1,
h2 {
  color: #535353;
}
#core {
  font-size: 20px;
  border: 4px solid #535353;
  padding: 15px 15px 15px 15px;
}
#core:after {
  content: " With an appended string";
}
```

## Область видимости переменных

Область видимости переменных описывает места, где они доступны. Если вы определите переменную в самом начале LESS файла, то она будет доступна для любого кода написанного после.

Также можно определять переменную внутри CSS правила. В этом случае переменные не будут доступны вне этого правила, они могут быть использованы локально.

```css
a {
  @color: #ff9900;
  color:@color;
}
button {
  background: @color;
}
```

В этом примере LESS не будет сконвертирован из-за ошибки, color не определена для использования внутри элемента button. Если переменная объявлена вне элемента и внутри другого элемента, то она будет доступна только локально.

```css
@color: #222222;
a {
  @color: #ffffff;
  color:@color;
}
button {
  background: @color;
}
```