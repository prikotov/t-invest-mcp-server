# Руководство по стилю кода

## Общие правила
- Кодировка: UTF-8
- Перевод строки: LF
- Отступы: 4 пробела (не табы)
- Макс. длина строки: 120 символов
- Финальный перевод строки: обязателен
- Файлы PHP: строгий режим (`declare(strict_types=1)`)
- Все файлы должны содержать declare на первой строке после <?php

## PHP-стиль
- Версия PHP: 8.3+
- Режим: strict_types=1 (обязательно)
- Видимость: всегда явно указывать (public/protected/private)
- Типизация: везде где возможно
- Readonly-классы: для DTO и компонентов
- Атрибуты: Symfony-стиль (#[Attribute])
- Импорты: группировать по типу (встроенные PHP, сторонние, внутренние)

### Именование
- Классы: `PascalCase` (ServerCommand)
- Интерфейсы: `PascalCase` с суффиксом Interface (OperationsServiceComponentInterface)
- Трейты: `PascalCase` с суффиксом Trait
- Enum: `PascalCase` с суффиксом Enum (ToolNameEnum)
- Методы: `camelCase` (getPortfolio)
- Переменные: `camelCase` ($accountId)
- Константы: `UPPER_SNAKE_CASE`
- DTO: `PascalCase` с суффиксом Dto (GetPortfolioRequestDto)
- Value Objects: `PascalCase` с суффиксом Vo (MoneyVo)

### Структура файлов
- Один класс/интерфейс/трейт на файл
- Имя файла должно совпадать с именем класса
- Порядок в классе:
  1. Константы класса
  2. Свойства (readonly/private/protected/public)
  3. Конструктор
  4. Методы (публичные -> защищенные -> приватные)
  5. Магические методы (__invoke и др.)
- Группировка методов по функциональности

## Документирование
- Докблоки для всех публичных методов и классов
- Формат PHPDoc:
```php
/**
 * Краткое описание (одна строка)
 * 
 * Подробное описание (если нужно)
 * Может быть многострочным
 *
 * @param Type $param Описание параметра
 * @return Type Описание возвращаемого значения
 * @throws ExceptionType Когда выбрасывается
 */
```
- Для DTO и VO - указывать типы свойств в докблоках
- Для интерфейсов - полное описание контракта
- Исключения: документировать условия выбрасывания

## Тестирование
- Фреймворк: PHPUnit
- Имена тестов: `testMethodName_WhenCondition_ShouldDoSomething`
- Каждый тест проверяет одну вещь
- Структура теста:
  - Arrange (подготовка)
  - Act (действие)
  - Assert (проверки)
- Использование моков через createMock()
- Четкие сообщения в assert
- Тесты на исключения:
```php
$this->expectException(InfrastructureException::class);
$this->expectExceptionMessage('Failed to Get Portfolio');
```
- Для сложных тестовых данных - приватные методы-фабрики (createExpectedResponseDto)

## Коммиты
- Сообщения на английском
- Conventional Commits:
  - feat: для новой функциональности
  - fix: для исправления багов  
  - docs: для изменений документации
  - style: для изменений форматирования
  - refactor: для рефакторинга
  - test: для тестов
  - chore: для вспомогательных изменений
- Описание в повелительном наклонении ("Add feature" вместо "Added feature")
- Подробное описание после пустой строки (если нужно)
- Ссылка на issue (если есть)

## Инструменты
- PHP-CS-Fixer: нет конфига (используется .editorconfig)
- CI: GitHub Actions (см. .github/workflows/ci.yml)

## Исключения
- Собственные исключения наследуют от InfrastructureException
- Сообщения ошибок на английском
- Включать оригинальное исключение через предыдущее (previous exception)

## Примеры

### Хороший пример класса:
```php
<?php

declare(strict_types=1);

namespace App\Tool;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\OperationsServiceComponentInterface;
use App\Exception\InfrastructureException;

/**
 * Сервис для работы с инвестиционным портфелем
 */
readonly class PortfolioService
{
    public function __construct(
        private OperationsServiceComponentInterface $operationsService,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Получает полный портфель по идентификатору аккаунта
     *
     * @param string $accountId Идентификатор аккаунта в T-Invest
     * @return PortfolioDto Данные портфеля
     * @throws InfrastructureException При ошибках запроса к API
     */
    public function getByAccountId(string $accountId): PortfolioDto
    {
        $this->logger->info('Getting portfolio', ['accountId' => $accountId]);
        
        try {
            return $this->operationsService->getPortfolio(
                new GetPortfolioRequestDto($accountId)
            );
        } catch (InfrastructureException $e) {
            $this->logger->error('Failed to get portfolio', [
                'accountId' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
```

### Хороший пример теста:
```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tool;

use App\Tool\PortfolioService;
use PHPUnit\Framework\TestCase;

class PortfolioServiceTest extends TestCase
{
    public function testGetByAccountId_WhenApiReturnsData_ShouldReturnPortfolioDto(): void
    {
        // Arrange
        $operationsService = $this->createMock(OperationsServiceComponentInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $service = new PortfolioService($operationsService, $logger);
        
        // Act
        $result = $service->getByAccountId('test-account');
        
        // Assert
        $this->assertInstanceOf(PortfolioDto::class, $result);
    }
}
```
