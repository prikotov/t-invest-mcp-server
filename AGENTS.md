# Contribution Rules

Этот репозиторий использует PHP и PHPUnit.

## Подготовка окружения

- [ ] Установить PHP 8
- [ ] Установить Composer
- [ ] Выполнить команду `composer install` для установки зависимостей

## Требования к веткам

- [ ] Название ветки должно быть написано только на английском языке.
- [ ] Формат названия ветки: `codex/<task-description-in-english>`. Пример: `codex/add-mit-license`

## Требования к Pull Request (PR)

- [ ] Заголовок к Pull Request должен быть написан на английском языке, формат: `[codex] <Brief description>`. Пример заголовка PR: `[codex] Add MIT license`
- [ ] В PR должны быть обязательные разделы:

### Summary

- [ ] Кратко перечислить внесённые изменения (списком)
- [ ] Указать, какие файлы/модули затронуты
- [ ] Описать любые важные детали по реализации
- [ ] Summary должен быть написан на русском языке

### Testing

- [ ] Добавить результат выполнения команды `./bin/phpunit`

  Пример вывода:

  ```
  $ ./bin/phpunit
  PHPUnit 12.2.1 by Sebastian Bergmann and contributors.

  Runtime:       PHP 8.4.8
  Configuration: /workspace/t-invest-mcp-server/phpunit.dist.xml

  ...                                                                 3 / 3 (100%)

  Time: 00:00.175, Memory: 16.00 MB

  OK (3 tests, 4 assertions)
  ```

## Структура проекта

- `src/` — исходный код приложения.
- `src/Tool` — исходный код Tools.
- `bin/` — консольные скрипты.
- `config/` — конфигурация Symfony.
- `tests/` — тесты.
- `var/` — временные файлы и логи (создаётся во время работы приложения).

## T-Invest API

- Для реализации Tools используй схему OpenAPI, доступную по адресу:
  https://russianinvestments.github.io/investAPI/swagger-ui/openapi.yaml
