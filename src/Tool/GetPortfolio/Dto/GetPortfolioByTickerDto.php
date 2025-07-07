<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio\Dto;

use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\StringValueDto;

final readonly class GetPortfolioByTickerDto
{
    public function __construct(
        public StringValueDto $instrumentType,
        public FloatValueDto $quantity,
        public FloatValueDto $averagePositionPrice,
        public FloatValueDto $expectedYield,
        public ?FloatValueDto $currentNkd,
        public FloatValueDto $currentPrice,
        public FloatValueDto $averagePositionPriceFifo,
        public ?FloatValueDto $blockedLots,
        public ?FloatValueDto $varMargin,
        public FloatValueDto $expectedYieldFifo,
        public FloatValueDto $dailyYield,
        public StringValueDto $summary,
    ) {
    }
}
