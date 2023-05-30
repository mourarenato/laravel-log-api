<?php

namespace Unit;

use App\Models\ApiGatewayLogs;
use App\Services\ProcessApiGatewayLogsService;
use Database\Factories\ApiGatewayLogsFactory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessApiGatewayLogsServiceTest extends TestCase
{
    use RefreshDatabase;

    private $testLogPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testLogPath = 'tests/logs.txt';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->testLogPath)) {
            unlink($this->testLogPath);
        }
    }

    private function createTestLogFile($log)
    {
        file_put_contents($this->testLogPath, $log);
    }

    public function testProcessLogFileWithSuccess()
    {
        $log = ApiGatewayLogsFactory::new()->make();

        $this->createTestLogFile($log);

        $apiGatewayLogs = new ApiGatewayLogs();
        $service = new ProcessApiGatewayLogsService($this->testLogPath, $apiGatewayLogs);

        $service->processLogFile();

        $modelObject = $apiGatewayLogs->first();

        $this->assertJsonStringEqualsJsonString($modelObject->request, json_encode($log->request));
        $this->assertJsonStringEqualsJsonString($modelObject->upstream_uri, json_encode($log->upstream_uri));
        $this->assertJsonStringEqualsJsonString($modelObject->response, json_encode($log->response));
        $this->assertJsonStringEqualsJsonString($modelObject->authenticated_entity, json_encode($log->authenticated_entity));
        $this->assertJsonStringEqualsJsonString($modelObject->route, json_encode($log->route));
        $this->assertJsonStringEqualsJsonString($modelObject->service, json_encode($log->service));
        $this->assertJsonStringEqualsJsonString($modelObject->latencies, json_encode($log->latencies));
        $this->assertJsonStringEqualsJsonString($modelObject->client_ip, json_encode($log->client_ip));
        $this->assertJsonStringEqualsJsonString($modelObject->started_at, json_encode($log->started_at));
    }

    public function testProcessLogFileWhenExceptionIsThrown()
    {
        $apiGatewayLogsMock = $this->createMock(ApiGatewayLogs::class);

        $service = new ProcessApiGatewayLogsService('/path/to/log/file.log', $apiGatewayLogsMock);

        $this->expectException(Exception::class);
        $service->processLogFile();
    }
}
