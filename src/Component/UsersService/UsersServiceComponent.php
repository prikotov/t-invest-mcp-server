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
        $url = $this->baseUrl . 'tinkoff.public.invest.api.contract.v1.UsersService/GetAccounts';

        $this->logger->info('Get Accounts', ['url' => $url]);

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
                    'timeout' => 10,
                    'json' => [
                        'status' => 2
                    ]
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('Get Portfolio error', [
                'data' => $e->getMessage(),
                'body' => $response?->getContent(false),
            ]);
            throw new InfrastructureException(
                message: 'Failed to Get Accounts: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $this->logger->info('Get Accounts response:', ['data' => $responseData]);

        return $this->getAccountsResponseMapper->map($responseData);
    }
}
