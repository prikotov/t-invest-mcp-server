<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class GetPortfolioDto
{
    public function __construct(
        public StringValueDto $accountId,
        public FloatValueDto $totalAmountShares,
        public FloatValueDto $totalAmountBonds,
        public FloatValueDto $totalAmountEtf,
        public FloatValueDto $totalAmountCurrencies,
        public FloatValueDto $totalAmountFutures,
        public FloatValueDto $totalAmountOptions,
        public FloatValueDto $totalAmountSp,
        public FloatValueDto $totalAmountPortfolio,
        public FloatValueDto $expectedYield,
        public IntValueDto $positionsCount,
        public IntValueDto $virtualPositionsCount,
        public FloatValueDto $dailyYield,
        public FloatValueDto $dailyYieldRelative,
    ) {
    }
}
