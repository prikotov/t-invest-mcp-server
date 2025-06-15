<?php

declare(strict_types=1);

namespace App\Component\OperationsService;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Mapper\GetPortfolioRequestMapper;
use App\Component\OperationsService\Mapper\GetPortfolioResponseMapper;
use App\Exception\InfrastructureException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class OperationsServiceComponent implements OperationsServiceComponentInterface
{
    public function __construct(
        private string $token,
        private string $baseUrl,
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private GetPortfolioRequestMapper $getPortfolioRequestMapper,
        private GetPortfolioResponseMapper $getPortfolioResponseMapper,
    ) {
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
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token,
                    ],
                    'timeout' => 10,
                    'json' => $this->getPortfolioRequestMapper->map($request),
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('Get Portfolio error:', ['data' => $e->getMessage()]);
            throw new InfrastructureException(
                message: 'Failed to Get Portfolio: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $this->logger->info('Get Portfolio response:', ['data' => $responseData]);

        return $this->getPortfolioResponseMapper->map($responseData);
    }
}
