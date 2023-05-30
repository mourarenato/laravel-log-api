<?php

namespace App\Http\Controllers;

use App\Jobs\BuildAverageTimeReportByServiceJob;
use App\Jobs\BuildReportRequestsByConsumerJob;
use App\Jobs\BuildReportRequestsByServiceJob;
use App\Jobs\ProcessApiGatewayLogsJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiGatewayLogsController extends Controller
{
    public function processLogFile(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'log_path' => 'required|string'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            }

            $this->dispatchProcessApiGatewayLogsJob($request->log_path);

            return response()->json([
                'success' => true,
                'message' => 'Added log file processing to queue!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error when trying to process log file', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Could not to process log file',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function dispatchProcessApiGatewayLogsJob(string $logPath): void
    {
        ProcessApiGatewayLogsJob::dispatch($logPath);
    }

    public function exportReportOfRequestsByService(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'log_path' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            }

            $this->dispatchBuildReportRequestsByServiceJob($request->log_path);

            return response()->json([
                'success' => true,
                'message' => 'Log report export was added to the queue!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error when trying to export report', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error when trying to export report',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function dispatchBuildReportRequestsByServiceJob(string $logPath): void
    {
        BuildReportRequestsByServiceJob::dispatch($logPath);
    }

    public function exportReportOfRequestsByConsumer(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'log_path' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            }

            $this->dispatchBuildReportRequestsByConsumerJob($request->log_path);

            return response()->json([
                'success' => true,
                'message' => 'Log report export was added to the queue!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error when trying to export report', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error when trying to export report',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function dispatchBuildReportRequestsByConsumerJob(string $logPath): void
    {
        BuildReportRequestsByConsumerJob::dispatch($logPath);
    }

    public function exportReportOfAverageTimeByService(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'log_path' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            }

            $this->dispatchBuildReportOfAverageTimeByService($request->log_path);

            return response()->json([
                'success' => true,
                'message' => 'Log report export was added to the queue!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error when trying to export report', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error when trying to export report',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function dispatchBuildReportOfAverageTimeByService(string $logPath): void
    {
        BuildAverageTimeReportByServiceJob::dispatch($logPath);
    }
}
