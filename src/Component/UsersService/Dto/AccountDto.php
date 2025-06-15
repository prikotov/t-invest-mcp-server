<?php

declare(strict_types=1);

namespace App\Component\UsersService\Dto;

use DateTimeImmutable;

class AccountDto
{
    public function __construct(
        public string $id,
        public string $type,
        public string $name,
        public string $status,
        public DateTimeImmutable $openedDate,
        public ?DateTimeImmutable $closedDate,
        public string $accessLevel
    ) {
    }
}
