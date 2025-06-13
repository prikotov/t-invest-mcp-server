<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Dto;

use App\Component\OperationsService\Enum\CurrencyEnum;

/**
 * Запрос получения текущего портфеля по счету.
 * @link https://developer.tbank.ru/invest/services/operations/methods#portfoliorequest
 */
class GetPortfolioRequestDto
{
    public function __construct(
        public string $accountId,
        public ?CurrencyEnum $currency = null,
    ) {
    }
}
