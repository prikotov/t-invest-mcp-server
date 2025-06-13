<?php

declare(strict_types=1);

namespace App\Tests\Integration\Command;

use Mcp\Client\Client;
use Mcp\Client\ClientSession;
use Mcp\Client\Transport\StdioServerParameters;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServerCommandTest extends KernelTestCase
{
    private ClientSession $clientSession;

    private function startSession(): ClientSession
    {
        if (!isset($this->clientSession)) {
            $serverParams = new StdioServerParameters(
                command: 'bin/console',
                args: [
                    'app:mcp-server',
                ],
            );
            $client = new Client();

            $this->clientSession = $client->connect(
                commandOrUrl: $serverParams->getCommand(),
                args: $serverParams->getArgs(),
                env: $serverParams->getEnv(),
            );
        }

        return $this->clientSession;
    }

    public function testListTools(): void {
        $session = $this->startSession();

        $toolsResult = $session->listTools();
        $toolsAsJson = json_encode($toolsResult, JSON_UNESCAPED_UNICODE);

        $this->assertStringContainsString('get_portfolio', $toolsAsJson);
    }

    public function testGetSecuritySpecification(): void
    {
        $session = $this->startSession();
        $res = $session->callTool('get_portfolio', [
            //'security' => 'SBER',
        ]);
        $this->assertFalse($res->isError);

        print_r($res); exit;

//        $resAsJson = json_encode($res, JSON_UNESCAPED_UNICODE);
//        $this->assertStringContainsString('ISIN код', $resAsJson);
//        $this->assertStringContainsString('RU0009029540', $resAsJson);
    }
}
