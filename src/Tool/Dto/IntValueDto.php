<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class IntValueDto
{
    public function __construct(
        public int $value,
        public string $description,
    ) {
    }
}
