<?php

namespace App\Jobs;

use App\Services\BuildLogReportService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BuildAverageTimeReportByServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $tries = 1;

    public function __construct(
        private string $logPath,
    ){}

    public function handle(): void
    {
        try {
            $service = $this->callService($this->logPath);
            $service->exportReportOfAverageTimeByService();
            Log::info('Report of average time by service exported with success!');
        } catch (Exception $e) {
            Log::error('Error when trying to export report of average time by service', [
                'error' => $e->getMessage(),
                'logPath' => $this->logPath,
            ]);
        }
    }

    public function callService(string $logPath) : BuildLogReportService
    {
        return new BuildLogReportService($logPath);
    }

}
