<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;

final readonly class GetPortfolioRequestMapper
{
    public function map(GetPortfolioRequestDto $request): array
    {
        $data = [
            'accountId' => $request->accountId,
        ];

        if ($request->currency !== null) {
            $data['currencyId'] = $request->currency->value;
        }

        return $data;
    }
}
