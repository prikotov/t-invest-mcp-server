<?php

declare(strict_types=1);

namespace App\Component\OperationsService;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Dto\GetPortfolioResponseDto;

interface OperationsServiceComponentInterface
{
    public function getPortfolio(GetPortfolioRequestDto $request): GetPortfolioResponseDto;
}
