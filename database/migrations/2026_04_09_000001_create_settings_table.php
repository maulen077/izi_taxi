<?php

use App\Services\PricingSettingsService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            [
                'key' => PricingSettingsService::TAXI_RATE_PER_KM,
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => PricingSettingsService::DELIVERY_RATE_PER_KM,
                'value' => '200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
