<?php

namespace Unit;

use App\Jobs\BuildReportRequestsByServiceJob;
use App\Services\BuildLogReportService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BuildReportRequestsByServiceJobTest extends TestCase
{
    public function testHandleWithSuccess()
    {
        $logPath = '/files/logtest.txt';

        $this->createMock(BuildLogReportService::class);

        $job = new BuildReportRequestsByServiceJob($logPath);

        Log::shouldReceive('info')
            ->with('Report of requests by service exported with success!')
            ->once();

        $this->assertNull($job->handle());
    }

}
