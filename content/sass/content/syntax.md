# Синтаксис

Вы можете выбрать один из двух синтаксисов, который вам ближе - упрощенный (SASS) и развернутый CSS-подобный (SCSS).

* **SASS** - cамый старый вариант написания SASS - это синтаксис отступов. Расширение файлов для такого синтаксиса - *.sass*

*sample.sass*

```
$font-stack: Helvetica, sans-serif
$primary-color: #333

body
  font: 100% $font-stack
  color: $primary-color
```

* **SCSS** - это синтаксис, расширяющий синтаксис CSS. SCSS пишется как обычный CSS, но расширен дополнительными возможностями Sass. Расширение файлов с SCSS синтаксисом - *.scss*.

*sample.scss*

```
$font-stack: Helvetica, sans-serif;
$primary-color: #333;

body {
  font: 100% $font-stack;
  color: $primary-color;
}
```