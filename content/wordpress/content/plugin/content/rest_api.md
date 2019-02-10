# WordPress REST API

- [Что такое WordPress REST API](#what-is)
- [Что такое REST и JSON](#restfull-api-json)
- [Часто используемые роуты](#popular-routes)

<div id="what-is"></div>

## Что такое WordPress REST API

**WordPress REST API** - это RESTful API, который может быть доступен через простые HTTP запросы для доступа к данным сайта в JSON формате. WordPress REST API - это новый вид доступа к данным WordPress без использования системы темизации, RSS или XML-RPC.

С использованием WordPress REST API у вас есть возможность создавать CRUD приложение, то есть мы можете считывать, создавать, редактировать и удалять данные без взаимодействия с WordPress напрямую без использования админки WordPress.

При стандартном подходе, вы можете использовать WordPress только в браузере, но с использованием WP REST API вы можете взаимодействовать с WordPress через мобильные или десктопные приложения, предоставлять данные для сторонних сервисов или использовать данные сайта для веб-приложений или в WordPress плагинах, а также вы использовать любое приложение или любой язык программирования для взаимодействия или использования возможностей вашего сайта.

<div id="restfull-api-json"></div>

## Что такое REST и JSON

**REST** - это акроним для **RE**presantational **S**tate **T**ransfer.

**REST** - это архитектура приложения для веб. **RESTful application** обычное приложение, которое использует стандартные запросы **GET**, **POST**, **PUT**, **DELETE** для получения/отправки данных от/к удалённому серверу.

RESTful приложение использует ресурсы (**resources**), что фактически представляют собой URL для взаимодействия с внешними системами.

**API** - это акроним для **A**pplication **P**rogramming **I**nterface. API это набор подпрограмм, протоколов и инструментов создания приложений и интерфейсов. Мы часто используете API даже тогда когда об этом не знаете, например Google Maps.

В итоге **RESTful API** - это интерфейс прикладного программирования, который использует URL ресурсы для выполнения операций на удалённом сервере через HTTP запросы.

Вы можете отправлять URL к серверу и получать обратно данные в JSON формате. JSON обычно используется для ассинхронных взаимодействий между браузером и сервером.

**Endpoint** (конечная точка) - это функции доступны через API в форме глаголов **GET**, **POST**, **PUT**, **DELETE**. Конечная точка выполняет определённую функцию с переданными параметрами и возвратом результата клиенту.

**Route** (маршрут) - это имя используемое для доступа к доступным конечным точкам.

`GET wp/v2/posts/5`, где `GET` - конечная точка, `wp/v2/posts/` - роут. Возвратит данные для поста с ID 5 в виде объекта.
`PUT wp/v2/posts/5` - обновит данные, `DELETE wp/v2/posts/5` - удалит текущий пост.

<div id="popular-routes"></div>

## Часто используемые роуты

Описание                                               | Роут
---                                                    |---
API index (all routes and endpoints)                   | `wp-json/wp/v2/`
Post index (10 latest posts)                           | `wp-json/wp/v2/posts`
Post index (2 posts)                                   | `wp-json/wp/v2/posts?per_page=2`
Single post based on ID                                | `wp-json/wp/v2/posts/7`
Page index (10 latest pages)                           | `wp-json/wp/v2/pages`
Page index (2 pages)                                   | `wp-json/wp/v2/pages?per_page=2`
Single page based on ID                                | `wp-json/wp/v2/pages/9`
Category index (10 first categories, alphabetically)   | `wp-json/wp/v2/categories`
Category index (2 categories)                          | `wp-json/wp/v2/categories?per_page=3`
Single category based on ID                            | `wp-json/wp/v2/categories/4`
Tag index (10 first tags, alpabetically)               | `wp-json/wp/v2/tags`
Tag index (2 tags)                                     | `wp-json/wp/v2/tags?per_page=3`
Single tag based on ID                                 | `wp-json/wp/v2/tags/3`
Post in category based on category IDs                 | `wp-json/wp/v2/posts?categories=198,4`
Post in category and tag by ID                         | `wp-json/wp/v2/posts?tags=199&categories=4`
User index (10 recent)                                 | `wp-json/wp/v2/users`
Current logged in user                                 | `wp-json/wp/v2/users/me`
Single user by ID                                      | `wp-json/wp/v2/users/1`
Comments index (10 recent)                             | `wp-json/wp/v2/comments`
10 latest comments on specific post based on post ID   | `wp-json/wp/v2/comments?post=6`