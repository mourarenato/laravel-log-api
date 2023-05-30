<?php

namespace App\Services;

use App\Models\ApiGatewayLogs;

class BuildLogReportService
{
    public function __construct(
        private string $logPath
    ){}

    public function exportReportOfRequestsByConsumer($requestsByConsumerPath = "files/requests-by-consumer.csv"): void
    {
        $filename = public_path($requestsByConsumerPath);
        $handle = fopen($filename, 'w');

        fputcsv($handle, [
            "Consumidor (UUID)",
            "Requisições"
        ]);

        $logs = ApiGatewayLogs::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(authenticated_entity, '$.consumer_id.uuid')) as consumer_uuid, COUNT(*) as count")
            ->where('log_path', $this->logPath)
            ->groupBy('consumer_uuid')
            ->get();

        foreach ($logs as $log) {
            $consumerId = $log->consumer_uuid;

            fputcsv($handle, [
                $consumerId,
                $log->count,
            ]);
        }

        fclose($handle);
    }

    public function exportReportOfRequestsByService($requestsByServicePath = "files/requests-by-service.csv"): void
    {
        $filename = public_path($requestsByServicePath);
        $handle = fopen($filename, 'w');

        fputcsv($handle, [
            "Service ID",
            "Service name",
            "Requisições"
        ]);

        $logs = ApiGatewayLogs::where('log_path', $this->logPath)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(service, '$.id')) as service_id, COUNT(*) as count")
            ->groupBy('service_id')
            ->get();

        foreach ($logs as $log) {
            $serviceId = $log->service_id;
            $count = $log->count;

            $service = ApiGatewayLogs::where('log_path', $this->logPath)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(service, '$.id')) = ?", [$serviceId])
                ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(service, '$.name')) as service_name")
                ->first();

            if ($service) {
                fputcsv($handle, [
                    $serviceId,
                    $service->service_name,
                    $count,
                ]);
            }
        }

        fclose($handle);
    }

    public function exportReportOfAverageTimeByService($averageTimePath = "files/average-time-by-service.csv"): void
    {
        $filename = public_path($averageTimePath);
        $handle = fopen($filename, 'w');

        fputcsv($handle, [
            "Service",
            "Tempo médio de request",
            "Tempo médio de proxy",
            "Tempo médio de gateway"
        ]);

        $serviceTimes = [];

        ApiGatewayLogs::selectRaw('
        JSON_UNQUOTE(JSON_EXTRACT(service, "$.id")) as service_id,
        JSON_UNQUOTE(JSON_EXTRACT(service, "$.name")) as service_name,
        JSON_UNQUOTE(JSON_EXTRACT(latencies, "$.request")) as request_time,
        JSON_UNQUOTE(JSON_EXTRACT(latencies, "$.proxy")) as proxy_time,
        JSON_UNQUOTE(JSON_EXTRACT(latencies, "$.gateway")) as gateway_time')
            ->chunk(1000, function ($logs) use (&$serviceTimes) {
                foreach ($logs as $log) {
                    $serviceId = $log->service_id;
                    $serviceName = $log->service_name;

                    if (!isset($serviceTimes[$serviceId])) {
                        $serviceTimes[$serviceId] = [
                            'name' => $serviceName,
                            'requestTimes' => [],
                            'proxyTimes' => [],
                            'gatewayTimes' => [],
                        ];
                    }

                    $serviceTimes[$serviceId]['requestTimes'][] = $log->request_time;
                    $serviceTimes[$serviceId]['proxyTimes'][] = $log->proxy_time;
                    $serviceTimes[$serviceId]['gatewayTimes'][] = $log->gateway_time;
                }
            });

        foreach ($serviceTimes as $serviceId => $serviceData) {
            $requestAverage = number_format(array_sum($serviceData['requestTimes']) / count($serviceData['requestTimes']), 2);
            $proxyAverage = number_format(array_sum($serviceData['proxyTimes']) / count($serviceData['proxyTimes']), 2);
            $gatewayAverage = number_format(array_sum($serviceData['gatewayTimes']) / count($serviceData['gatewayTimes']), 2);

            fputcsv($handle, [
                $serviceData['name'],
                $requestAverage,
                $proxyAverage,
                $gatewayAverage
            ]);
        }

        fclose($handle);
    }
}

