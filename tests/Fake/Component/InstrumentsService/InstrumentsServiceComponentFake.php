<?php

declare(strict_types=1);

namespace App\Tests\Fake\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\AssetFundamentalDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\InstrumentsServiceComponentInterface;

class InstrumentsServiceComponentFake implements InstrumentsServiceComponentInterface
{
    public function getAssetFundamentals(GetAssetFundamentalsRequestDto $request): GetAssetFundamentalsResponseDto
    {
        $assetUid = $request->assets[0] ?? 'test-uid';
        $data = [
            'assetUid' => $assetUid,
            'marketCapitalization' => 0,
        ];
        $dto = new AssetFundamentalDto($assetUid, $data);

        return new GetAssetFundamentalsResponseDto([$dto]);
    }
}
