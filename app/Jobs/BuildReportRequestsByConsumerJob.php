<?php

namespace App\Jobs;

use App\Models\ApiGatewayLogs;
use App\Services\BuildLogReportService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BuildReportRequestsByConsumerJob implements ShouldQueue
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
            $service->exportReportOfRequestsByConsumer();
            Log::info('Report of requests by consumer exported with success!');
        } catch (Exception $e) {
            Log::error('Error when trying to export report of requests by consumer', [
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
