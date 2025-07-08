<?php

declare(strict_types=1);

namespace App\Tool\GetAssetFundamentals\Mapper;

use App\Component\InstrumentsService\Dto\AssetFundamentalDto as ComponentAssetFundamentalDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\StringValueDto;
use App\Tool\GetAssetFundamentals\Dto\AssetFundamentalDto;
use App\Tool\GetAssetFundamentals\Dto\GetAssetFundamentalsDto;

final class GetAssetFundamentalsMapper
{
    /**
     * @return GetAssetFundamentalsDto
     */
    public function map(GetAssetFundamentalsResponseDto $dto): GetAssetFundamentalsDto
    {
        $fundamentals = array_map(fn (ComponentAssetFundamentalDto $item) => $this->mapItem($item), $dto->fundamentals);
        return new GetAssetFundamentalsDto($fundamentals);
    }

    private function mapItem(ComponentAssetFundamentalDto $item): AssetFundamentalDto
    {
        $f = fn(?float $value, string $desc): ?FloatValueDto => $value === null ? null : new FloatValueDto($value, $desc);
        $s = fn(?string $value, string $desc): ?StringValueDto => $value === null ? null : new StringValueDto($value, $desc);

        return new AssetFundamentalDto(
            assetUid: new StringValueDto($item->assetUid, 'Идентификатор актива.'),
            currency: $s($item->currency, 'Валюта.'),
            marketCapitalization: $f($item->marketCapitalization, 'Рыночная капитализация.'),
            highPriceLast52Weeks: $f($item->highPriceLast52Weeks, 'Максимум за год.'),
            lowPriceLast52Weeks: $f($item->lowPriceLast52Weeks, 'Минимум за год.'),
            averageDailyVolumeLast10Days: $f($item->averageDailyVolumeLast10Days, 'Средний объем торгов за 10 дней.'),
            averageDailyVolumeLast4Weeks: $f($item->averageDailyVolumeLast4Weeks, 'Средний объем торгов за месяц.'),
            beta: $f($item->beta, 'Бета-коэффициент.'),
            freeFloat: $f($item->freeFloat, 'Доля акций в свободном обращении.'),
            forwardAnnualDividendYield: $f($item->forwardAnnualDividendYield, 'Процент форвардной дивидендной доходности по отношению к цене акций.'),
            sharesOutstanding: $f($item->sharesOutstanding, 'Количество акций в обращении.'),
            revenueTtm: $f($item->revenueTtm, 'Выручка.'),
            ebitdaTtm: $f($item->ebitdaTtm, 'EBITDA — прибыль до вычета процентов, налогов, износа и амортизации.'),
            netIncomeTtm: $f($item->netIncomeTtm, 'Чистая прибыль.'),
            epsTtm: $f($item->epsTtm, 'EPS — величина чистой прибыли компании, которая приходится на каждую обыкновенную акцию.'),
            dilutedEpsTtm: $f($item->dilutedEpsTtm, 'EPS компании с допущением, что все конвертируемые ценные бумаги компании были сконвертированы в обыкновенные акции.'),
            freeCashFlowTtm: $f($item->freeCashFlowTtm, 'Свободный денежный поток.'),
            fiveYearAnnualRevenueGrowthRate: $f($item->fiveYearAnnualRevenueGrowthRate, 'Среднегодовой  рocт выручки за 5 лет.'),
            threeYearAnnualRevenueGrowthRate: $f($item->threeYearAnnualRevenueGrowthRate, 'Среднегодовой  рocт выручки за 3 года.'),
            peRatioTtm: $f($item->peRatioTtm, 'Соотношение рыночной капитализации компании к ее чистой прибыли.'),
            priceToSalesTtm: $f($item->priceToSalesTtm, 'Соотношение рыночной капитализации компании к ее выручке.'),
            priceToBookTtm: $f($item->priceToBookTtm, 'Соотношение рыночной капитализации компании к ее балансовой стоимости.'),
            priceToFreeCashFlowTtm: $f($item->priceToFreeCashFlowTtm, 'Соотношение рыночной капитализации компании к ее свободному денежному потоку.'),
            totalEnterpriseValueMrq: $f($item->totalEnterpriseValueMrq, 'Рыночная стоимость компании.'),
            evToEbitdaMrq: $f($item->evToEbitdaMrq, 'Соотношение EV и EBITDA.'),
            netMarginMrq: $f($item->netMarginMrq, 'Маржа чистой прибыли.'),
            netInterestMarginMrq: $f($item->netInterestMarginMrq, 'Рентабельность чистой прибыли.'),
            roe: $f($item->roe, 'Рентабельность собственного капитала.'),
            roa: $f($item->roa, 'Рентабельность активов.'),
            roic: $f($item->roic, 'Рентабельность активов.'),
            totalDebtMrq: $f($item->totalDebtMrq, 'Сумма краткосрочных и долгосрочных обязательств компании.'),
            totalDebtToEquityMrq: $f($item->totalDebtToEquityMrq, 'Соотношение долга к собственному капиталу.'),
            totalDebtToEbitdaMrq: $f($item->totalDebtToEbitdaMrq, 'Total Debt/EBITDA.'),
            freeCashFlowToPrice: $f($item->freeCashFlowToPrice, 'Отношение свободногоо кэша к стоимости.'),
            netDebtToEbitda: $f($item->netDebtToEbitda, 'Отношение чистого долга к EBITDA.'),
            currentRatioMrq: $f($item->currentRatioMrq, 'Коэффициент текущей ликвидности.'),
            fixedChargeCoverageRatioFy: $f($item->fixedChargeCoverageRatioFy, 'Коэффициент покрытия фиксированных платежей — FCCR.'),
            dividendYieldDailyTtm: $f($item->dividendYieldDailyTtm, 'Дивидендная доходность за 12 месяцев.'),
            dividendRateTtm: $f($item->dividendRateTtm, 'Выплаченные дивиденды за 12 месяцев.'),
            dividendsPerShare: $f($item->dividendsPerShare, 'Значение дивидендов на акцию.'),
            fiveYearsAverageDividendYield: $f($item->fiveYearsAverageDividendYield, 'Средняя дивидендная доходность за 5 лет.'),
            fiveYearAnnualDividendGrowthRate: $f($item->fiveYearAnnualDividendGrowthRate, 'Среднегодовой рост дивидендов за 5 лет.'),
            dividendPayoutRatioFy: $f($item->dividendPayoutRatioFy, 'Процент чистой прибыли, уходящий на выплату дивидендов.'),
            buyBackTtm: $f($item->buyBackTtm, 'Деньги, потраченные на обратный выкуп акций.'),
            oneYearAnnualRevenueGrowthRate: $f($item->oneYearAnnualRevenueGrowthRate, 'Рост выручки за 1 год.'),
            domicileIndicatorCode: $s($item->domicileIndicatorCode, 'Код страны.'),
            adrToCommonShareRatio: $f($item->adrToCommonShareRatio, 'Соотношение депозитарной расписки к акциям.'),
            numberOfEmployees: $f($item->numberOfEmployees, 'Количество сотрудников.'),
            exDividendDate: $s($item->exDividendDate?->format('c'), 'Дата закрытия реестра на получение дивидендов.'),
            fiscalPeriodStartDate: $s($item->fiscalPeriodStartDate?->format('c'), 'Начало фискального периода.'),
            fiscalPeriodEndDate: $s($item->fiscalPeriodEndDate?->format('c'), 'Окончание фискального периода.'),
            revenueChangeFiveYears: $f($item->revenueChangeFiveYears, 'Изменение общего дохода за 5 лет.'),
            epsChangeFiveYears: $f($item->epsChangeFiveYears, 'Изменение EPS за 5 лет.'),
            ebitdaChangeFiveYears: $f($item->ebitdaChangeFiveYears, 'Изменение EBIDTA за 5 лет.'),
            totalDebtChangeFiveYears: $f($item->totalDebtChangeFiveYears, 'Изменение общей задолжности за 5 лет.'),
            evToSales: $f($item->evToSales, 'Отношение EV к выручке.'),
        );
    }
}
