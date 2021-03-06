# Что такое Git

В распределённых системах контроля версий клиенты не просто выгружают последние версии файлов, а полностью копируют весь репозиторий. Поэтому в случае, когда "умирает" сервер, через который шла работа, любой клиентский репозиторий может быть скопирован обратно на сервер, чтобы восстановить базу данных. Каждый раз, когда клиент забирает свежую версию файлов, он создаёт себе полную копию всех данных. Кроме того можно работать с несколькими удалёнными репозиториями, таким образом, можно параллельно работать с разными группами людей по-разному в рамках одного проекта.

## Директории и состояние файлов проекта Git

В Git файлы могут находиться в одном из трёх состояний: зафиксированном (закомиченом), изменённом и подготовленном. Зафиксированный значит, что файл уже сохранён в вашей локальной базе. К изменённым относятся файлы, которые поменялись, но ещё не были зафиксированы. Подготовленные файлы — это изменённые файлы,отмеченные для включения в следующий коммит.

Таким образом, в проектах, использующих Git, есть три части: каталог Git (git directory), рабочий каталог (working directory) и область подготовленных файлов (staging area).

* **Рабочий каталог** — это извлечённая из базы копия определённой версии проекта. Эти файлы достаются из сжатой базы данных в каталоге Git’а и помещаются на диск для того, чтобы вы их просматривали и редактировали.
* **Область подготовленных файлов** — это обычный файл, обычно хранящийся в каталоге Git, который содержит информацию о том, что должно войти в следующий коммит. Это временное место для хранения изменений. Иногда его называют индексом (index) или буфером, хотя в последнее время становится общепринятым называть его областью подготовленных файлов (staging area). Как говорят что все великие идеи всегда рождаются дважды, тоесть в начале в голове создателя, а потом в материальном мире. Точно также и с измененными файлами, сначала мы их отправляем в буфер и только после этого мы их можем закоммитить. Добавлять новые или изменённые файлы можно постепенно и изменения в этой областе можно легко изменять. Коммиты - это уже постоянное место хранения информации и коммиты сложно удалить и это крайне не рекомендуется делать. 
* **Каталог Git** — это место, где Git хранит метаданные и базу данных объектов вашего проекта. Это наиболее важная часть Git, и именно она копируется, когда вы клонируете репозиторий с другого компьютера.

