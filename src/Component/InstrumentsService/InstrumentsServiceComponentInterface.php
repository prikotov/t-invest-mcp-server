<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\FindInstrumentRequestDto;
use App\Component\InstrumentsService\Dto\FindInstrumentResponseDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByRequestDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByResponseDto;

interface InstrumentsServiceComponentInterface
{
    public function getAssetFundamentals(GetAssetFundamentalsRequestDto $request): GetAssetFundamentalsResponseDto;

    public function findInstrument(FindInstrumentRequestDto $request): FindInstrumentResponseDto;

    public function getInstrumentBy(GetInstrumentByRequestDto $request): GetInstrumentByResponseDto;
}
