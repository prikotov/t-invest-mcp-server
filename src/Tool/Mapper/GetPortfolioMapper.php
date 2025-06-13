<?php

declare(strict_types=1);

namespace App\Tool\Mapper;

use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
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
}
