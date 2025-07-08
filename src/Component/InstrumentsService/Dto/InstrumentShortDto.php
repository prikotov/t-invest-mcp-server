<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

final readonly class InstrumentShortDto
{
    public function __construct(
        public string $uid,
        public string $ticker,
    ) {
    }
}
