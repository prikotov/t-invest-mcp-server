<?php

declare(strict_types=1);

namespace App\Tool\GetAccounts\Dto;

final readonly class AccountDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $accessLevel
    ) {
    }
}
