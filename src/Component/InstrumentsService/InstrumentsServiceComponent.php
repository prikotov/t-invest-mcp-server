<?php

declare(strict_types=1);

namespace App\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsRequestMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsResponseMapper;
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
        private GetAssetFundamentalsRequestMapper $requestMapper,
        private GetAssetFundamentalsResponseMapper $responseMapper,
    ) {
    }

    #[Override]
    public function getAssetUidByTicker(string $ticker): ?string
    {
        $uid = $this->findInstrumentUid($ticker);
        if ($uid === null) {
            return null;
        }

        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.InstrumentsService/GetInstrumentBy';

        $this->logger->info('Fetching instrument by uid', ['uid' => $uid]);

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
                        'idType' => 'INSTRUMENT_ID_TYPE_UID',
                        'id' => $uid,
                    ],
                ],
            );
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('GetInstrumentBy request failed', ['error' => $e->getMessage()]);
            return null;
        }

        return $data['instrument']['assetUid'] ?? null;
    }

    private function findInstrumentUid(string $query): ?string
    {
        $url = rtrim($this->baseUrl, '/') . '/tinkoff.public.invest.api.contract.v1.InstrumentsService/FindInstrument';

        $this->logger->info('Searching instrument', ['query' => $query]);

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
                        'query' => $query,
                    ],
                ],
            );
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            $this->logger->error('FindInstrument request failed', ['error' => $e->getMessage()]);
            return null;
        }

        foreach ($data['instruments'] ?? [] as $instrument) {
            if (($instrument['ticker'] ?? '') === $query) {
                return $instrument['uid'] ?? null;
            }
        }

        $first = $data['instruments'][0]['uid'] ?? null;
        return $first;
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
                    'json' => $this->requestMapper->map($request),
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

        return $this->responseMapper->map($responseData);
    }
}
