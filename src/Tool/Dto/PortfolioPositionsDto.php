<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class PortfolioPositionsDto
{
    /**
     * @param PortfolioPositionFieldDescriptionDto $fieldDescriptions
     * @param PortfolioPositionDto[] $positions
     */
    public function __construct(
        public PortfolioPositionFieldDescriptionDto $fieldDescriptions,
        public array $positions,
    ) {
    }
}
