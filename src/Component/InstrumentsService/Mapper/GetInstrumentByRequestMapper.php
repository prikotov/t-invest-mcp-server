<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\GetInstrumentByRequestDto;

final readonly class GetInstrumentByRequestMapper
{
    public function map(GetInstrumentByRequestDto $request): array
    {
        $data = [
            'idType' => $request->idType,
            'id' => $request->id,
        ];
        if ($request->classCode !== null) {
            $data['classCode'] = $request->classCode;
        }
        return $data;
    }
}
