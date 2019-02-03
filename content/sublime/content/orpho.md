## Добавляем проверку орфографии

Для проверки русско-английских текстов одновременно качаем [Russian-English Bilingual](https://github.com/titoBouzout/Dictionaries/pull/68)

Файлы `.aff` и `.dic` помещаем в папку User (меню Sublime *Preferences / Browse Packages / User*). Находим новый словарь в меню Sublime `View → Dictionary`. Словарь заработает только после перезапуска Sublime. Для активации орфографии нажимае комбинацию `Fn + F6` (Mac OS). В пользовательских настройках должно появится две новых опции:

```
{
    "dictionary": "Packages/User/Russian-English Bilingual.dic",
    "spell_check": true,
}
```