<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

/**
 * Запрос фундаментальных показателей по активам.
 * @link https://developer.tbank.ru/invest/services/instruments/methods#getassetfundamentals
 */
final readonly class GetAssetFundamentalsRequestDto
{
    /**
     * @param string[] $assets
     */
    public function __construct(
        public array $assets,
    ) {
    }
}
