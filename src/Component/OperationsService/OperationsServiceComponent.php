<?php

declare(strict_types=1);

namespace App\Component\OperationsService;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Mapper\GetPortfolioRequestMapper;
use App\Component\OperationsService\Mapper\GetPortfolioResponseMapper;
use App\Exception\InfrastructureException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OperationsServiceComponent implements OperationsServiceComponentInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(
        private readonly string $token,
        private readonly string $baseUrl,
        private readonly LoggerInterface $logger,
        private readonly GetPortfolioRequestMapper $getPortfolioRequestMapper,
        private readonly GetPortfolioResponseMapper $getPortfolioResponseMapper,
    ) {
        $this->httpClient = HttpClient::create([
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'timeout' => 10,
        ]);
    }

    public function getPortfolio(GetPortfolioRequestDto $request): GetPortfolioResponseDto
    {
        $url = $this->baseUrl . 'tinkoff.public.invest.api.contract.v1.OperationsService/GetPortfolio';

        $this->logger->info('Get Portfolio', ['url' => $url]);

        try {
            $response = $this->httpClient->request(
                'POST',
                $url,
                [
                    'json' => $this->getPortfolioRequestMapper->map($request),
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new InfrastructureException(
                message: $e->getMessage(),
                previous: $e,
            );
        }

        return $this->getPortfolioResponseMapper->map($responseData);
    }
}
