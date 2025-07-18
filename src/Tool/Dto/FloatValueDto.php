<?php

declare(strict_types=1);

namespace App\Tool\Dto;

final readonly class FloatValueDto
{
    public function __construct(
        public float $value,
        public string $description,
    ) {
    }
}
