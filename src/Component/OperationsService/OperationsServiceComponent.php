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
    private const int TIMEOUT = 10;

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
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.OperationsService/GetPortfolio';

        $this->logger->info('Fetching portfolio', ['url' => $url]);

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
                    'json' => $this->getPortfolioRequestMapper->map($request),
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('GetPortfolio request failed', ['error' => $e->getMessage()]);
            throw new InfrastructureException(
                message: 'GetPortfolio request failed: ' . $e->getMessage(),
                previous: $e,
            );
        }

        // ВНИМАНИЕ: при логировании полного $responseData пропадают ошибки уровня выше.
        // Вероятно, из-за сериализации больших массивов или вложенных структур в логгере.
        // Не логируем ответ целиком, чтобы не терять исключения.
        //$this->logger->info('Portfolio data received', ['data' => $responseData]);
        $this->logger->info('Portfolio data received');

        return $this->getPortfolioResponseMapper->map($responseData);
    }
}
