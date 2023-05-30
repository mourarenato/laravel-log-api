<?php

namespace Unit;

use App\Jobs\BuildAverageTimeReportByServiceJob;
use App\Services\BuildLogReportService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BuildAverageTimeReportByServiceJobTest extends TestCase
{
    public function testHandleWithSuccess()
    {
        $logPath = '/files/logtest.txt';

        $this->createMock(BuildLogReportService::class);

        $job = new BuildAverageTimeReportByServiceJob($logPath);

        Log::shouldReceive('info')
            ->with('Report of average time by service exported with success!')
            ->once();

        $this->assertNull($job->handle());
    }
}
