<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

final readonly class FindInstrumentResponseDto
{
    /** @param InstrumentShortDto[] $instruments */
    public function __construct(
        public array $instruments,
    ) {
    }
}
