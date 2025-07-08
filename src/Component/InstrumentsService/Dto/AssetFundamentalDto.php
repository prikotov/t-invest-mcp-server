<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

/**
 * Фундаментальные показатели по активу.
 */
final readonly class AssetFundamentalDto
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public string $assetUid,
        public array $data,
    ) {
    }
}
