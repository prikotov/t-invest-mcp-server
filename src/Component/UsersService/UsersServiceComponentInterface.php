<?php

declare(strict_types=1);

namespace App\Component\UsersService;

use App\Component\UsersService\Dto\GetAccountsResponseDto;

interface UsersServiceComponentInterface
{
    public function getAccounts(): GetAccountsResponseDto;
}
