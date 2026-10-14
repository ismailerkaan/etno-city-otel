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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_method', 30)->nullable()->after('currency');
            $table->string('payment_card_brand', 30)->nullable()->after('payment_method');
            $table->char('payment_card_last_four', 4)->nullable()->after('payment_card_brand');
            $table->string('payment_transaction_reference', 40)->nullable()->unique()->after('payment_card_last_four');
            $table->timestamp('paid_at')->nullable()->after('payment_transaction_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropUnique(['payment_transaction_reference']);
            $table->dropColumn([
                'payment_method',
                'payment_card_brand',
                'payment_card_last_four',
                'payment_transaction_reference',
                'paid_at',
            ]);
        });
    }
};
