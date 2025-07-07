<?php

declare(strict_types=1);

namespace App\Tests\Fake\Component\OperationService;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Mapper\GetPortfolioResponseMapper;
use App\Component\OperationsService\OperationsServiceComponentInterface;

class OperationsServiceComponentFake implements OperationsServiceComponentInterface
{
    public function __construct(
        private GetPortfolioResponseMapper $mapper
    ) {
    }

    public function getPortfolio(GetPortfolioRequestDto $request): GetPortfolioResponseDto
    {
        $json = <<<'JSON'
{"totalAmountShares":{},"totalAmountBonds":{},"totalAmountEtf":{},"totalAmountCurrencies":{},"totalAmountFutures":{},"expectedYield":{},"positions":[{"figi":"BBG00K53FBX6","instrumentType":"bond","quantity":{},"averagePositionPrice":{},"expectedYield":{},"currentNkd":{},"averagePositionPricePt":{},"currentPrice":{},"averagePositionPriceFifo":{},"quantityLots":{},"blocked":false,"blockedLots":{},"positionUid":"61bdcdb2-5733-4cf9-bb6a-7c2b975f3109","instrumentUid":"0160ee09-4b13-42d3-80f7-1482620820ea","varMargin":{},"expectedYieldFifo":{},"dailyYield":{},"ticker":"SU26224RMFS4"}],"accountId":"2019246368","totalAmountOptions":{},"totalAmountSp":{},"totalAmountPortfolio":{},"virtualPositions":[],"dailyYield":{},"dailyYieldRelative":{}}
JSON;
        $data = json_decode($json, true);

        $fillMoney = static fn(?array $money): array => [
            'currency' => $money['currency'] ?? 'RUB',
            'units' => $money['units'] ?? 0,
            'nano' => $money['nano'] ?? 0,
        ];
        $fillQuotation = static fn(?array $q): array => [
            'units' => $q['units'] ?? 0,
            'nano' => $q['nano'] ?? 0,
        ];

        foreach (
            [
            'totalAmountShares',
            'totalAmountBonds',
            'totalAmountEtf',
            'totalAmountCurrencies',
            'totalAmountFutures',
            'expectedYield',
            'totalAmountOptions',
            'totalAmountSp',
            'totalAmountPortfolio',
            'dailyYield',
            ] as $field
        ) {
            $data[$field] = $fillMoney($data[$field] ?? []);
        }
        $data['dailyYieldRelative'] = $fillQuotation($data['dailyYieldRelative'] ?? []);

        foreach ($data['positions'] as &$p) {
            $p['quantity'] = $fillQuotation($p['quantity'] ?? []);
            $p['averagePositionPrice'] = $fillMoney($p['averagePositionPrice'] ?? []);
            $p['expectedYield'] = $fillQuotation($p['expectedYield'] ?? []);
            $p['currentNkd'] = $fillMoney($p['currentNkd'] ?? []);
            $p['averagePositionPricePt'] = $fillMoney($p['averagePositionPricePt'] ?? []);
            $p['currentPrice'] = $fillMoney($p['currentPrice'] ?? []);
            $p['averagePositionPriceFifo'] = $fillMoney($p['averagePositionPriceFifo'] ?? []);
            $p['quantityLots'] = $fillQuotation($p['quantityLots'] ?? []);
            $p['blockedLots'] = $fillQuotation($p['blockedLots'] ?? []);
            $p['varMargin'] = $fillMoney($p['varMargin'] ?? []);
            $p['expectedYieldFifo'] = $fillQuotation($p['expectedYieldFifo'] ?? []);
            $p['dailyYield'] = $fillMoney($p['dailyYield'] ?? []);
        }
        unset($p);

        return $this->mapper->map($data);
    }
}
