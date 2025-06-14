<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class VirtualPortfolioPositionDto
{
    public function __construct(
        public StringValueDto $positionUid,
        public StringValueDto $instrumentUid,
        public StringValueDto $figi,
        public StringValueDto $instrumentType,
        public FloatValueDto $quantity,
        public FloatValueDto $averagePositionPrice,
        public FloatValueDto $expectedYield,
        public FloatValueDto $expectedYieldFifo,
        public StringValueDto $expireDate,
        public FloatValueDto $currentPrice,
        public FloatValueDto $averagePositionPriceFifo,
        public FloatValueDto $dailyYield,
        public StringValueDto $ticker,
    ) {
    }
}
