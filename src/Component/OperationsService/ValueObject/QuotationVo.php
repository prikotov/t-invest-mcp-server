<?php

declare(strict_types=1);

namespace App\Component\OperationsService\ValueObject;

use InvalidArgumentException;

/**
 * Котировка - денежная сумма без указания валюты
 * @link https://developer.tbank.ru/invest/services/operations/methods#quotation
 */
readonly class QuotationVo
{
    private function __construct(
        private float $value,
        private int $units,
        private int $nano
    ) {
    }

    public static function createFromArray(array $data): self
    {
        if (!isset($data['units']) && !isset($data['nano'])) {
            throw new InvalidArgumentException('Invalid quotation data');
        }

        $units = (string)($data['units']);
        $nano = (string)($data['nano']);

        $value = round((int)$units + (int)$nano / 1000000000, 9);

        return new self(
            $value,
            (int)$units,
            (int)$nano,
        );
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
