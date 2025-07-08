<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

final readonly class GetInstrumentByResponseDto
{
    public function __construct(
        public ?string $assetUid,
    ) {
    }
}
