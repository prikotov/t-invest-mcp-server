<?php

declare(strict_types=1);

namespace App\Service\Instruments;

interface InstrumentsServiceInterface
{
    public function getAssetUidByTicker(string $ticker): ?string;
}
