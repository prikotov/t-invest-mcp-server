<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

/**
 * Ответ с фундаментальными показателями.
 */
final readonly class GetAssetFundamentalsResponseDto
{
    /**
     * @param AssetFundamentalDto[] $fundamentals
     */
    public function __construct(
        public array $fundamentals,
    ) {
    }
}
