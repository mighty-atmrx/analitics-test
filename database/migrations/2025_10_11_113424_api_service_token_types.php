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
        Schema::create('api_service_token_types', function (Blueprint $table) {
            $table->unsignedBigInteger('api_service_id');
            $table->foreign('api_service_id')->references('id')->on('api_services')->onDelete('cascade');

            $table->unsignedBigInteger('token_type_id');
            $table->foreign('token_type_id')->references('id')->on('token_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('api_service_token_types');
    }
};
