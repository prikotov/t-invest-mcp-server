<?php

declare(strict_types=1);

namespace App\Tests\Unit\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\InstrumentsServiceComponent;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsRequestMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsResponseMapper;
use App\Exception\InfrastructureException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpResponse;

class InstrumentsServiceComponentTest extends TestCase
{
    private InstrumentsServiceComponent $component;
    private $httpClient;
    private $requestMapper;
    private $responseMapper;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->requestMapper = $this->createMock(GetAssetFundamentalsRequestMapper::class);
        $this->responseMapper = $this->createMock(GetAssetFundamentalsResponseMapper::class);

        $this->component = new InstrumentsServiceComponent(
            'test-token',
            'https://test-api/',
            $this->httpClient,
            new NullLogger(),
            $this->requestMapper,
            $this->responseMapper,
        );
    }

    public function testGetAssetFundamentalsSuccess(): void
    {
        $requestDto = new GetAssetFundamentalsRequestDto(['uid']);
        $this->requestMapper->method('map')->willReturn(['assets' => ['uid']]);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn(['fundamentals' => []]);

        $this->httpClient->method('request')->willReturn($response);

        $expectedDto = new GetAssetFundamentalsResponseDto([]);
        $this->responseMapper->method('map')->willReturn($expectedDto);

        $result = $this->component->getAssetFundamentals($requestDto);
        $this->assertSame($expectedDto, $result);
    }

    public function testGetAssetFundamentalsFailure(): void
    {
        $requestDto = new GetAssetFundamentalsRequestDto(['uid']);
        $this->requestMapper->method('map')->willReturn(['assets' => ['uid']]);

        $exception = new TimeoutException('API error');
        $this->httpClient->method('request')->willThrowException($exception);

        try {
            $this->component->getAssetFundamentals($requestDto);
            $this->fail('Expected InfrastructureException was not thrown');
        } catch (InfrastructureException $e) {
            $this->assertSame('GetAssetFundamentals request failed: API error', $e->getMessage());
            $this->assertSame($exception, $e->getPrevious());
        }
    }

    public function testGetAssetUidByTickerSuccess(): void
    {
        $findResponse = $this->createMock(HttpResponse::class);
        $findResponse->method('toArray')->willReturn([
            'instruments' => [
                ['uid' => 'u1', 'ticker' => 'TCKR'],
            ],
        ]);

        $getResponse = $this->createMock(HttpResponse::class);
        $getResponse->method('toArray')->willReturn([
            'instrument' => ['assetUid' => 'asset-1'],
        ]);

        $this->httpClient->expects($this->exactly(2))
            ->method('request')
            ->willReturnOnConsecutiveCalls($findResponse, $getResponse);

        $result = $this->component->getAssetUidByTicker('TCKR');
        $this->assertSame('asset-1', $result);
    }
}
