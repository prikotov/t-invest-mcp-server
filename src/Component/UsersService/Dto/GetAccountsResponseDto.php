<?php

declare(strict_types=1);

namespace App\Component\UsersService\Dto;

final readonly class GetAccountsResponseDto
{
    /** @param AccountDto[] $accounts */
    public function __construct(
        public array $accounts
    ) {
    }
}
