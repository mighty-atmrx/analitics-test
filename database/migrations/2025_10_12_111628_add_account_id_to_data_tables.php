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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropForeign('account_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropForeign('account_id');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropForeign('account_id');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropForeign('account_id');
        });
    }
};
