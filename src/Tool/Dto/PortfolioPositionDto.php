<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class PortfolioPositionDto
{
    public function __construct(
        public StringValueDto $figi,
        public StringValueDto $instrumentType,
        public FloatValueDto $quantity,
        public FloatValueDto $averagePositionPrice,
        public FloatValueDto $expectedYield,
        public ?FloatValueDto $currentNkd,
        public ?FloatValueDto $averagePositionPricePt,
        public FloatValueDto $currentPrice,
        public FloatValueDto $averagePositionPriceFifo,
        public FloatValueDto $quantityLots,
        public bool $blocked,
        public ?FloatValueDto $blockedLots,
        public StringValueDto $positionUid,
        public StringValueDto $instrumentUid,
        public FloatValueDto $varMargin,
        public FloatValueDto $expectedYieldFifo,
        public FloatValueDto $dailyYield,
        public StringValueDto $ticker,
    ) {
    }
}
