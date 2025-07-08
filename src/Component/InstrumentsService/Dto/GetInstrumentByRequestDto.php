<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

final readonly class GetInstrumentByRequestDto
{
    public function __construct(
        public string $id,
        public string $idType,
        public ?string $classCode = null,
    ) {
    }
}
