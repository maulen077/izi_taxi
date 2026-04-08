<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->string('contact_phone')->nullable()->index()->after('passenger_id');
            $table->string('passenger_name')->nullable()->after('contact_phone');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete()->after('driver_id');
            $table->text('notes')->nullable()->after('dropoff_address');
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_user_id');
            $table->dropColumn(['contact_phone', 'passenger_name', 'notes']);
        });
    }
};
