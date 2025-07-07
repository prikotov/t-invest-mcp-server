<?php

declare(strict_types=1);

namespace App\Component\UsersService\Mapper;

use App\Component\UsersService\Dto\AccountDto;
use App\Component\UsersService\Dto\GetAccountsResponseDto;
use App\Exception\InfrastructureException;
use DateTimeImmutable;
use Exception;

final class GetAccountsResponseMapper
{
    public function map(array $responseData): GetAccountsResponseDto
    {
        $accounts = array_map(
            function (array $accountData) {
                $openedDate = $this->createDt($accountData['openedDate'] ?? null);
                if ($openedDate === null) {
                    throw new InfrastructureException("openedDate not valid: " . ($accountData['openedDate'] ?? 'null'));
                }

                return new AccountDto(
                    id: $accountData['id'],
                    type: $accountData['type'],
                    name: $accountData['name'],
                    status: $accountData['status'],
                    openedDate: $openedDate,
                    closedDate: $this->createDt($accountData['closedDate'] ?? null),
                    accessLevel: $accountData['accessLevel']
                );
            },
            $responseData['accounts']
        );

        return new GetAccountsResponseDto($accounts);
    }

    private function createDt(?string $dateTime): ?DateTimeImmutable
    {
        /** @psalm-suppress RiskyTruthyFalsyComparison */
        if (empty($dateTime)) {
            return null;
        }

        try {
            $date = new DateTimeImmutable($dateTime);
        } catch (Exception $e) {
            throw new InfrastructureException($e->getMessage());
        }

        if ($date->format('Y-m-d') === '1970-01-01') {
            return null;
        }

        return $date;
    }
}
