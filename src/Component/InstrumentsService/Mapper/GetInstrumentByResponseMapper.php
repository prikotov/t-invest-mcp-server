<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\GetInstrumentByResponseDto;

final readonly class GetInstrumentByResponseMapper
{
    public function map(array $data): GetInstrumentByResponseDto
    {
        $assetUid = $data['instrument']['assetUid'] ?? null;
        return new GetInstrumentByResponseDto($assetUid === null ? null : (string)$assetUid);
    }
}
