<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Enum;

/**
 *
 * @link https://developer.tbank.ru/invest/services/operations/methods#portfoliorequestcurrencyrequest
 */
enum CurrencyEnum: int
{
    case rub = 0;
    case usd = 1;
    case eur = 2;
}
