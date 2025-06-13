<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Dto;

use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;
use DateTimeImmutable;

/**
 * Виртуальные позиции портфеля.
 * @link https://developer.tbank.ru/invest/services/operations/methods#virtualportfolioposition
 */
final readonly class VirtualPortfolioPositionDto
{
    public function __construct(
        public string $positionUid,
        public string $instrumentUid,
        public string $figi,
        public string $instrumentType,
        public QuotationVo $quantity,
        public MoneyVo $averagePositionPrice,
        public QuotationVo $expectedYield,
        public QuotationVo $expectedYieldFifo,
        public DateTimeImmutable $expireDate,
        public MoneyVo $currentPrice,
        public MoneyVo $averagePositionPriceFifo,
        public MoneyVo $dailyYield,
        public string $ticker,
    ) {
    }
}
