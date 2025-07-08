<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

final readonly class FindInstrumentRequestDto
{
    public function __construct(
        public string $query,
    ) {
    }
}
