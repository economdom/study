# Изменения в копилке

**Копилка** - это место, где мы временно можем сохранить изменения без необходимости их коммитить в репозиторий. Копилка не является частью репозитория, буфера или рабочей директории, это специальная 4-я  часть Git, отделённая от других и изменения, которые там хранятся очень похожие на коммиты и хранятся похожим образом, это слепки изменений, но у них нет SHA связанных с ними.

## Сохранение изменений в копилке

Давайте сделаем изменения и отправим его в копилку.

* Перейдём в бранч `shorten_title`, где нет всех тех изменений, которые мы внесли `text_edits`
* Сделаем изменения в файле *index.html*
* Пробуем переключить бранч и мы получаем сообщение, что нужно сохранить изменения, перед тем как сделать это

```bash
git checkout shorten_title
vim index.html
git status
git checkout master
```

Этот как раз тот случай, когда вы сделали изменения и хотите переключится на другой бранч, но при этом пока что не хотите коммитить изменения, а вместо этого вы хотите отправить их в копилку.

Для того чтобы сохранить изменения в копилку мы пишем:

```bash
git stash save "Change title"
git status
git log --oneline
```

Внутри двойных кавычек мы пишем сообщение, при этом флаг `-m` не требуется. Затем если мы проверим статус, но мы не увидим изменённых файлов, а если посмотрим логи, то убедимся ещё и в том что мы находимся на том коммите, на котором мы находились до того как мы отправили изменения в копилку.

Таким образом мы просто спрятали изменения, но на самом деле Git просто запустил `git reset hard HEAD`. Также если у вас есть не отслеживаемые файлы, вы также можете их включить при помощи опции `--include-untracked`. Но обычно в таком случае будут включатся вещи, которых находятся в рабочей директории, тоесть отслеживаемые файлы, потому что это вещи, которые могут вызвать конфликт и Git не даст нам возможности переключится.

## Просмотр изменений из копилки

Для работы с копилкой нужно использовать `git stash` и далее название требуемой команды.

Для того чтобы посмотреть изменения следует использовать команду `list`

```bash
git stash list
```

Нам отобразится примерно такое сообщение - `stash@{0}: On shorten_title: Change title` и первая часть сообщения, тоесть `stash@{0}`, это как раз то что понадобится нам для того чтобы обратится к сохранённому изменению в копилке. Далее отображается название бранча, где были сделанны изменения в копилке, потому что копилка доступна даже в том случае, если мы переключились на другой бранч. Копилка доступна всё время и это особенно полезно, когда вы хотите достать из неё данные, например вы сделали изменения, а затем вы поняли что хотите коммитить эти изменения не в этот бранч, тогда вы можете отправить изменения в копилку, перейти в другой бранч, а затем забрать изменения из копилки.

Следующая команда покажет нам некую статистику сделанных изменений

```bash
git stash show stash@{0}
```

Если же нам нужно больше информации, то нам нужна опция `-p`, которая показывает её нам в виде патча. Патч - это раздел кода, который вы можете применять к разным вещам, чтобы их поменять или модифицировать. Это очень похоже на то как показывается коммит.

```bash
git stash show -p stash@{0}
```

## Получение изменений из копилки

После того как мы заберём изменения из копилки они попадают в рабочую директорию, не зависимо от того в каком бранче вы находитесь, тоесть Git не важно в каком бранче вы находитесь, он попытается перенести эти изменения в рабочую директорию и применить их. Это как и со слиянием, тоесть здесь также существует возможность возникновения конфликтов, когда эти изменения не могут быть примененны.

* Переключимся в основной бранч
* Проверим список изменений в копилке

```bash
git checkout master
git stash list
```

Есть две команды, которые позволяют достать измения из копилки

* `git stash pop` - достаёт изменения и удаляет их из копилки. Это полная противоположность `git stash save`
* `git stash apply` - достаёт изменения, но не удаляет их

Если не передать номер изменения, то будет забран первый. Если нужно указать конкретное изменение, тогда нужно передать его номер, при этом нумерация начинается с нуля, например:

```bash
git stash pop stash@{2}
```

Заберёт 3-е изменение.

## Удаление изменений из копилки

Чтобы удалить набор изменений из копилки нужно использовать команду `drop` а затем ссылку на элемент, которых мы хотим удалить.

```bash
git stash drop stash@{0}
git stash list
```

Чтобы удалить все наборы изменений из копилки за один раз следует использовать команду `clear`.

```bash
git stash clear
```

Будьте осторожны с этой командой, так как она чистить копилку полностью.