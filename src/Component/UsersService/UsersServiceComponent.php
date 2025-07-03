<?php

declare(strict_types=1);

namespace App\Component\UsersService;

use App\Component\UsersService\Dto\GetAccountsResponseDto;
use App\Component\UsersService\Mapper\GetAccountsResponseMapper;
use App\Exception\InfrastructureException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class UsersServiceComponent implements UsersServiceComponentInterface
{
    private const int TIMEOUT = 10;
    private const int STATUS_OPENED_ACCOUNTS = 2;

    public function __construct(
        private string $token,
        private string $baseUrl,
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private GetAccountsResponseMapper $getAccountsResponseMapper,
    ) {
    }

    public function getAccounts(): GetAccountsResponseDto
    {
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.UsersService/GetAccounts';

        $this->logger->info('Fetching accounts', ['url' => $url]);

        $response = null;
        try {
            $response = $this->httpClient->request(
                'POST',
                $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token,
                    ],
                    'timeout' => self::TIMEOUT,
                    'json' => [
                        'status' => self::STATUS_OPENED_ACCOUNTS,
                    ]
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('GetAccounts request failed', [
                'error' => $e->getMessage(),
                'response_body' => $response?->getContent(false) ?? null,
            ]);
            throw new InfrastructureException(
                message: 'Failed to Get Accounts: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $this->logger->info('Accounts data received');

        return $this->getAccountsResponseMapper->map($responseData);
    }
}
