<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('mobile');
            $table->string('token_hash', 64)->unique();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('car_brand')->nullable();
            $table->string('car_model')->nullable();
            $table->unsignedSmallInteger('car_year')->nullable();
            $table->string('car_number')->nullable();
            $table->string('car_color')->nullable();
            $table->string('car_tariff')->default('economy');
            $table->longText('car_photo_front')->nullable();
            $table->longText('car_photo_side')->nullable();
            $table->longText('car_photo_interior')->nullable();
            $table->string('license_path')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('vehicle_registration_path')->nullable();
            $table->string('application_status')->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('saved_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('address');
            $table->boolean('is_recent')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('mode')->default('taxi');
            $table->string('tariff')->default('economy');
            $table->string('status')->default('searching');
            $table->string('pickup_address');
            $table->string('destination_address')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('base_price')->default(0);
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->boolean('has_luggage')->default(false);
            $table->string('luggage_size')->nullable();
            $table->string('sender_phone')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('waiting_minutes')->default(0);
            $table->unsignedTinyInteger('passenger_rating')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('car_brand');
            $table->string('car_model');
            $table->unsignedSmallInteger('car_year');
            $table->string('car_number');
            $table->string('car_color');
            $table->string('license_path')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('vehicle_registration_path')->nullable();
            $table->longText('car_photo_front')->nullable();
            $table->longText('car_photo_side')->nullable();
            $table->longText('car_photo_interior')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_applications');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('rides');
        Schema::dropIfExists('saved_addresses');
        Schema::dropIfExists('driver_profiles');
        Schema::dropIfExists('api_tokens');
    }
};
