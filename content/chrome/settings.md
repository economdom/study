## Отключить автоматическое обновление

Отключаем службы и с автозагрузки, а также вносим изменения в регистр (если ключей нет, то добавляем вручную)

```
Dword: AutoUpdateCheckPeriodMinutes Value: 0
Dword: DisableAutoUpdateChecksCheckboxValue Value: 1
Dword: UpdateDefault Value: 0
Dword: Update{8A69D345-D564-463C-AFF1-A69D9E530F96} Value: 0
```

> Нужно вносить изменения в двух местах `HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Google\Update` и `HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\Policies\Google\Update`

## Классический вид Chrome

В адресную строку вводим `chrome://flags`, находим **UI Layout for the browser’s top chrome** и устанавливаем опцию `Normal`.

> В Google Chrome версии больше 72 перейти на классический вид уже не возможно (отключили данный флаг).