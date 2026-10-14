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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 26)->unique();
            $table->foreignId('room_type_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('payment_pending');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('nights');
            $table->unsignedTinyInteger('adults');
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('guest_title', 20)->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->char('country_code', 2)->nullable();
            $table->text('special_requests')->nullable();
            $table->string('room_name');
            $table->decimal('nightly_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->char('currency', 3)->default('TRY');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
