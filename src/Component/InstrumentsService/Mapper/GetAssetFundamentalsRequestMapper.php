<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;

final readonly class GetAssetFundamentalsRequestMapper
{
    public function map(GetAssetFundamentalsRequestDto $request): array
    {
        return [
            'assets' => $request->assets,
        ];
    }
}
