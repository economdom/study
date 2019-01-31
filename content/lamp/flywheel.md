# Local by Flywheel

Окружение на базе Docker, специализированное для WordPress. В процессе установки автоматически установится необходимая версия VirtualBox и ничего дополнительно ставить не нужно.

## Доступ по SSH через Cmder под Windows

Чтобы из под Windows подключится через желаемый терминал, например Cmder, нужно перейти в папку */AppData/Roaming/Local by Flywheel/ssh-entry/* и запустить сгенерируемый *.bat* файл через Cmder, в моём случае это *36StyRuB9.bat*.

## Установка Composer

Установка и вызов для проверки одной командой:

```
apt-get update && apt-get install -y curl && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer
```