<?php

namespace Unit;

use App\Http\Controllers\ApiGatewayLogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log, Validator};
use Mockery;
use Tests\TestCase;

class ApiGatewayLogsControllerTest extends TestCase
{
    public function testProcessLogFileWithSuccess()
    {
        $controller = new ApiGatewayLogsController();
        $request = new Request();

        $logPath = 'path/to/log';
        $request->merge(['log_path' => $logPath]);

        $response = $controller->processLogFile($request);

        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Added log file processing to queue!', $response->getData()->message);
        $this->assertEquals(200, $response->getStatusCode());
        Mockery::close();
    }

    public function testProcessLogFileFails()
    {
        $controller = new ApiGatewayLogsController();

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $response = $controller->processLogFile($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Could not to process log file', $response->getData()->message);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testExportReportOfRequestsByServiceWithSuccess()
    {
        $controller = new ApiGatewayLogsController();
        $request = new Request();

        $logPath = 'path/to/log';
        $request->merge(['log_path' => $logPath]);

        $response = $controller->exportReportOfRequestsByService($request);

        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Log report export was added to the queue!', $response->getData()->message);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExportReportOfRequestsByServiceFails()
    {
        $controller = new ApiGatewayLogsController();

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $response = $controller->exportReportOfRequestsByService($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Error when trying to export report', $response->getData()->message);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testExportReportOfRequestsByConsumerWithSuccess()
    {
        $controller = new ApiGatewayLogsController();
        $request = new Request();

        $logPath = 'path/to/log';
        $request->merge(['log_path' => $logPath]);

        $response = $controller->exportReportOfRequestsByConsumer($request);

        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Log report export was added to the queue!', $response->getData()->message);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExportReportOfRequestsByConsumerFails()
    {
        $controller = new ApiGatewayLogsController();

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $response = $controller->exportReportOfRequestsByConsumer($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Error when trying to export report', $response->getData()->message);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testExportReportOfAverageTimeByServiceWithSuccess()
    {
        $controller = new ApiGatewayLogsController();
        $request = new Request();

        $logPath = 'path/to/log';
        $request->merge(['log_path' => $logPath]);

        $response = $controller->exportReportOfAverageTimeByService($request);

        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Log report export was added to the queue!', $response->getData()->message);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExportReportOfAverageTimeByServiceFails()
    {
        $controller = new ApiGatewayLogsController();

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $response = $controller->exportReportOfAverageTimeByService($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Error when trying to export report', $response->getData()->message);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
