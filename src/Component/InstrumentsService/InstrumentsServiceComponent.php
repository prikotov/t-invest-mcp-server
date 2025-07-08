<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\FindInstrumentRequestDto;
use App\Component\InstrumentsService\Dto\FindInstrumentResponseDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByRequestDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByResponseDto;
use App\Component\InstrumentsService\Mapper\FindInstrumentRequestMapper;
use App\Component\InstrumentsService\Mapper\FindInstrumentResponseMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsRequestMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsResponseMapper;
use App\Component\InstrumentsService\Mapper\GetInstrumentByRequestMapper;
use App\Component\InstrumentsService\Mapper\GetInstrumentByResponseMapper;
use App\Exception\InfrastructureException;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class InstrumentsServiceComponent implements InstrumentsServiceComponentInterface
{
    private const int TIMEOUT = 10;

    public function __construct(
        private string $token,
        private string $baseUrl,
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private GetAssetFundamentalsRequestMapper $assetRequestMapper,
        private GetAssetFundamentalsResponseMapper $assetResponseMapper,
        private FindInstrumentRequestMapper $findRequestMapper,
        private FindInstrumentResponseMapper $findResponseMapper,
        private GetInstrumentByRequestMapper $getRequestMapper,
        private GetInstrumentByResponseMapper $getResponseMapper,
    ) {
    }

    #[Override]
    public function findInstrument(FindInstrumentRequestDto $request): FindInstrumentResponseDto
    {
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.InstrumentsService/FindInstrument';

        $this->logger->info('Searching instrument', ['query' => $request->query]);

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
                    'json' => $this->findRequestMapper->map($request),
                ],
            );
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('FindInstrument request failed', ['error' => $e->getMessage()]);
            return new FindInstrumentResponseDto([]);
        }

        return $this->findResponseMapper->map($data);
    }

    #[Override]
    public function getInstrumentBy(GetInstrumentByRequestDto $request): GetInstrumentByResponseDto
    {
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.InstrumentsService/GetInstrumentBy';

        $this->logger->info('Fetching instrument by id', ['id' => $request->id]);

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
                    'json' => $this->getRequestMapper->map($request),
                ],
            );
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('GetInstrumentBy request failed', ['error' => $e->getMessage()]);
            return new GetInstrumentByResponseDto(null);
        }

        return $this->getResponseMapper->map($data);
    }

    #[Override]
    public function getAssetFundamentals(GetAssetFundamentalsRequestDto $request): GetAssetFundamentalsResponseDto
    {
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.InstrumentsService/GetAssetFundamentals';

        $this->logger->info('Fetching asset fundamentals', ['url' => $url]);

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
                    'json' => $this->assetRequestMapper->map($request),
                ],
            );
            $responseData = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('GetAssetFundamentals request failed', ['error' => $e->getMessage()]);
            throw new InfrastructureException(
                message: 'GetAssetFundamentals request failed: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $this->logger->info('Asset fundamentals data received');

        return $this->assetResponseMapper->map($responseData);
    }
}
