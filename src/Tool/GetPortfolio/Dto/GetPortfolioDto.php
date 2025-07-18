<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio\Dto;

use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\IntValueDto;

final readonly class GetPortfolioDto
{
    public function __construct(
        public FloatValueDto $totalAmountShares,
        public FloatValueDto $totalAmountBonds,
        public FloatValueDto $totalAmountEtf,
        public FloatValueDto $totalAmountCurrencies,
        public FloatValueDto $totalAmountFutures,
        public FloatValueDto $totalAmountOptions,
        public FloatValueDto $totalAmountSp,
        public FloatValueDto $totalAmountPortfolio,
        public FloatValueDto $expectedYield,
        public PortfolioPositionsDto $positions,
        //        /** @var VirtualPortfolioPositionDto[] */
        //        public array $virtualPositions,
        public IntValueDto $positionsCount,
        public IntValueDto $virtualPositionsCount,
        public FloatValueDto $dailyYield,
        public FloatValueDto $dailyYieldRelative,
    ) {
    }
}
