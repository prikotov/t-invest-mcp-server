<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Dto;

/**
 * Фундаментальные показатели по активу.
 *
 * @see https://developer.tbank.ru/invest/services/instruments/methods#getassetfundamentalsresponsestatisticresponse
 */
final readonly class AssetFundamentalDto
{
    public function __construct(
        public string $assetUid,
        public ?string $currency,
        public ?float $marketCapitalization,
        public ?float $highPriceLast52Weeks,
        public ?float $lowPriceLast52Weeks,
        public ?float $averageDailyVolumeLast10Days,
        public ?float $averageDailyVolumeLast4Weeks,
        public ?float $beta,
        public ?float $freeFloat,
        public ?float $forwardAnnualDividendYield,
        public ?float $sharesOutstanding,
        public ?float $revenueTtm,
        public ?float $ebitdaTtm,
        public ?float $netIncomeTtm,
        public ?float $epsTtm,
        public ?float $dilutedEpsTtm,
        public ?float $freeCashFlowTtm,
        public ?float $fiveYearAnnualRevenueGrowthRate,
        public ?float $threeYearAnnualRevenueGrowthRate,
        public ?float $peRatioTtm,
        public ?float $priceToSalesTtm,
        public ?float $priceToBookTtm,
        public ?float $priceToFreeCashFlowTtm,
        public ?float $totalEnterpriseValueMrq,
        public ?float $evToEbitdaMrq,
        public ?float $netMarginMrq,
        public ?float $netInterestMarginMrq,
        public ?float $roe,
        public ?float $roa,
        public ?float $roic,
        public ?float $totalDebtMrq,
        public ?float $totalDebtToEquityMrq,
        public ?float $totalDebtToEbitdaMrq,
        public ?float $freeCashFlowToPrice,
        public ?float $netDebtToEbitda,
        public ?float $currentRatioMrq,
        public ?float $fixedChargeCoverageRatioFy,
        public ?float $dividendYieldDailyTtm,
        public ?float $dividendRateTtm,
        public ?float $dividendsPerShare,
        public ?float $fiveYearsAverageDividendYield,
        public ?float $fiveYearAnnualDividendGrowthRate,
        public ?float $dividendPayoutRatioFy,
        public ?float $buyBackTtm,
        public ?float $oneYearAnnualRevenueGrowthRate,
        public ?string $domicileIndicatorCode,
        public ?float $adrToCommonShareRatio,
        public ?float $numberOfEmployees,
        public ?\DateTimeImmutable $exDividendDate,
        public ?\DateTimeImmutable $fiscalPeriodStartDate,
        public ?\DateTimeImmutable $fiscalPeriodEndDate,
        public ?float $revenueChangeFiveYears,
        public ?float $epsChangeFiveYears,
        public ?float $ebitdaChangeFiveYears,
        public ?float $totalDebtChangeFiveYears,
        public ?float $evToSales,
    ) {
    }
}
