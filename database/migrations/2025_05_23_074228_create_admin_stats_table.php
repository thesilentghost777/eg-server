<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->integer('connection_count')->default(0);
            $table->integer('request_count')->default(0);
            $table->integer('average_response_time')->default(0); // en millisecondes
            $table->integer('error_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_stats');
    }
};
