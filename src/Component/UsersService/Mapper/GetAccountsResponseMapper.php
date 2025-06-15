<?php

declare(strict_types=1);

namespace App\Component\UsersService\Mapper;

use App\Component\UsersService\Dto\AccountDto;
use App\Component\UsersService\Dto\GetAccountsResponseDto;
use DateMalformedStringException;
use DateTimeImmutable;

class GetAccountsResponseMapper
{
    /**
     * @throws DateMalformedStringException
     */
    public function map(array $responseData): GetAccountsResponseDto
    {
        $accounts = array_map(
            fn(array $accountData) => new AccountDto(
                id: $accountData['id'],
                type: $accountData['type'],
                name: $accountData['name'],
                status: $accountData['status'],
                openedDate: $this->createDt($accountData['openedDate']),
                closedDate: $this->createDt($accountData['closedDate']),
                accessLevel: $accountData['accessLevel']
            ),
            $responseData['accounts']
        );

        return new GetAccountsResponseDto($accounts);
    }

    /**
     * @throws DateMalformedStringException
     */
    private function createDt(string $dateTime): ?DateTimeImmutable
    {
        $date = new DateTimeImmutable($dateTime);
        if ($date->format('Y-m-d') === '1970-01-01') {
            $date = null;
        }

        return $date;
    }
}
