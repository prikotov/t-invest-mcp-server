<?php

declare(strict_types=1);

namespace App\Tool\Dto;

use App\Tool\Dto\PortfolioPositionDto;
use App\Tool\Dto\VirtualPortfolioPositionDto;

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
        /** @var PortfolioPositionDto[] */
        public array $positions,
        /** @var VirtualPortfolioPositionDto[] */
        public array $virtualPositions,
        public IntValueDto $positionsCount,
        public IntValueDto $virtualPositionsCount,
        public FloatValueDto $dailyYield,
        public FloatValueDto $dailyYieldRelative,
    ) {
    }
}
