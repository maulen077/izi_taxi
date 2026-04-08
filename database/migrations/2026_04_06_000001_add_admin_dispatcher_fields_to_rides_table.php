<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rides', 'contact_phone')) {
            Schema::table('rides', function (Blueprint $table) {
                $table->string('contact_phone')->nullable()->index()->after('passenger_id');
            });
        }

        if (! Schema::hasColumn('rides', 'passenger_name')) {
            Schema::table('rides', function (Blueprint $table) {
                $table->string('passenger_name')->nullable()->after('contact_phone');
            });
        }

        if (! Schema::hasColumn('rides', 'created_by_user_id')) {
            Schema::table('rides', function (Blueprint $table) {
                $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete()->after('driver_id');
            });
        }

        if (! Schema::hasColumn('rides', 'notes')) {
            Schema::table('rides', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('destination_address');
            });
        }
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            if (Schema::hasColumn('rides', 'created_by_user_id')) {
                $table->dropConstrainedForeignId('created_by_user_id');
            }
        });

        Schema::table('rides', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('rides', 'contact_phone')) {
                $columns[] = 'contact_phone';
            }

            if (Schema::hasColumn('rides', 'passenger_name')) {
                $columns[] = 'passenger_name';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
