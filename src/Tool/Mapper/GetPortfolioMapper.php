<?php

declare(strict_types=1);

namespace App\Tool\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Dto\PortfolioPositionDto as ComponentPortfolioPositionDto;
use App\Component\OperationsService\Dto\VirtualPortfolioPositionDto as ComponentVirtualPortfolioPositionDto;
use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;
use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\IntValueDto;
use App\Tool\Dto\StringValueDto;
use App\Tool\GetPortfolio\Dto\GetPortfolioDto;
use App\Tool\GetPortfolio\Dto\PortfolioPositionDto;
use App\Tool\GetPortfolio\Dto\PortfolioPositionFieldDescriptionDto;
use App\Tool\GetPortfolio\Dto\PortfolioPositionsDto;
use App\Tool\GetPortfolio\Dto\VirtualPortfolioPositionDto;

final class GetPortfolioMapper
{
    public function map(GetPortfolioResponseDto $dto): GetPortfolioDto
    {
        return new GetPortfolioDto(
            totalAmountShares: $this->mapMoney('Общая стоимость акций в портфеле.', $dto->totalAmountShares),
            totalAmountBonds: $this->mapMoney('Общая стоимость облигаций в портфеле.', $dto->totalAmountBonds),
            totalAmountEtf: $this->mapMoney('Общая стоимость фондов в портфеле.', $dto->totalAmountEtf),
            totalAmountCurrencies: $this->mapMoney('Общая стоимость валют в портфеле.', $dto->totalAmountCurrencies),
            totalAmountFutures: $this->mapMoney('Общая стоимость фьючерсов в портфеле.', $dto->totalAmountFutures),
            totalAmountOptions: $this->mapMoney('Общая стоимость опционов в портфеле.', $dto->totalAmountOptions),
            totalAmountSp: $this->mapMoney('Общая стоимость структурных нот в портфеле.', $dto->totalAmountSp),
            totalAmountPortfolio: $this->mapMoney('Общая стоимость портфеля.', $dto->totalAmountPortfolio),
            expectedYield: $this->mapQuotation('Текущая относительная доходность портфеля в процентах.', $dto->expectedYield),
            positions: new PortfolioPositionsDto(
                fieldDescriptions: new PortfolioPositionFieldDescriptionDto(
                    ticker: null,
                    instrumentType: null,
                    quantity: 'Количество инструмента в портфеле в штуках.',
                    averagePositionPrice: 'Средневзвешенная цена позиции.',
                    expectedYield: 'Текущая рассчитанная доходность позиции.',
                    currentNkd: 'Текущий накопленный купонный доход (НКД).',
                    currentPrice: 'Текущая цена за 1 инструмент.',
                    averagePositionPriceFifo: 'Средняя цена позиции по методу FIFO.',
                    blockedLots: 'Количество бумаг, заблокированных выставленными заявками.',
                    varMargin: 'Вариационная маржа.',
                    expectedYieldFifo: 'Текущая рассчитанная доходность позиции по методу FIFO (%).',
                    dailyYield: 'Рассчитанная доходность портфеля за день.',
                    summary: null,
                ),
                positions: array_map(
                    fn(ComponentPortfolioPositionDto $p) => $this->mapPosition($p),
                    $dto->positions
                )
            ),
            //            virtualPositions: array_map(
            //                fn(ComponentVirtualPortfolioPositionDto $p) => $this->mapVirtualPosition($p),
            //                $dto->virtualPositions
            //            ),
            positionsCount: new IntValueDto(
                value: count($dto->positions),
                description: 'Количество позиций в портфеле.',
            ),
            virtualPositionsCount: new IntValueDto(
                value: count($dto->virtualPositions),
                description: 'Количество виртуальных позиций в портфеле.',
            ),
            dailyYield: $this->mapMoney('Рассчитанная доходность портфеля за день в рублях.', $dto->dailyYield),
            dailyYieldRelative: new FloatValueDto(
                value: $dto->dailyYieldRelative->getValue(),
                description: 'Относительная доходность в день в процентах.',
            ),
        );
    }

    private function mapMoney(string $description, MoneyVo $money): FloatValueDto
    {
        return new FloatValueDto(
            value: $money->getValue(),
            description: trim($description, '.') . ' (' . $money->getCurrency() . ').',
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
            ticker: $p->ticker,
            instrumentType: $p->instrumentType,
            quantity: $p->quantity->getValue(),
            averagePositionPrice: $p->averagePositionPrice->getValue(),
            expectedYield: $p->expectedYield->getValue(),
            currentNkd: $p->currentNkd?->getValue(),
            currentPrice: $p->currentPrice->getValue(),
            averagePositionPriceFifo: $p->averagePositionPriceFifo->getValue(),
            blockedLots: $p->blockedLots === null || $p->blockedLots->getValue() == 0 ? null : $p->blockedLots->getValue(),
            varMargin: $p->varMargin->getValue() == 0 ? null : $p->varMargin->getValue(),
            expectedYieldFifo: $p->expectedYieldFifo->getValue(),
            dailyYield: $p->dailyYield->getValue(),
            summary: sprintf(
                "%s: %s шт, средняя цена покупки %.2f₽, текущая цена %.2f₽, %s по позиции %.2f₽.",
                $p->ticker,
                $p->quantity->getValue(),
                $p->averagePositionPrice->getValue(),
                $p->currentPrice->getValue(),
                $p->expectedYield->getValue() < 0 ? 'убыток' : 'доход',
                abs($p->expectedYield->getValue()),
            )
        );
    }

    private function mapVirtualPosition(ComponentVirtualPortfolioPositionDto $p): VirtualPortfolioPositionDto
    {
        return new VirtualPortfolioPositionDto(
            ticker: $p->ticker,
            instrumentType: $p->instrumentType,
            quantity: $this->mapQuotation('Количество инструмента в портфеле в штуках.', $p->quantity),
            averagePositionPrice: $this->mapMoney('Средневзвешенная цена позиции.', $p->averagePositionPrice),
            expectedYield: $this->mapQuotation('Текущая рассчитанная доходность позиции.', $p->expectedYield),
            expectedYieldFifo: $this->mapQuotation('Текущая рассчитанная доходность позиции по методу FIFO.', $p->expectedYieldFifo),
            expireDate: new StringValueDto($p->expireDate->format('c'), 'Дата, до которой нужно продать виртуальные бумаги. После этой даты виртуальная позиция «сгорает».'),
            currentPrice: $this->mapMoney('Текущая цена за 1 инструмент.', $p->currentPrice),
            averagePositionPriceFifo: $this->mapMoney('Средняя цена позиции по методу FIFO.', $p->averagePositionPriceFifo),
            dailyYield: $this->mapMoney('Рассчитанная доходность портфеля за день.', $p->dailyYield),
        );
    }
}
