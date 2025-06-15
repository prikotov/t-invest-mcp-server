<?php

declare(strict_types=1);

namespace App\Component\OperationsService\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Dto\PortfolioPositionDto;
use App\Component\OperationsService\Dto\VirtualPortfolioPositionDto;
use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;
use DateMalformedStringException;
use DateTimeImmutable;

readonly class GetPortfolioResponseMapper
{
    /**
     * @throws DateMalformedStringException
     */
    public function map(array $data): GetPortfolioResponseDto
    {
        $positions = [];
        foreach ($data['positions'] as $position) {
            $positions[] = new PortfolioPositionDto(
                $position['figi'],
                $position['instrumentType'],
                QuotationVo::createFromArray($position['quantity']),
                MoneyVo::createFromArray($position['averagePositionPrice']),
                QuotationVo::createFromArray($position['expectedYield']),
                isset($position['currentNkd'])
                    ? MoneyVo::createFromArray($position['currentNkd'])
                    : null,
                isset($position['averagePositionPricePt'])
                    ? MoneyVo::createFromArray($position['averagePositionPricePt'])
                    : null,
                MoneyVo::createFromArray($position['currentPrice']),
                MoneyVo::createFromArray($position['averagePositionPriceFifo']),
                QuotationVo::createFromArray($position['quantityLots']),
                $position['blocked'],
                isset($position['blockedLots'])
                    ? QuotationVo::createFromArray($position['blockedLots'])
                    : null,
                $position['positionUid'],
                $position['instrumentUid'],
                MoneyVo::createFromArray($position['varMargin']),
                QuotationVo::createFromArray($position['expectedYieldFifo']),
                MoneyVo::createFromArray($position['dailyYield']),
                $position['ticker'],
            );
        }

        $virtualPositions = [];
        foreach ($data['virtualPositions'] as $position) {
            $virtualPositions[] = new VirtualPortfolioPositionDto(
                $position['positionUid'],
                $position['instrumentUid'],
                $position['figi'],
                $position['instrumentType'],
                QuotationVo::createFromArray($position['quantity']),
                MoneyVo::createFromArray($position['averagePositionPrice']),
                QuotationVo::createFromArray($position['expectedYield']),
                QuotationVo::createFromArray($position['expectedYieldFifo']),
                new DateTimeImmutable($position['expireDate']),
                MoneyVo::createFromArray($position['currentPrice']),
                MoneyVo::createFromArray($position['averagePositionPriceFifo']),
                MoneyVo::createFromArray($position['dailyYield']),
                $position['ticker'],
            );
        }

        return new GetPortfolioResponseDto(
            MoneyVo::createFromArray($data['totalAmountShares']),
            MoneyVo::createFromArray($data['totalAmountBonds']),
            MoneyVo::createFromArray($data['totalAmountEtf']),
            MoneyVo::createFromArray($data['totalAmountCurrencies']),
            MoneyVo::createFromArray($data['totalAmountFutures']),
            QuotationVo::createFromArray($data['expectedYield']),
            $positions,
            $data['accountId'],
            MoneyVo::createFromArray($data['totalAmountOptions']),
            MoneyVo::createFromArray($data['totalAmountSp']),
            MoneyVo::createFromArray($data['totalAmountPortfolio']),
            $virtualPositions,
            MoneyVo::createFromArray($data['dailyYield']),
            QuotationVo::createFromArray($data['dailyYieldRelative']),
        );
    }
}
