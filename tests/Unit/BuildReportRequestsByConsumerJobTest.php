<?php

namespace Unit;

use App\Jobs\BuildReportRequestsByConsumerJob;
use App\Services\BuildLogReportService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BuildReportRequestsByConsumerJobTest extends TestCase
{
    public function testHandleWithSuccess()
    {
        $logPath = '/files/logtest.txt';

        $this->createMock(BuildLogReportService::class);

        $job = new BuildReportRequestsByConsumerJob($logPath);

        Log::shouldReceive('info')
            ->with('Report of requests by consumer exported with success!')
            ->once();

        $this->assertNull($job->handle());
    }

}
