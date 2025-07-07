<?php

declare(strict_types=1);

namespace App\Tool\Dto;

final readonly class StringValueDto
{
    public function __construct(
        public string $value,
        public string $description,
    ) {
    }
}
