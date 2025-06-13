<?php

declare(strict_types=1);

namespace App\Tool\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Tool\Dto\GetPortfolioDto;

class GetPortfolioMapper
{
    public function map(GetPortfolioResponseDto $dto): GetPortfolioDto
    {
        // todo ? описать структуру в GetPortfolioDto используя StringValueDto, IntValueDto, FloatValueDto
        return new GetPortfolioDto();
    }
}
