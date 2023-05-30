<?php

namespace Database\Factories;

use App\Models\ApiGatewayLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiGatewayLogsFactory extends Factory
{
    protected $model = ApiGatewayLogs::class;

    public function definition()
    {
        return [
            'request' => [
                'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
                'uri' => '/',
                'url' => $this->faker->url(),
                'size' => $this->faker->numberBetween(100, 1000),
                'querystring' => [],
                'headers' => [
                    'accept' => '*/*',
                    'host' => $this->faker->domainName(),
                    'user-agent' => $this->faker->userAgent(),
                ],
            ],
            'upstream_uri' => '/',
            'response' => [
                'status' => $this->faker->numberBetween(200, 500),
                'size' => $this->faker->numberBetween(500, 2000),
                'headers' => [
                    'Content-Length' => $this->faker->numberBetween(100, 200),
                    'via' => 'gateway/1.3.0',
                    'Connection' => 'close',
                    'access-control-allow-credentials' => 'true',
                    'Content-Type' => 'application/json',
                    'server' => 'nginx',
                    'access-control-allow-origin' => '*',
                ],
            ],
            'authenticated_entity' => [
                'consumer_id' => [
                    'uuid' => $this->faker->uuid(),
                ],
            ],
            'route' => [
                'created_at' => $this->faker->unixTime(),
                'hosts' => $this->faker->domainName(),
                'id' => $this->faker->uuid(),
                'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'],
                'paths' => ['/', '/api'],
                'preserve_host' => $this->faker->boolean(),
                'protocols' => ['http', 'https'],
                'regex_priority' => $this->faker->numberBetween(0, 10),
                'service' => [
                    'id' => $this->faker->uuid(),
                ],
                'strip_path' => $this->faker->boolean(),
                'updated_at' => $this->faker->unixTime(),
            ],
            'service' => [
                'connect_timeout' => 60000,
                'created_at' => $this->faker->unixTime(),
                'host' => $this->faker->domainName(),
                'id' => $this->faker->uuid(),
                'name' => $this->faker->word(),
                'path' => '/',
                'port' => 80,
                'protocol' => 'http',
                'read_timeout' => 60000,
                'retries' => $this->faker->numberBetween(1, 10),
                'updated_at' => $this->faker->unixTime(),
                'write_timeout' => 60000,
            ],
            'latencies' => [
                'proxy' => $this->faker->numberBetween(0, 2000),
                'gateway' => $this->faker->numberBetween(0, 200),
                'request' => $this->faker->numberBetween(0, 2000),
            ],
            'client_ip' => $this->faker->ipv4(),
            'started_at' => $this->faker->unixTime(),
            'log_path' => 'tests/logs.txt',
        ];
    }
}
