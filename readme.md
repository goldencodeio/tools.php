# Bitrix Restify

Rest API для 1С-Битрикс

## Начало работы

Эти инструкции помогут тебе получить копию проекта на локальную машину для разработки и/или тестирования. Смотри, как развернуть проект в живой системе, в разделе "Деплой".

### Необходимые условия окружения

Для запуска проекта в системе должны быть установлены:

- Docker
- Node.js
- [Docker Proxy](http://git.zolotoykod.ru/zk/docker-proxy-letsencrypt)

### Установка

Клонируем репозиторий, собираем фронтэнд и запускаем приложение

```bash
git clone git@github.com:goldencodeio/bitrix-restify.git && cd abv
yarn install
make frontend
npm run start
```

## Запуск тестов

Объясните, как запускать автоматические тесты для этой системы, что тестируют эти тесты и почему

```bash
make test
```

### Проверка стиля кода

Линтеры проверяют код на соответствие [стилю кода](http://git.zolotoykod.ru/zk/developer-guide/blob/master/1-standards/readme.md).
Запуск линтеров производится автоматически перед коммитом или командой:

```bash
npm run lint
```

## Деплой

Для проекта настроен автоматический деплой средствами Gitlab CI на кластер Docker Swarm с Docker Flow Proxy.

- Docker
- [Docker Flow Proxy](http://git.zolotoykod.ru/zk/docker-proxy-letsencrypt)

### Переменные окружения

Для конфигурации приложения используются переменные окружения. Перед деплоем приложения необходимо задать их в настройках проекта *[Settings > CI & CD > Secret variables](http://git.zolotoykod.ru/help/ci/variables/README#secret-variables)*.

Имя переменной | Описание | Значение по-умолчанию | Пример
--- | --- | --- | ---
`NODE_ENV` | окружение Node.js | `development` | `production` 
`ENVIRONMENT` | название окружения в котором запускается приложение | `development` | `(development|staging|production)`
`PRODUCTION_DOMAIN` | доменное имя продакшн хоста | |
`MYSQL_PASSWORD` | пароль пользователя MySQL | |
`MYSQL_ROOT_PASSWORD` | пароль root пользователя MySQL | |

## Разработано с использованием

* [Env](https://github.com/oscarotero/env) - Simple library to read environment variables and convert to simple types
* [Angular](https://angular.io/) - Frontend framework
* [UIkit](https://getuikit.com/) - A lightweight and modular front-end framework for developing fast and powerful web interfaces.
