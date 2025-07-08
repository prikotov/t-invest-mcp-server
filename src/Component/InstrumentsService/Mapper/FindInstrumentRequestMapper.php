<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\FindInstrumentRequestDto;

final readonly class FindInstrumentRequestMapper
{
    public function map(FindInstrumentRequestDto $request): array
    {
        return [
            'query' => $request->query,
        ];
    }
}
