<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class VirtualPortfolioPositionDto
{
    public function __construct(
        public string $ticker,
        public string $instrumentType,
        public FloatValueDto $quantity,
        public FloatValueDto $averagePositionPrice,
        public FloatValueDto $expectedYield,
        public FloatValueDto $expectedYieldFifo,
        public StringValueDto $expireDate,
        public FloatValueDto $currentPrice,
        public FloatValueDto $averagePositionPriceFifo,
        public FloatValueDto $dailyYield,
    ) {
    }
}
