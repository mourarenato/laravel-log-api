<?php

namespace App\Jobs;

use App\Models\ApiGatewayLogs;
use App\Services\ProcessApiGatewayLogsService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessApiGatewayLogsJob implements ShouldQueue
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
            if (!file_exists($this->logPath) || !is_readable($this->logPath)) {
                Log::error('Could not read the file', ['logPath' => $this->logPath]);
                throw new Exception('Could not read the file');
            }
            $service = $this->callService($this->logPath, new ApiGatewayLogs());
            $service->processLogFile();
            Log::info('Log file processed with success!', ['logPath' => $this->logPath]);
        } catch (Exception $e) {
            Log::error('Error when trying to read log file', [
                'error' => $e->getMessage(),
                'logPath' => $this->logPath,
            ]);
        }
    }

    public function callService(string $logPath, ApiGatewayLogs $model) : ProcessApiGatewayLogsService
    {
        return new ProcessApiGatewayLogsService($logPath, $model);
    }
}
