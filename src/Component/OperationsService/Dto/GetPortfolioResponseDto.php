<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Dto;

use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;

/**
 * Текущий портфель по счету.
 * @link https://developer.tbank.ru/invest/services/operations/methods#portfolioresponse
 */
readonly class GetPortfolioResponseDto
{
    /**
     * @param MoneyVo $totalAmountShares
     * @param MoneyVo $totalAmountBonds
     * @param MoneyVo $totalAmountEtf
     * @param MoneyVo $totalAmountCurrencies
     * @param MoneyVo $totalAmountFutures
     * @param QuotationVo $expectedYield
     * @param PortfolioPositionDto[] $positions
     * @param string $accountId
     * @param MoneyVo $totalAmountOptions
     * @param MoneyVo $totalAmountSp
     * @param MoneyVo $totalAmountPortfolio
     * @param VirtualPortfolioPositionDto[] $virtualPositions
     * @param MoneyVo $dailyYield
     * @param QuotationVo $dailyYieldRelative
     */
    public function __construct(
        public MoneyVo $totalAmountShares,
        public MoneyVo $totalAmountBonds,
        public MoneyVo $totalAmountEtf,
        public MoneyVo $totalAmountCurrencies,
        public MoneyVo $totalAmountFutures,
        public QuotationVo $expectedYield,
        public array $positions,
        public string $accountId,
        public MoneyVo $totalAmountOptions,
        public MoneyVo $totalAmountSp,
        public MoneyVo $totalAmountPortfolio,
        public array $virtualPositions,
        public MoneyVo $dailyYield,
        public QuotationVo $dailyYieldRelative,
    ) {
    }
}
