# Введение в React

Каждый веб-сайт состоит из блоков, которые переиспользуются в разных местах веб-сайта. Разбивать пользовательский интерфейс на компоненты - это также правильно, как и разбивать ваш JavaScript код на функции. **Функция** - это блок кода, который можно переиспользовать в разных местах вашего приложения, точно также и UI компонент - это блок вашего пользовательского интерфейса с каким-то внешним видом, каким-то поведением, который затем можно переиспользовать в разных частях вашего приложения. И точно также как хорошая функция должна быть маленькой и независимой для того чтобы её легко было обновлять и тестировать, хороший UI компонент тоже должен быть компактным и независимым от всего остального приложения, чтобы его было легко обновлять и тестировать.

React как раз построен вокруг UI компонентов и даёт вам инструменты чтобы эффективно создавать эти UI компоненты и затем строить из них приложение.

Основными отличиями React от остальных подобных библиотек и фреймворков являются:

1. **JSX** - это язык расширения JavaScript, который позволяет вам комбинировать вам JavaScript код с разметкой, которая похожая на HTML, таким образом ваш код и ваш UI находятся рядом. Это позволяет вам существенно более эффективно писать UI логику.
2. **Reconcilation Algorithm** - это тот алгоритм, который он использует внутри себя чтобы понимать какие именно части веб-сайта нужно обновить. К примеру пользователь кликает на какой-то компонент и этот компонент изменяет свой внешний вид. Или приходят новые данные с сервера и вам нужно обновить эти данные на UI. Этот алгоритм, который работает внутри React делает достаточно много сложных вещей для того чтобы найти именно те блоки на странице, которые требуют обновления и обновить только их, не трогая те блоки, которые не изменились. Этот подход очень эффективный и делает React приложение действительно быстрым.

Сначала React разрабатывался исключительно как веб-библиотека, но со временем стало очевидно что и JSX и Reconcilation Algorithm можно также использовать и для Native приложений для разработки приложений для IOS и Android и таким образом появился **React Native**.

При этом React не является:

* Это не фреймворк. В крупных приложениях есть масса друхих аспектов, кроме UI - это работа с сервером, валидация, управление глобальным состоянием, Unit тестирование и т. д. Во фреймворках это всё как правило есть из коробки. React делает практически одну вещь, но делает это очень хорошо, React отвечает только за UI. Но есть другие библиотеки, которые покрывают весь этот функционал и отлично работают с React.
* React не является готовым набором компонентов, которые можно просто взять и использовать в своём приложении. React даёт вам инструменты для создания таких компонентов.