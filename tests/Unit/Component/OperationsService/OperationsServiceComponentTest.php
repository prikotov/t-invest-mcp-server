<?php

declare(strict_types=1);

namespace App\Tests\Unit\Component\OperationsService;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Dto\GetPortfolioResponseDto;
use App\Component\OperationsService\Mapper\GetPortfolioRequestMapper;
use App\Component\OperationsService\Mapper\GetPortfolioResponseMapper;
use App\Component\OperationsService\OperationsServiceComponent;
use App\Component\OperationsService\ValueObject\MoneyVo;
use App\Component\OperationsService\ValueObject\QuotationVo;
use App\Exception\InfrastructureException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OperationsServiceComponentTest extends TestCase
{
    private OperationsServiceComponent $component;
    private $httpClient;
    private $requestMapper;
    private $responseMapper;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->requestMapper = $this->createMock(GetPortfolioRequestMapper::class);
        $this->responseMapper = $this->createMock(GetPortfolioResponseMapper::class);

        $this->component = new OperationsServiceComponent(
            'test-token',
            'https://test-api/',
            $this->httpClient,
            new NullLogger(),
            $this->requestMapper,
            $this->responseMapper
        );
    }

    public function testGetPortfolioSuccess(): void
    {
        $requestDto = new GetPortfolioRequestDto('test-account');
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn(['positions' => []]);

        $this->httpClient->method('request')
            ->willReturn($response);

        $expectedDto = $this->createExpectedResponseDto();
        $this->responseMapper->method('map')
            ->willReturn($expectedDto);

        $result = $this->component->getPortfolio($requestDto);
        $this->assertSame($expectedDto, $result);
    }

    private function createExpectedResponseDto(): GetPortfolioResponseDto
    {
        $moneyData = ['currency' => 'RUB', 'units' => 1000, 'nano' => 500000000];
        $quotationData = ['units' => 100, 'nano' => 50000000];

        return new GetPortfolioResponseDto(
            MoneyVo::createFromArray($moneyData),
            MoneyVo::createFromArray(['currency' => 'USD', 'units' => 500, 'nano' => 0]),
            MoneyVo::createFromArray(['currency' => 'EUR', 'units' => 200, 'nano' => 0]),
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 1000, 'nano' => 0]),
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 500, 'nano' => 0]),
            QuotationVo::createFromArray($quotationData),
            [],
            'test-account',
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 1000, 'nano' => 0]),
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 500, 'nano' => 0]),
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 1000, 'nano' => 0]),
            [],
            MoneyVo::createFromArray(['currency' => 'RUB', 'units' => 1000, 'nano' => 0]),
            QuotationVo::createFromArray(['units' => 100, 'nano' => 50000000])
        );
    }

    public function testGetPortfolioFailure(): void
    {
        $requestDto = new GetPortfolioRequestDto('test-account');
        $exception = new TimeoutException('API error');
        $this->httpClient->method('request')
            ->willThrowException($exception);

        try {
            $this->component->getPortfolio($requestDto);
            $this->fail('Expected InfrastructureException was not thrown');
        } catch (InfrastructureException $e) {
            $this->assertSame('GetPortfolio request failed: API error', $e->getMessage());
            $this->assertSame($exception, $e->getPrevious());
        }
    }

    public function testGetPortfolioHandlesTransportException(): void
    {
        $requestDto = new GetPortfolioRequestDto('test-account');
        $exception = new TimeoutException('Connection timeout');

        $this->httpClient->method('request')
            ->willThrowException($exception);

        $this->expectException(InfrastructureException::class);
        $this->expectExceptionMessage('GetPortfolio request failed: Connection timeout');

        $this->component->getPortfolio($requestDto);
    }
}
