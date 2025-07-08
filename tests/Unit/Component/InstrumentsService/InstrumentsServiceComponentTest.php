<?php

declare(strict_types=1);

namespace App\Tests\Unit\Component\InstrumentsService;

use App\Component\InstrumentsService\Dto\FindInstrumentRequestDto;
use App\Component\InstrumentsService\Dto\FindInstrumentResponseDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\Dto\GetAssetFundamentalsResponseDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByRequestDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByResponseDto;
use App\Component\InstrumentsService\Dto\InstrumentShortDto;
use App\Component\InstrumentsService\InstrumentsServiceComponent;
use App\Component\InstrumentsService\Mapper\FindInstrumentRequestMapper;
use App\Component\InstrumentsService\Mapper\FindInstrumentResponseMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsRequestMapper;
use App\Component\InstrumentsService\Mapper\GetAssetFundamentalsResponseMapper;
use App\Component\InstrumentsService\Mapper\GetInstrumentByRequestMapper;
use App\Component\InstrumentsService\Mapper\GetInstrumentByResponseMapper;
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
    private $findRequestMapper;
    private $findResponseMapper;
    private $getRequestMapper;
    private $getResponseMapper;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->requestMapper = $this->createMock(GetAssetFundamentalsRequestMapper::class);
        $this->responseMapper = $this->createMock(GetAssetFundamentalsResponseMapper::class);
        $this->findRequestMapper = $this->createMock(FindInstrumentRequestMapper::class);
        $this->findResponseMapper = $this->createMock(FindInstrumentResponseMapper::class);
        $this->getRequestMapper = $this->createMock(GetInstrumentByRequestMapper::class);
        $this->getResponseMapper = $this->createMock(GetInstrumentByResponseMapper::class);

        $this->component = new InstrumentsServiceComponent(
            'test-token',
            'https://test-api/',
            $this->httpClient,
            new NullLogger(),
            $this->requestMapper,
            $this->responseMapper,
            $this->findRequestMapper,
            $this->findResponseMapper,
            $this->getRequestMapper,
            $this->getResponseMapper,
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

    public function testFindInstrument(): void
    {
        $response = $this->createMock(HttpResponse::class);
        $response->method('toArray')->willReturn([
            'instruments' => [
                ['uid' => 'u1', 'ticker' => 'TCKR'],
            ],
        ]);

        $this->findRequestMapper->method('map')->willReturn(['query' => 'TCKR']);
        $this->findResponseMapper->method('map')->willReturn(
            new FindInstrumentResponseDto([new InstrumentShortDto('u1', 'TCKR')])
        );

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->component->findInstrument(new FindInstrumentRequestDto('TCKR'));
        $this->assertCount(1, $result->instruments);
        $this->assertSame('u1', $result->instruments[0]->uid);
    }

    public function testGetInstrumentBy(): void
    {
        $response = $this->createMock(HttpResponse::class);
        $response->method('toArray')->willReturn([
            'instrument' => ['assetUid' => 'asset-1'],
        ]);

        $this->getRequestMapper->method('map')->willReturn([
            'idType' => 'INSTRUMENT_ID_TYPE_UID',
            'id' => 'u1',
        ]);
        $this->getResponseMapper->method('map')->willReturn(new GetInstrumentByResponseDto('asset-1'));

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->component->getInstrumentBy(new GetInstrumentByRequestDto('u1', 'INSTRUMENT_ID_TYPE_UID'));
        $this->assertSame('asset-1', $result->assetUid);
    }
}
