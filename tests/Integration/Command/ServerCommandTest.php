<?php

declare(strict_types=1);

namespace App\Tests\Integration\Command;

use Mcp\Client\Client;
use Mcp\Client\ClientSession;
use Mcp\Client\Transport\EnvironmentHelper;
use Mcp\Client\Transport\StdioServerParameters;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServerCommandTest extends KernelTestCase
{
    private ClientSession $clientSession;

    private function startSession(): ClientSession
    {
        if (!isset($this->clientSession)) {
            EnvironmentHelper::initialize();
            $serverParams = new StdioServerParameters(
                command: 'bin/console',
                args: [
                    'app:mcp-server',
                ],
                env: array_merge(EnvironmentHelper::getDefaultEnvironment(), [
                    'APP_ENV' => 'test',
                ]),
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

    public function testListTools(): void
    {
        $session = $this->startSession();

        $toolsResult = $session->listTools();
        $toolsAsJson = json_encode($toolsResult, JSON_UNESCAPED_UNICODE);

        $this->assertStringContainsString('get_portfolio', $toolsAsJson);
        $this->assertStringContainsString('get_asset_fundamentals', $toolsAsJson);
    }

    public function testGetSecuritySpecification(): void
    {
        $session = $this->startSession();
        $res = $session->callTool('get_portfolio');
        $this->assertFalse($res->isError, $res->content[0]->text);

        $content = $res->content[0]->text ?? '';
        $data = json_decode($content, true);

        $this->assertSame(0, $data['totalAmountShares']['value']);
    }

    public function testCallGetAssetFundamentals(): void
    {
        $session = $this->startSession();

        $res = $session->callTool('get_asset_fundamentals', ['assets' => ['test']]);
        $this->assertFalse($res->isError, $res->content[0]->text ?? '');

        $content = $res->content[0]->text ?? '';
        $data = json_decode($content, true);

        $this->assertSame('test', $data['fundamentals'][0]['assetUid']);
    }
}
