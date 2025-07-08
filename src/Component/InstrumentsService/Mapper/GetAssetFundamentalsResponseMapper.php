<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\AssetFundamentalDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;

final readonly class GetAssetFundamentalsResponseMapper
{
    public function map(array $data): GetAssetFundamentalsResponseDto
    {
        $fundamentals = [];
        foreach ($data['fundamentals'] ?? [] as $item) {
            foreach ($item as $key => $value) {
                if (is_numeric($value) && (float)$value === 0.0) {
                    $item[$key] = null;
                }
            }
            $assetUid = (string)($item['assetUid'] ?? '');
            $fundamentals[] = new AssetFundamentalDto($assetUid, $item);
        }

        return new GetAssetFundamentalsResponseDto($fundamentals);
    }
}
