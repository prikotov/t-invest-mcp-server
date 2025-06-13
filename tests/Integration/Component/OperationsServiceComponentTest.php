<?php

declare(strict_types=1);

namespace App\Tests\Integration\Component;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\Mapper\GetPortfolioRequestMapper;
use App\Component\OperationsService\Mapper\GetPortfolioResponseMapper;
use App\Component\OperationsService\OperationsServiceComponent;
use App\Exception\InfrastructureException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class OperationsServiceComponentTest extends TestCase
{
    public function testGetPortfolioHandlesTransportException(): void
    {
        $component = new OperationsServiceComponent(
            token: 'token',
            baseUrl: 'http://invalid/',
            logger: new NullLogger(),
            getPortfolioRequestMapper: new GetPortfolioRequestMapper(),
            getPortfolioResponseMapper: new GetPortfolioResponseMapper(),
        );

        $this->expectException(InfrastructureException::class);
        $component->getPortfolio(new GetPortfolioRequestDto('test'));
    }
}
