<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\FindInstrumentResponseDto;
use App\Component\InstrumentsService\Dto\InstrumentShortDto;

final readonly class FindInstrumentResponseMapper
{
    public function map(array $data): FindInstrumentResponseDto
    {
        $items = [];
        foreach ($data['instruments'] ?? [] as $instrument) {
            $items[] = new InstrumentShortDto(
                uid: (string)($instrument['uid'] ?? ''),
                ticker: (string)($instrument['ticker'] ?? ''),
            );
        }
        return new FindInstrumentResponseDto($items);
    }
}
