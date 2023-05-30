<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiGatewayLogs extends Model
{
    use HasFactory;

    protected $table = 'api_gateway_logs';

    protected $fillable = [
        'id',
        'request',
        'upstream_uri',
        'response',
        'authenticated_entity',
        'route',
        'service',
        'latencies',
        'client_ip',
        'started_at',
        'log_path'
    ];

    public function toArray(): array
    {
        return [
            'request' => $this->request,
            'upstream_uri' => $this->upstream_uri,
            'response' => $this->response,
            'authenticated_entity' => $this->authenticated_entity,
            'route' => $this->route,
            'service' => $this->service,
            'latencies' => $this->latencies,
            'client_ip' => $this->client_ip,
            'started_at' => $this->started_at,
            'log_path' => $this->log_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
