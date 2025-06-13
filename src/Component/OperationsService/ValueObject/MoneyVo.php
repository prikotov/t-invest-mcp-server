<?php

declare(strict_types=1);

namespace App\Component\OperationsService\ValueObject;

use InvalidArgumentException;

/**
 * Денежная сумма в определенной валюте
 * @link https://developer.tbank.ru/invest/services/operations/methods#moneyvalue
 */
final readonly class MoneyVo
{
    private function __construct(
        private string $currency,
        private float $value,
        private int $units,
        private int $nano
    ) {
    }

    public static function createFromArray(?array $data): self
    {
        if (
            !isset($data['currency'])
            && !isset($data['units'])
            && !isset($data['nano'])
        ) {
            throw new InvalidArgumentException('Invalid money data');
        }

//        if (
//            !isset($data['currency'])
//            && $data['units'] == 0
//            && $data['nano'] == 0
//        ) {
//            throw new InvalidArgumentException('Zero money data');
//        }

        $units = (string)($data['units'] ?? '');
        $nano = (string)($data['nano'] ?? '');

        $value = round((int)$units + (int)$nano / 1000000000, 9);

        return new self(
            (string)$data['currency'],
            $value,
            (int)$units,
            (int)$nano
        );
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function getNano(): int
    {
        return $this->nano;
    }
}
