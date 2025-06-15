<?php

declare(strict_types=1);

namespace App\Tool\Dto;

class AccountDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $accessLevel
    ) {
    }
}
