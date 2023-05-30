<?php

namespace App\Services;

use App\Models\ApiGatewayLogs;
use Exception;

class ProcessApiGatewayLogsService
{
    public function __construct(
        private string $logPath,
        private ApiGatewayLogs $apiGatewayLogs
    ){}

    public function processLogFile(): void
    {
        $handle = fopen($this->logPath, 'r');

        if ($handle === false) {
            throw new Exception("Failed to open log file: $this->logPath");
        }

        $batchSize = 100; // lines per step
        $validObjects = [];

        while (($line = fgets($handle)) !== false) {
            $object = json_decode($line);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validObjects[] = new $this->apiGatewayLogs([
                    'request' => json_encode($object->request),
                    'upstream_uri' => json_encode($object->upstream_uri),
                    'response' => json_encode($object->response),
                    'authenticated_entity' => json_encode($object->authenticated_entity),
                    'route' => json_encode($object->route),
                    'service' => json_encode($object->service),
                    'latencies' => json_encode($object->latencies),
                    'client_ip' => json_encode($object->client_ip),
                    'started_at' => json_encode($object->started_at),
                    'log_path' => json_encode($this->logPath)
                ]);
            }

            if (count($validObjects) >= $batchSize) {
                $this->insertObjects($validObjects);
                $validObjects = []; // clear objects to next step
            }
        }

        fclose($handle);

        // Inserts any objects that remained
        if (!empty($validObjects)) {
            $this->insertObjects($validObjects);
        }
    }

    private function insertObjects(array $validObjects): void
    {
        $chunks = array_chunk($validObjects, 10);

        foreach ($chunks as $chunk) {
            $validArrays = array_map(function ($object) {
                return $object->toArray();
            }, $chunk);

            $this->apiGatewayLogs->insert($validArrays);
        }
    }
}
