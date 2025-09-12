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
        Schema::table('automobiles', function (Blueprint $table) {
            $table->string('vin')->unique()->after('year');
            $table->string('body_type')->nullable()->after('color');
            $table->string('engine')->nullable()->after('engine_size');
            $table->json('features')->nullable()->after('engine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('automobiles', function (Blueprint $table) {
            $table->dropColumn(['vin', 'body_type', 'engine', 'features']);
        });
    }
};
