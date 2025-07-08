<?php

declare(strict_types=1);

namespace App\Tool\GetAssetFundamentals\Dto;

/**
 * @param AssetFundamentalDto[] $fundamentals
 */
final readonly class GetAssetFundamentalsDto
{
    public function __construct(
        public array $fundamentals,
    ) {
    }
}
