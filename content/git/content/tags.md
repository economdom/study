# Теги

Как правило используются для того чтобы указать версионность проекта.

Посмотреть все (перечисляет в алфавитном порядке, а не по времени их создания)

```bash
git tag
```

Посмотреть попадающие под маску

```bash
git tag -l 'v1.4.2.*'
```

Создать метку на текущем коммите (ключ -а) с меточным сообщением (ключ -m)

```bash
git tag -a v1.4 -m 'my version 1.4'
```

Если ключ -m не указывать то откроется окно редактора чтобы ввести сообщение

Создание легковесной метки на текущем коммите

```bash
git tag <name_tag>
git tag MyTAG
```

Посмотреть метки вместе с комментариями к коммитам, а так же с именами поставивших метки

```bash
git show <tag>
git show MyTAG
```

Так же можно выставлять метки и на уже пройденные коммиты.