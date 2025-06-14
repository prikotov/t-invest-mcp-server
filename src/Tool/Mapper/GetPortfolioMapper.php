<?php

declare(strict_types=1);

namespace App\Tool\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Dto\PortfolioPositionDto as ComponentPortfolioPositionDto;
use App\Component\OperationsService\Dto\VirtualPortfolioPositionDto as ComponentVirtualPortfolioPositionDto;
use App\Component\OperationsService\ValueObject\QuotationVo;
use App\Tool\Dto\PortfolioPositionDto;
use App\Tool\Dto\VirtualPortfolioPositionDto;
use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\GetPortfolioDto;
use App\Tool\Dto\IntValueDto;
use App\Tool\Dto\StringValueDto;
use App\Component\OperationsService\ValueObject\MoneyVo;

class GetPortfolioMapper
{
    public function map(GetPortfolioResponseDto $dto): GetPortfolioDto
    {
        return new GetPortfolioDto(
            accountId: new StringValueDto(
                value: $dto->accountId,
                description: 'Account ID',
            ),
            totalAmountShares: $this->mapMoney('Total amount of shares', $dto->totalAmountShares),
            totalAmountBonds: $this->mapMoney('Total amount of bonds', $dto->totalAmountBonds),
            totalAmountEtf: $this->mapMoney('Total amount of ETF', $dto->totalAmountEtf),
            totalAmountCurrencies: $this->mapMoney('Total amount of currencies', $dto->totalAmountCurrencies),
            totalAmountFutures: $this->mapMoney('Total amount of futures', $dto->totalAmountFutures),
            totalAmountOptions: $this->mapMoney('Total amount of options', $dto->totalAmountOptions),
            totalAmountSp: $this->mapMoney('Total amount of SP', $dto->totalAmountSp),
            totalAmountPortfolio: $this->mapMoney('Total amount of portfolio', $dto->totalAmountPortfolio),
            expectedYield: $this->mapMoney('Expected yield', $dto->expectedYield),
            positions: array_map(
                fn(ComponentPortfolioPositionDto $p) => $this->mapPosition($p),
                $dto->positions
            ),
            virtualPositions: array_map(
                fn(ComponentVirtualPortfolioPositionDto $p) => $this->mapVirtualPosition($p),
                $dto->virtualPositions
            ),
            positionsCount: new IntValueDto(
                value: count($dto->positions),
                description: 'Number of positions',
            ),
            virtualPositionsCount: new IntValueDto(
                value: count($dto->virtualPositions),
                description: 'Number of virtual positions',
            ),
            dailyYield: $this->mapMoney('Daily yield', $dto->dailyYield),
            dailyYieldRelative: new FloatValueDto(
                value: $dto->dailyYieldRelative->getValue(),
                description: 'Daily yield relative',
            ),
        );
    }

    private function mapMoney(string $description, MoneyVo $money): FloatValueDto
    {
        return new FloatValueDto(
            value: $money->getValue(),
            description: $description . ' (' . $money->getCurrency() . ')',
        );
    }

    private function mapQuotation(string $description, QuotationVo $q): FloatValueDto
    {
        return new FloatValueDto(
            value: $q->getValue(),
            description: $description,
        );
    }

    private function mapPosition(ComponentPortfolioPositionDto $p): PortfolioPositionDto
    {
        return new PortfolioPositionDto(
            figi: new StringValueDto($p->figi, 'FIGI'),
            instrumentType: new StringValueDto($p->instrumentType, 'Instrument type'),
            quantity: $this->mapQuotation('Quantity', $p->quantity),
            averagePositionPrice: $this->mapMoney('Average position price', $p->averagePositionPrice),
            expectedYield: $this->mapQuotation('Expected yield', $p->expectedYield),
            currentNkd: $p->currentNkd ? $this->mapMoney('Current NKD', $p->currentNkd) : null,
            averagePositionPricePt: $p->averagePositionPricePt ? $this->mapMoney('Average position price pt', $p->averagePositionPricePt) : null,
            currentPrice: $this->mapMoney('Current price', $p->currentPrice),
            averagePositionPriceFifo: $this->mapMoney('Average position price FIFO', $p->averagePositionPriceFifo),
            quantityLots: $this->mapQuotation('Quantity lots', $p->quantityLots),
            blocked: $p->blocked,
            blockedLots: $p->blockedLots ? $this->mapQuotation('Blocked lots', $p->blockedLots) : null,
            positionUid: new StringValueDto($p->positionUid, 'Position UID'),
            instrumentUid: new StringValueDto($p->instrumentUid, 'Instrument UID'),
            varMargin: $this->mapMoney('Var margin', $p->varMargin),
            expectedYieldFifo: $this->mapQuotation('Expected yield FIFO', $p->expectedYieldFifo),
            dailyYield: $this->mapMoney('Daily yield', $p->dailyYield),
            ticker: new StringValueDto($p->ticker, 'Ticker'),
        );
    }

    private function mapVirtualPosition(ComponentVirtualPortfolioPositionDto $p): VirtualPortfolioPositionDto
    {
        return new VirtualPortfolioPositionDto(
            positionUid: new StringValueDto($p->positionUid, 'Position UID'),
            instrumentUid: new StringValueDto($p->instrumentUid, 'Instrument UID'),
            figi: new StringValueDto($p->figi, 'FIGI'),
            instrumentType: new StringValueDto($p->instrumentType, 'Instrument type'),
            quantity: $this->mapQuotation('Quantity', $p->quantity),
            averagePositionPrice: $this->mapMoney('Average position price', $p->averagePositionPrice),
            expectedYield: $this->mapQuotation('Expected yield', $p->expectedYield),
            expectedYieldFifo: $this->mapQuotation('Expected yield FIFO', $p->expectedYieldFifo),
            expireDate: new StringValueDto($p->expireDate->format('c'), 'Expire date'),
            currentPrice: $this->mapMoney('Current price', $p->currentPrice),
            averagePositionPriceFifo: $this->mapMoney('Average position price FIFO', $p->averagePositionPriceFifo),
            dailyYield: $this->mapMoney('Daily yield', $p->dailyYield),
            ticker: new StringValueDto($p->ticker, 'Ticker'),
        );
    }
}
