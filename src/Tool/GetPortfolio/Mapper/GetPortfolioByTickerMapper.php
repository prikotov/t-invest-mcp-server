<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;
use App\Exception\NotFoundException;
use App\Tool\Dto\FloatValueDto;
use App\Tool\Dto\StringValueDto;
use App\Tool\GetPortfolio\Dto\GetPortfolioByTickerDto;

final class GetPortfolioByTickerMapper
{
    public function map(string $ticker, GetPortfolioResponseDto $dto): GetPortfolioByTickerDto
    {
        foreach ($dto->positions as $p) {
            if ($p->ticker === $ticker) {
                return new GetPortfolioByTickerDto(
                    instrumentType: new StringValueDto(
                        $p->instrumentType,
                        ''
                    ),
                    quantity: $this->mapQuotation(
                        'Количество инструмента в портфеле в штуках.',
                        $p->quantity
                    ),
                    averagePositionPrice: $this->mapMoney(
                        'Средневзвешенная цена позиции.',
                        $p->averagePositionPrice
                    ),
                    expectedYield: $this->mapQuotation(
                        'Текущая рассчитанная доходность позиции.',
                        $p->expectedYield
                    ),
                    currentNkd: $p->currentNkd === null ? null : $this->mapMoney(
                        'Текущий накопленный купонный доход (НКД).',
                        $p->currentNkd,
                    ),
                    currentPrice: $this->mapMoney(
                        'Текущая цена за 1 инструмент.',
                        $p->currentPrice
                    ),
                    averagePositionPriceFifo: $this->mapMoney(
                        'Средняя цена позиции по методу FIFO.',
                        $p->averagePositionPriceFifo
                    ),
                    blockedLots: $p->blockedLots === null || $p->blockedLots->getValue() == 0 ? null : $this->mapQuotation(
                        'Количество бумаг, заблокированных выставленными заявками.',
                        $p->blockedLots,
                    ),
                    varMargin: $p->varMargin->getValue() == 0 ? null : $this->mapMoney(
                        'Вариационная маржа.',
                        $p->varMargin,
                    ),
                    expectedYieldFifo: $this->mapQuotation(
                        'Текущая рассчитанная доходность позиции по методу FIFO (%).',
                        $p->expectedYieldFifo
                    ),
                    dailyYield: $this->mapMoney(
                        'Рассчитанная доходность портфеля за день.',
                        $p->dailyYield
                    ),
                    summary: new StringValueDto(
                        sprintf(
                            "%s: %s шт, средняя цена покупки %.2f₽, текущая цена %.2f₽, %s по позиции %.2f₽.",
                            $p->ticker,
                            $p->quantity->getValue(),
                            $p->averagePositionPrice->getValue(),
                            $p->currentPrice->getValue(),
                            $p->expectedYield->getValue() < 0 ? 'убыток' : 'доход',
                            abs($p->expectedYield->getValue()),
                        ),
                        ''
                    )
                );
            }
        }

        throw new NotFoundException(sprintf("Инструмент %s не обнаружен в портфеле", $ticker));
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
}
