<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;

interface InstrumentsServiceComponentInterface
{
    public function getAssetFundamentals(GetAssetFundamentalsRequestDto $request): GetAssetFundamentalsResponseDto;

    /**
     * Возвращает UID актива по тикеру инструмента. Возвращает null, если инструмент не найден.
     */
    public function getAssetUidByTicker(string $ticker): ?string;
}
