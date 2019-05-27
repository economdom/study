# Сообщения для коммита

Сообщение коммита должны быть максимально информативными и сообщать нам какие именно были сделанны изменения.

* Для однострочного комментария или первой строки нужно использовать менее 50 символов
* Если вы коммитите много изменений, которые были сделанны в нескольких файлах, тогда лучше будет составлять более информативное сообщение состоящиее из нескольких строк. При этом длинна каждой строки не должна быть больше 72 символов. Длинные сообщения проще писать в редакторах кода чем вводить их из консоли.
* Сообщения для коммита нужно писать в настоящем времени, тоесть отвечать на вопрос что делает этот коммит - фиксит баг, удаляет файл.
* Для описания нескольких действия можно использовать звёздочки или дефисы для обозначения элементов списка
* Можно следовать рекоментдованным стандартам, например: 
  * Указать что вы работаете с  CSS и JavaScript используя квадратные кавычки - `[css,js]`
  * Что вы исправили багу - `bugfix: ...`
  * Указать номер баги - `#4545 - `

Примеры правильных и описательных коммитов

```bash
git commit -m "Add missing > in project section of HTML"
git commit -m "Change user authentication to use Blowfish"
git commit -m "Change user authentication to use Blowfish"
git commit -m "t23094 - Fixed bug in admin logout
When an admin logged out of the admin area, they 
could not log in to the members area because their 
session[:user_id] was still set to the admin ID. This patch 
fixed the bug by setting session[:user_id] to nil when 
any user logs out of any area."
```