<?php

namespace Unit;

use App\Services\BuildLogReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BuildLogReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testExportReportOfRequestsByConsumerWithSuccess()
    {
        $reportPath = 'files/unit-report1.csv';
        $publicPath = 'public/files/unit-report1.csv';

        $service = new BuildLogReportService($reportPath);

        $service->exportReportOfRequestsByConsumer($reportPath);

        $this->assertTrue(File::exists($publicPath));

        unlink($publicPath);
    }

    public function exportReportOfRequestsByServiceWithSuccess()
    {
        $reportPath = 'files/unit-report2.csv';
        $publicPath = 'public/files/unit-report2.csv';

        $service = new BuildLogReportService($reportPath);

        $service->exportReportOfRequestsByService($reportPath);

        $this->assertTrue(File::exists($publicPath));

        unlink($publicPath);
    }

    public function exportReportOfAverageTimeByServiceWithSuccess()
    {
        $reportPath = 'files/unit-report3.csv';
        $publicPath = 'public/files/unit-report3.csv';

        $service = new BuildLogReportService($reportPath);

        $service->exportReportOfAverageTimeByService($reportPath);

        $this->assertTrue(File::exists($publicPath));

        unlink($publicPath);
    }
}