![Local operations](https://github.com/kamuz/study/blob/master/content/git/content/img/local_operations.png?raw=true)

## Состояние файлов 

Каждый файл в вашем рабочем каталоге может находиться в одном из двух состояний: под версионным контролем (отслеживаемые, *анг.* trackted) и нет (неотслеживаемые, *анг.* untrackted). Отслеживаемые файлы — это те файлы, которые были в последнем слепке состояния проекта (`snapshot`); они могут быть неизменёнными (*анг.* unmodefied), изменёнными (*анг.* modified) или подготовленными к коммиту (*анг.* staged). Неотслеживаемые файлы — это всё остальное, любые файлы в вашем рабочем каталоге, которые не входили в ваш последний слепок состояния и не подготовлены к коммиту. Когда вы впервые клонируете репозиторий, все файлы будут отслеживаемыми и неизменёнными, потому что вы только взяли их из хранилища (`checked them out`) и ничего пока не редактировали.

![file_status](https://github.com/kamuz/study/blob/master/content/git/content/img/file_status.png?raw=true)

Стандартный рабочий процесс с использованием Git’а выглядит примерно так:

* Вы вносите изменения в файлы в своём рабочем каталоге.
* Подготавливаете файлы, добавляя их слепки в область подготовленных файлов.
* Делаете коммит, который берёт подготовленные файлы из индекса и помещает их в каталог Git на постоянное хранение.

Вам нужно делать некоторые изменения и фиксировать "снимки" состояния (`snapshots`) этих изменений в вашем репозитории каждый раз, когда проект достигает со- стояния, которое вам хотелось бы сохранить.

После выполнения команды `git init` создаётся скрытая папка *.git/*, в которой сохраняются файлы для хранения версий нашего проекта. По умолчанию эта папка скрыта, но если мы применим команду.

```
cd folder_project
ls -la
```

То мы увидим все скрытые директории и файлы внутри папки с проектом.

Если мы заходим удалить все версии нашего проекта созданные при помощи Git, то нам нужно просто удалить директорию *.git/*

Если мы посмотрим внутрь директории *.git/*, то мы увидим примерно следующее, то мы увидим список файлов и папок, которые Git использует при отслеживании.

```
cd .git/
ls -la
total 13
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 ./
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 ../
-rw-r--r-- 1 Vladimir 197609  10 May 29 08:34 COMMIT_EDITMSG
-rw-r--r-- 1 Vladimir 197609 157 May 29 08:34 config
-rw-r--r-- 1 Vladimir 197609  73 May 29 08:34 description
-rw-r--r-- 1 Vladimir 197609  23 May 29 08:34 HEAD
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 hooks/
-rw-r--r-- 1 Vladimir 197609 145 May 29 08:34 index
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 info/
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 logs/
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 objects/
drwxr-xr-x 1 Vladimir 197609   0 May 29 08:34 refs/
```

Это рабочее место Git и вам не нужно сюда заходить и что-то менять. Единственным исключением будет файл *config* - это единственное место, куда вы возможно будете заходить, просматривать или что-то менять - он предназначен для настройки конфигурации на уровне проекта.

У нас есть интефейс Git, поэтому нам не нужно заходить в файлы конфигурации и напрямую их редактировать - мы можем просто использовать разные команды Git для того чтобы назначить и посмотреть необходимые значения.

## Слепки и хэш суммы коммитов

Git считает хранимые данные набором слепков (снепшотов) небольшой файловой системы. Каждый раз, когда вы фиксируете текущую версию проекта, Git, по сути, сохраняет слепок того, как выглядят все файлы проекта на текущий момент. Слепок - это набор изменений, в одном или нескольких файлах. Важно понимать что слепки это не версии различных файлов. Ради эффективности, если файл не менялся, Git не сохраняет файл снова, а делает ссылку на ранее сохранённый файл.

Когда мы отправляем наборы изменений (коммитим), то в этот момент Git генерирует контрольную сумму для каждого набора изменений. Контрольная сумма это набор чисел и символов, которое генерируется путём принятия неких данных и отправки их алгоритму. Одни и те же данные отправленные алгоритму всегда ровны одной и той же итоговой контрольной сумме. Это важно, потому что если мы меняем принимаемые данные, то на выходе у нас получится другая контрольная сумма. Таким образом контрольная сумма всегда используется для гарантии того что данные не изменились. Способ, которым Git генерируем контрольную сумму заключается в использовании хэш-алгоритма - SHA-1.

Контрольная сумма или хэш - это можно сказать имя или идентификатор коммита, который мы часто будем использовать для перемещения указателя `HEAD` и просмотра изменений между отдельными коммитами. Мы можем использовать хэш для того чтобы переместить `HEAD` на определённый коммит, затем с этой точки создать новый бранч и с этого момента вести другую версию проекта.

Число, которое генерирует алгоритм всегда будет 16-ричной строкой в 40 символов. 16-ричное число обозначает что в ней могут быть цифры от 1 до 9 и буквы от a до f (0-9, a-f). Например `26a87f42531b270afba81a9e6c01aac894a0f9e7`. Данный номер будет уникальным для изменений.

* Git берёт набор изменений
* Пропускает их через алгоритм
* Получает номер из 40 символов

Используя команду `git log` вы можете видеть что каждый коммит сопроводждается контрольной суммой.

К каждому из слепков Git присоединяет некую метаинформацию, в том числе номер родительского коммита, тоесть коммит, который шёл до этого, автор коммита и сообщение коммита. Первый коммит имет значение родителя `nil`, а остальные содержат значение контрольной суммы предыдущего коммита (родителя). За счёт использования значения ротельского коммита, Git определяет последовательность коммитов в истории коммитов.

## Указатель HEAD

Git поддерживает ссылочную переменную, которая называется `HEAD`. Эту переменную называют указателем (*анг* pointer), потому что её цель заключается в том чтобы ссылаться или указывать на конкретный коммит в репозитории, а конкретней последний сделанный коммит, который также будет являтся родителем для следующего сделанного коммита. Когда мы делаем новые коммиты, указатель перемещается чтобы выделить новый коммит, `HEAD` всегда указывает на верхушку текущей ветки (*анг.* brunch) в нашем репозитории.

Проще говоря, эта переменная, которая по умолчанию всегда указывает на последний коммит в текущем бранче. Это можно изменить с помощью команды `checkout` и хеш суммы коммита, но об это позже.

`HEAD` становится очень важным когда мы говорим о ветках (бранчах). Ветка Git - это отдельное отдельная версия репозитория. По умолчанию основная ветка с которой мы работаем является `master`. В Git есть возможность создавать новые ветки, тоесть новые наборы кода с которыми мы работает и он является отдельным от нашей основной ветки. Тоесть у нас есть новая ветка и в этой ветке, мы также можем делать свои собственные коммиты, которые не касаются основной ветки. Когда мы делаем первый коммит в новой ветке, то `HEAD` перемещается на этот коммит в новую ветку и дальше перемещение уже происходит вперед по коммитам на новой ветке. Мы можем переключатся между этими ветками, мы можем делать `checkout` отдельный веток и таким образом `HEAD` всегда будет указывать на последний коммит текущей выбранной ветки.

Внутри папки *.git/* есть файл *HEAD* - его использует Git чтобы выяснить на что указывает `HEAD`. Можно отследить куда ссылается на самом деле ссылается `HEAD`

```
cd .git/
cat HEAD
```

Видим что это ссылка на файл *ref: refs/heads/master*. И если мы его откроем, то мы увидим хэш сумму последнего коммита.

```
cat refs/heads/master
```

В моем случая я получая `acc1f1477c3eaf0a76e5efc39f21c37fbedc14d6`. Таким образом вы выяснили что `HEAD` - это просто указатель который указывает на хеш сумму последнего коммита в текущей ветке.

Просмотр истории перемещения указателя `HEAD`

```
git reflog
```