<?php

declare(strict_types=1);

namespace App\Tests\Unit\Component\UsersService;

use App\Component\UsersService\Dto\GetAccountsResponseDto;
use App\Component\UsersService\Mapper\GetAccountsResponseMapper;
use App\Component\UsersService\UsersServiceComponent;
use App\Exception\InfrastructureException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UsersServiceComponentTest extends TestCase
{
    private UsersServiceComponent $component;
    private $httpClient;
    private $mapper;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->mapper = $this->createMock(GetAccountsResponseMapper::class);

        $this->component = new UsersServiceComponent(
            'test-token',
            'https://test-api/',
            $this->httpClient,
            new NullLogger(),
            $this->mapper
        );
    }

    public function testGetAccountsSuccess(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn(['accounts' => []]);

        $this->httpClient->method('request')
            ->willReturn($response);

        $expectedDto = new GetAccountsResponseDto([]);
        $this->mapper->method('map')
            ->willReturn($expectedDto);

        $result = $this->component->getAccounts();
        $this->assertSame($expectedDto, $result);
    }

    public function testGetAccountsFailure(): void
    {
        $exception = new TimeoutException('API error');
        $this->httpClient->method('request')
            ->willThrowException($exception);

        try {
            $this->component->getAccounts();
            $this->fail('Expected InfrastructureException was not thrown');
        } catch (InfrastructureException $e) {
            $this->assertSame('Failed to Get Accounts: API error', $e->getMessage());
            $this->assertSame($exception, $e->getPrevious());
        }
    }
}
