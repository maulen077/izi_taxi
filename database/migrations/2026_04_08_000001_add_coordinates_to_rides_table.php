<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->decimal('pickup_lat', 10, 7)->nullable()->after('pickup_address');
            $table->decimal('pickup_lng', 10, 7)->nullable()->after('pickup_lat');
            $table->decimal('destination_lat', 10, 7)->nullable()->after('destination_address');
            $table->decimal('destination_lng', 10, 7)->nullable()->after('destination_lat');
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_lat',
                'pickup_lng',
                'destination_lat',
                'destination_lng',
            ]);
        });
    }
};
