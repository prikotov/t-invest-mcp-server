# Сервер MCP для T-Invest API

Проект содержит консольное приложение на базе [Symfony](https://symfony.com/), реализующее сервер MCP (Model Context Protocol).
Сервер предоставляет инструменты для работы с [T-Invest API](https://developer.tbank.ru/invest/intro/intro).

Сервер использует библиотеку [`logiscape/mcp-sdk-php`](https://github.com/logiscape/mcp-sdk-php)

## Конфигурация

```json
{
  "mcpServers": {
    "t-invest": {
      "command": "podman",
      "args": ["run", "-i", "--rm", "docker.io/prikotov/t-invest-mcp-server:latest", "bin/server"]
    }
  }
}
```

## Возможности (Tools)

- `get_profile` — возвращает портфель клиента;

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

```bash
./bin/phpunit
```

Или

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
