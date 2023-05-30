<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_gateway_logs', function (Blueprint $table) {
            $table->id();
            $table->jsonb('request')->nullable();
            $table->jsonb('upstream_uri')->nullable();
            $table->jsonb('response')->nullable();
            $table->jsonb('authenticated_entity')->nullable();
            $table->jsonb('route')->nullable();
            $table->jsonb('service')->nullable();
            $table->jsonb('latencies')->nullable();
            $table->jsonb('client_ip')->nullable();
            $table->jsonb('started_at')->nullable();
            $table->jsonb('log_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_gateway_logs');
    }
};
