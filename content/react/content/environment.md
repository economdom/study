# Настройка среды разработки

Сначала нужно убедиться что у вас установлен Node.js. Для того чтобы создать новый проект на React, необходимо скачать и настроить несколько инструментов, которые будут преобразововать ваш React код, написаный на языке JSX в обычный JavaScript, который будет понятен браузеру, но кроме того делать несколько дополнительных полезных вещей.

Первый способ - это использование утилиты `create-react-app`. Этот инструмент создаст для вас характерную структуру файлов и папок, скачает все дополнительные утилиты и настроит их таким образом чтобы вам можно было сразу писать код. Второй способо сделать всё самостоятельно.

```
npm i -g create-react-app
```

Теперь `create-react-app` доступен глобально из коммандной строки.

```
create-react-app todo.loc
cd todo.loc
npm start
```

Данная команда создаст и запустит приложение React внутри папки `todo.loc`.

Отредактируем файл *src/App.js*.

*src/App.js*

```
import React from 'react';
import logo from './logo.svg';
import './App.css';

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Welcome to React!
        </p>
      </header>
    </div>
  );
}

export default App;
```

![Новый проект React](https://github.com/kamuz/study/blob/master/content/react/content/img/welcome-react.png?raw=true)

Утилита перезагрузит вместо вас страницу и покажет изменённый результат. Таким образом у нас имеется локальная среда разработки и локальный React проект на основе которого мы сможем делать свои собственные проекты.