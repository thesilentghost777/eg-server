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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('error_type')->nullable(); // Exception class name
            $table->text('error_message');
            $table->longText('stack_trace')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('line_number')->nullable();
            $table->string('request_method')->nullable();
            $table->text('request_url')->nullable();
            $table->json('request_data')->nullable(); // POST/GET data
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->timestamp('error_time');
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['error_time', 'error_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};