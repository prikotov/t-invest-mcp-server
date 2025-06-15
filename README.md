# Сервер MCP для T-Invest API

Проект содержит консольное приложение на базе [Symfony](https://symfony.com/), реализующее сервер MCP (Model Context Protocol).
Сервер предоставляет инструменты для работы с [T-Invest API](https://developer.tbank.ru/invest/intro/intro).

Сервер использует библиотеку [`logiscape/mcp-sdk-php`](https://github.com/logiscape/mcp-sdk-php)

## Конфигурация

```json
{
  "mcpServers": {
    "t-invest": {
      "command": "docker",
      "args": [
          "run",
          "-i",
          "--rm",
          "-e",
          "APP_T_INVEST_BASE_URL",
          "-e",
          "APP_T_INVEST_TOKEN",
          "-e",
          "APP_T_INVEST_ACCOUNT_ID",
          "docker.io/prikotov/t-invest-mcp-server:latest",
          "bin/server"
      ],
      "env": {
          "APP_T_INVEST_BASE_URL": "<API ENDPOINT>",
          "APP_T_INVEST_TOKEN": "<YOUR_TOKEN>",
          "APP_T_INVEST_ACCOUNT_ID": "<YOUR_ACCOUNT_ID>"
      }
    }
  }
}
```

где <API ENDPOINT> - [T-Invest REST API Endpoint](https://developer.tbank.ru/invest/intro/developer/protocols/): 
    - https://invest-public-api.tinkoff.ru/rest/ - продовый сервис
    - https://sandbox-invest-public-api.tinkoff.ru/rest/ — песочница.

<YOUR_TOKEN> - токен T-Invest REST API. С инструкцией получения токена можно ознакомиться [тут](https://developer.tbank.ru/invest/intro/intro/token#получить-токен).

<YOUR_ACCOUNT_ID> - номер счета в T-Invest к которому подключается MCP Server.  

## Возможности (Tools)

- `get_portfolio` — возвращает портфель клиента;

# Информация для разработчиков

## Требования

- PHP версии 8.3 и выше;
- Composer.

Приложение можно запустить как локально, так и в Docker.

## Установка

Склонируйте репозиторий и установите зависимости:

```bash
composer install
```

## Запуск

### Локально

```bash
bin/server
```

Либо традиционным способом:
```bash
php bin/console app:mcp-server
```

Либо с помощью podman:
```bash
podman run --rm -i t-invest-mcp-server bin/console app:mcp-server
```

Сервер выводит список доступных инструментов и позволяет вызывать каждый из них. Проверить можно с помощью:
```bash
podman-compose run --rm t-invest-mcp-server bin/console app:mcp-client --via=console
```

Опция `--via` позволяет выбрать способ запуска сервера (`console`, `podman` или `docker`). По умолчанию используется `console`.


### Docker (Podman)

В проекте присутствуют `Dockerfile` и `compose.yaml`. Чтобы собрать и запустить контейнер, выполните:

```bash
podman build -t t-invest-mcp-server .
```

## Тесты

Запуск тестов локально:

```bash
./bin/phpunit
```

Или через docker:

```bash
docker-compose run --rm t-invest-mcp-server bin/phpunit
```

Или через podman:

```bash
podman-compose run --rm t-invest-mcp-server bin/phpunit
```

Тесты подключают клиента к серверу и вызывают его инструменты.

## Структура проекта

- `src/` — исходный код приложения;
- `src/Tool` — исходный код Tools;
- `bin/` — консольные скрипты;
- `config/` — конфигурация Symfony;
- `tests/` — интеграционные тесты.
- `var/log` — логи приложения.

## Лицензия

Проект распространяется на условиях лицензии MIT. Полный текст лицензии см. в файле [LICENSE](LICENSE).

# Поддержка
Для вопросов и предложений:
- [Issues](https://github.com/prikotov/t-invest-mcp-server/issues)
- Email: prikotov@gmail.com
