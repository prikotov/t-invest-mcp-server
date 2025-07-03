<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Dto;

use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;

/**
 * Позиции портфеля.
 * @link https://developer.tbank.ru/invest/services/operations/methods#portfolioposition
 */
final readonly class PortfolioPositionDto
{
    public function __construct(
        public string $figi,
        public string $instrumentType,
        public QuotationVo $quantity,
        public MoneyVo $averagePositionPrice,
        public QuotationVo $expectedYield,
        public ?MoneyVo $currentNkd,
        //public ?MoneyVo $averagePositionPricePt, @deprecated
        public MoneyVo $currentPrice,
        public MoneyVo $averagePositionPriceFifo,
        //public QuotationVo $quantityLots, @deprecated
        public bool $blocked,
        public ?QuotationVo $blockedLots,
        public string $positionUid,
        public string $instrumentUid,
        public MoneyVo $varMargin,
        public QuotationVo $expectedYieldFifo,
        public MoneyVo $dailyYield,
        public string $ticker,
    ) {
    }
}
