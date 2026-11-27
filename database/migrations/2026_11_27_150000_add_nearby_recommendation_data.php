<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('clinic_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('credentials_verified')->default(false);
            $table->boolean('available')->default(true);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::create('doctor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_reviews');

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('doctor_id');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'clinic_address',
                'latitude',
                'longitude',
                'credentials_verified',
                'available',
            ]);
        });
    }
};
