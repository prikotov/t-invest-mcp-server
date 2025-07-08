<?php

declare(strict_types=1);

namespace App\Tool\GetAssetFundamentals\Dto;

use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\StringValueDto;

final readonly class AssetFundamentalDto
{
    public function __construct(
        public StringValueDto $assetUid,
        public ?StringValueDto $currency,
        public ?FloatValueDto $marketCapitalization,
        public ?FloatValueDto $highPriceLast52Weeks,
        public ?FloatValueDto $lowPriceLast52Weeks,
        public ?FloatValueDto $averageDailyVolumeLast10Days,
        public ?FloatValueDto $averageDailyVolumeLast4Weeks,
        public ?FloatValueDto $beta,
        public ?FloatValueDto $freeFloat,
        public ?FloatValueDto $forwardAnnualDividendYield,
        public ?FloatValueDto $sharesOutstanding,
        public ?FloatValueDto $revenueTtm,
        public ?FloatValueDto $ebitdaTtm,
        public ?FloatValueDto $netIncomeTtm,
        public ?FloatValueDto $epsTtm,
        public ?FloatValueDto $dilutedEpsTtm,
        public ?FloatValueDto $freeCashFlowTtm,
        public ?FloatValueDto $fiveYearAnnualRevenueGrowthRate,
        public ?FloatValueDto $threeYearAnnualRevenueGrowthRate,
        public ?FloatValueDto $peRatioTtm,
        public ?FloatValueDto $priceToSalesTtm,
        public ?FloatValueDto $priceToBookTtm,
        public ?FloatValueDto $priceToFreeCashFlowTtm,
        public ?FloatValueDto $totalEnterpriseValueMrq,
        public ?FloatValueDto $evToEbitdaMrq,
        public ?FloatValueDto $netMarginMrq,
        public ?FloatValueDto $netInterestMarginMrq,
        public ?FloatValueDto $roe,
        public ?FloatValueDto $roa,
        public ?FloatValueDto $roic,
        public ?FloatValueDto $totalDebtMrq,
        public ?FloatValueDto $totalDebtToEquityMrq,
        public ?FloatValueDto $totalDebtToEbitdaMrq,
        public ?FloatValueDto $freeCashFlowToPrice,
        public ?FloatValueDto $netDebtToEbitda,
        public ?FloatValueDto $currentRatioMrq,
        public ?FloatValueDto $fixedChargeCoverageRatioFy,
        public ?FloatValueDto $dividendYieldDailyTtm,
        public ?FloatValueDto $dividendRateTtm,
        public ?FloatValueDto $dividendsPerShare,
        public ?FloatValueDto $fiveYearsAverageDividendYield,
        public ?FloatValueDto $fiveYearAnnualDividendGrowthRate,
        public ?FloatValueDto $dividendPayoutRatioFy,
        public ?FloatValueDto $buyBackTtm,
        public ?FloatValueDto $oneYearAnnualRevenueGrowthRate,
        public ?StringValueDto $domicileIndicatorCode,
        public ?FloatValueDto $adrToCommonShareRatio,
        public ?FloatValueDto $numberOfEmployees,
        public ?StringValueDto $exDividendDate,
        public ?StringValueDto $fiscalPeriodStartDate,
        public ?StringValueDto $fiscalPeriodEndDate,
        public ?FloatValueDto $revenueChangeFiveYears,
        public ?FloatValueDto $epsChangeFiveYears,
        public ?FloatValueDto $ebitdaChangeFiveYears,
        public ?FloatValueDto $totalDebtChangeFiveYears,
        public ?FloatValueDto $evToSales,
    ) {
    }
}
