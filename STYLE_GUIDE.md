# Руководство по стилю кода

## Общие правила
- Кодировка: UTF-8
- Перевод строки: LF
- Отступы: 4 пробела (не табы)
- Макс. длина строки: 120 символов
- Финальный перевод строки: обязателен

## PHP-стиль
- Версия PHP: 8.3+
- Режим: strict_types=1
- Видимость: всегда явно указывать (public/protected/private)
- Типизация: везде где возможно
- Атрибуты: Symfony-стиль (#[Attribute])

### Именование
- Классы: `PascalCase` (ServerCommand)
- Интерфейсы: `PascalCase` с суффиксом Interface
- Трейты: `PascalCase` с суффиксом Trait
- Методы: `camelCase` (getPortfolio)
- Переменные: `camelCase` ($accountId)
- Константы: `UPPER_SNAKE_CASE`

### Структура файлов
- Один класс/интерфейс/трейт на файл
- Порядок в классе:
  1. Константы
  2. Свойства
  3. Конструктор
  4. Методы (публичные -> защищенные -> приватные)

## Документирование
- Докблоки для всех публичных методов
- Формат PHPDoc:
```php
/**
 * Краткое описание
 *
 * Подробное описание (если нужно)
 *
 * @param Type $param Описание
 * @return Type Описание
 * @throws ExceptionType Когда выбрасывается
 */
```

## Тестирование
- Фреймворк: PHPUnit
- Имена тестов: `testFeatureName`
- Каждый тест проверяет одну вещь
- Использование моков через createMock()
- Четкие сообщения в assert

## Коммиты
- Сообщения на английском
- Описание изменений в повелительном наклонении ("Add feature" вместо "Added feature")
- Если нужно - ссылка на issue

## Инструменты
- PHP-CS-Fixer: нет конфига (используется .editorconfig)
- CI: GitHub Actions (см. .github/workflows/ci.yml)

## Исключения
- Собственные исключения наследуют от InfrastructureException
- Сообщения ошибок на английском
- Включать оригинальное исключение через предыдущее (previous exception)

## Примеры
Хороший пример класса:
```php
<?php

declare(strict_types=1);

namespace App\Tool;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;

/**
 * Сервис работы с портфелем
 */
readonly class PortfolioService
{
    public function __construct(
        private OperationsServiceInterface $operationsService
    ) {
    }

    /**
     * Получить портфель по ID аккаунта
     *
     * @throws InfrastructureException
     */
    public function getByAccountId(string $accountId): PortfolioDto
    {
        // ...
    }
}
```
