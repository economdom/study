# Установка и удаление плагинов

Установка выполняется таким образом

```
npm install gulp-plugin1 gulp-plugin2 gulp-plugin3 --save-dev
```

Можно также устанавливать каждый плагин отдельной командой, но первый способ короче.

```
npm install gulp-plugin1 --save-dev
npm install gulp-plugin2 --save-dev
npm install gulp-plugin3 --save-dev
```

Флаги:

* `--save` - запишет модуль в `dependencies` в *package.json*, тоесть модули которые используются для работы приложения
* `--save-dev` - запишет модуль в `devDependencies` в *package.json*, тоесть модули которые используются только для разработки
* `--verify` - позволяет проверить не занесён ли текущий плагин или модуль в чёрный список.

Удаление плагинов выполяется следующей командой

```
npm uninstall <package name>
```