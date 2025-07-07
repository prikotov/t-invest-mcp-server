<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio\Dto;

final readonly class PortfolioPositionFieldDescriptionDto
{
    public function __construct(
        public ?string $ticker,
        public ?string $instrumentType,
        public string $quantity,
        public string $averagePositionPrice,
        public string $expectedYield,
        public string $currentNkd,
        public string $currentPrice,
        public string $averagePositionPriceFifo,
        public string $blockedLots,
        public string $varMargin,
        public string $expectedYieldFifo,
        public string $dailyYield,
        public ?string $summary,
    ) {
    }
}
