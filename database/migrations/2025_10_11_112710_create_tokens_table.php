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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->dateTime('expires_at')->nullable();

            $table->text('refresh_token')->nullable();
            $table->dateTime('refresh_expires_at')->nullable();

            $table->string('login')->nullable();
            $table->string('password')->nullable();

            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedBigInteger('api_service_id');
            $table->foreign('api_service_id')->references('id')->on('api_services')->onDelete('cascade');

            $table->unsignedBigInteger('token_type_id');
            $table->foreign('token_type_id')->references('id')->on('token_types')->onDelete('cascade');

            $table->unique(['account_id', 'api_service_id', 'token_type_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
