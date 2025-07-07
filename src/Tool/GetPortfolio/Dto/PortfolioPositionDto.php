<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio\Dto;

final readonly class PortfolioPositionDto
{
    public function __construct(
        public string $ticker,
        public string $instrumentType,
        public float $quantity,
        public float $averagePositionPrice,
        public float $expectedYield,
        public ?float $currentNkd,
        public float $currentPrice,
        public float $averagePositionPriceFifo,
        public ?float $blockedLots,
        public ?float $varMargin,
        public float $expectedYieldFifo,
        public float $dailyYield,
        public string $summary,
    ) {
    }
}
