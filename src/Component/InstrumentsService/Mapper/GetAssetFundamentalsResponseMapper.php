<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService\Mapper;

use App\Component\InstrumentsService\Dto\AssetFundamentalDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use DateTimeImmutable;

final readonly class GetAssetFundamentalsResponseMapper
{
    public function map(array $data): GetAssetFundamentalsResponseDto
    {
        $fundamentals = [];
        foreach ($data['fundamentals'] ?? [] as $item) {
            $fundamentals[] = new AssetFundamentalDto(
                assetUid: (string)($item['assetUid'] ?? ''),
                currency: $item['currency'] ?? null,
                marketCapitalization: $this->toFloatOrNull($item['marketCapitalization'] ?? null),
                highPriceLast52Weeks: $this->toFloatOrNull($item['highPriceLast52Weeks'] ?? null),
                lowPriceLast52Weeks: $this->toFloatOrNull($item['lowPriceLast52Weeks'] ?? null),
                averageDailyVolumeLast10Days: $this->toFloatOrNull($item['averageDailyVolumeLast10Days'] ?? null),
                averageDailyVolumeLast4Weeks: $this->toFloatOrNull($item['averageDailyVolumeLast4Weeks'] ?? null),
                beta: $this->toFloatOrNull($item['beta'] ?? null),
                freeFloat: $this->toFloatOrNull($item['freeFloat'] ?? null),
                forwardAnnualDividendYield: $this->toFloatOrNull($item['forwardAnnualDividendYield'] ?? null),
                sharesOutstanding: $this->toFloatOrNull($item['sharesOutstanding'] ?? null),
                revenueTtm: $this->toFloatOrNull($item['revenueTtm'] ?? null),
                ebitdaTtm: $this->toFloatOrNull($item['ebitdaTtm'] ?? null),
                netIncomeTtm: $this->toFloatOrNull($item['netIncomeTtm'] ?? null),
                epsTtm: $this->toFloatOrNull($item['epsTtm'] ?? null),
                dilutedEpsTtm: $this->toFloatOrNull($item['dilutedEpsTtm'] ?? null),
                freeCashFlowTtm: $this->toFloatOrNull($item['freeCashFlowTtm'] ?? null),
                fiveYearAnnualRevenueGrowthRate: $this->toFloatOrNull($item['fiveYearAnnualRevenueGrowthRate'] ?? null),
                threeYearAnnualRevenueGrowthRate: $this->toFloatOrNull($item['threeYearAnnualRevenueGrowthRate'] ?? null),
                peRatioTtm: $this->toFloatOrNull($item['peRatioTtm'] ?? null),
                priceToSalesTtm: $this->toFloatOrNull($item['priceToSalesTtm'] ?? null),
                priceToBookTtm: $this->toFloatOrNull($item['priceToBookTtm'] ?? null),
                priceToFreeCashFlowTtm: $this->toFloatOrNull($item['priceToFreeCashFlowTtm'] ?? null),
                totalEnterpriseValueMrq: $this->toFloatOrNull($item['totalEnterpriseValueMrq'] ?? null),
                evToEbitdaMrq: $this->toFloatOrNull($item['evToEbitdaMrq'] ?? null),
                netMarginMrq: $this->toFloatOrNull($item['netMarginMrq'] ?? null),
                netInterestMarginMrq: $this->toFloatOrNull($item['netInterestMarginMrq'] ?? null),
                roe: $this->toFloatOrNull($item['roe'] ?? null),
                roa: $this->toFloatOrNull($item['roa'] ?? null),
                roic: $this->toFloatOrNull($item['roic'] ?? null),
                totalDebtMrq: $this->toFloatOrNull($item['totalDebtMrq'] ?? null),
                totalDebtToEquityMrq: $this->toFloatOrNull($item['totalDebtToEquityMrq'] ?? null),
                totalDebtToEbitdaMrq: $this->toFloatOrNull($item['totalDebtToEbitdaMrq'] ?? null),
                freeCashFlowToPrice: $this->toFloatOrNull($item['freeCashFlowToPrice'] ?? null),
                netDebtToEbitda: $this->toFloatOrNull($item['netDebtToEbitda'] ?? null),
                currentRatioMrq: $this->toFloatOrNull($item['currentRatioMrq'] ?? null),
                fixedChargeCoverageRatioFy: $this->toFloatOrNull($item['fixedChargeCoverageRatioFy'] ?? null),
                dividendYieldDailyTtm: $this->toFloatOrNull($item['dividendYieldDailyTtm'] ?? null),
                dividendRateTtm: $this->toFloatOrNull($item['dividendRateTtm'] ?? null),
                dividendsPerShare: $this->toFloatOrNull($item['dividendsPerShare'] ?? null),
                fiveYearsAverageDividendYield: $this->toFloatOrNull($item['fiveYearsAverageDividendYield'] ?? null),
                fiveYearAnnualDividendGrowthRate: $this->toFloatOrNull($item['fiveYearAnnualDividendGrowthRate'] ?? null),
                dividendPayoutRatioFy: $this->toFloatOrNull($item['dividendPayoutRatioFy'] ?? null),
                buyBackTtm: $this->toFloatOrNull($item['buyBackTtm'] ?? null),
                oneYearAnnualRevenueGrowthRate: $this->toFloatOrNull($item['oneYearAnnualRevenueGrowthRate'] ?? null),
                domicileIndicatorCode: $item['domicileIndicatorCode'] ?? null,
                adrToCommonShareRatio: $this->toFloatOrNull($item['adrToCommonShareRatio'] ?? null),
                numberOfEmployees: $this->toFloatOrNull($item['numberOfEmployees'] ?? null),
                exDividendDate: $this->toDateTimeOrNull($item['exDividendDate'] ?? null),
                fiscalPeriodStartDate: $this->toDateTimeOrNull($item['fiscalPeriodStartDate'] ?? null),
                fiscalPeriodEndDate: $this->toDateTimeOrNull($item['fiscalPeriodEndDate'] ?? null),
                revenueChangeFiveYears: $this->toFloatOrNull($item['revenueChangeFiveYears'] ?? null),
                epsChangeFiveYears: $this->toFloatOrNull($item['epsChangeFiveYears'] ?? null),
                ebitdaChangeFiveYears: $this->toFloatOrNull($item['ebitdaChangeFiveYears'] ?? null),
                totalDebtChangeFiveYears: $this->toFloatOrNull($item['totalDebtChangeFiveYears'] ?? null),
                evToSales: $this->toFloatOrNull($item['evToSales'] ?? null),
            );
        }

        return new GetAssetFundamentalsResponseDto($fundamentals);
    }

    private function toFloatOrNull(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $float = (float)$value;

        return $float === 0.0 ? null : $float;
    }

    private function toDateTimeOrNull(?string $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new DateTimeImmutable($value);
    }
}
