<?php

declare(strict_types=1);

namespace App\Tests\Fake\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\AssetFundamentalDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\InstrumentsServiceComponentInterface;

class InstrumentsServiceComponentFake implements InstrumentsServiceComponentInterface
{
    public function getAssetFundamentals(GetAssetFundamentalsRequestDto $request): GetAssetFundamentalsResponseDto
    {
        $assetUid = $request->assets[0] ?? 'test-uid';
        $dto = new AssetFundamentalDto(
            assetUid: $assetUid,
            currency: null,
            marketCapitalization: null,
            highPriceLast52Weeks: null,
            lowPriceLast52Weeks: null,
            averageDailyVolumeLast10Days: null,
            averageDailyVolumeLast4Weeks: null,
            beta: null,
            freeFloat: null,
            forwardAnnualDividendYield: null,
            sharesOutstanding: null,
            revenueTtm: null,
            ebitdaTtm: null,
            netIncomeTtm: null,
            epsTtm: null,
            dilutedEpsTtm: null,
            freeCashFlowTtm: null,
            fiveYearAnnualRevenueGrowthRate: null,
            threeYearAnnualRevenueGrowthRate: null,
            peRatioTtm: null,
            priceToSalesTtm: null,
            priceToBookTtm: null,
            priceToFreeCashFlowTtm: null,
            totalEnterpriseValueMrq: null,
            evToEbitdaMrq: null,
            netMarginMrq: null,
            netInterestMarginMrq: null,
            roe: null,
            roa: null,
            roic: null,
            totalDebtMrq: null,
            totalDebtToEquityMrq: null,
            totalDebtToEbitdaMrq: null,
            freeCashFlowToPrice: null,
            netDebtToEbitda: null,
            currentRatioMrq: null,
            fixedChargeCoverageRatioFy: null,
            dividendYieldDailyTtm: null,
            dividendRateTtm: null,
            dividendsPerShare: null,
            fiveYearsAverageDividendYield: null,
            fiveYearAnnualDividendGrowthRate: null,
            dividendPayoutRatioFy: null,
            buyBackTtm: null,
            oneYearAnnualRevenueGrowthRate: null,
            domicileIndicatorCode: null,
            adrToCommonShareRatio: null,
            numberOfEmployees: null,
            exDividendDate: null,
            fiscalPeriodStartDate: null,
            fiscalPeriodEndDate: null,
            revenueChangeFiveYears: null,
            epsChangeFiveYears: null,
            ebitdaChangeFiveYears: null,
            totalDebtChangeFiveYears: null,
            evToSales: null,
        );

        return new GetAssetFundamentalsResponseDto([$dto]);
    }

    public function getAssetUidByTicker(string $ticker): ?string
    {
        return $ticker . '-uid';
    }
}
