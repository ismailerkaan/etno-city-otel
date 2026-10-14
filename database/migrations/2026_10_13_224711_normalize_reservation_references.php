<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $reservationIds = DB::table('reservations')->orderBy('id')->pluck('id');

            foreach ($reservationIds as $reservationId) {
                DB::table('reservations')->where('id', $reservationId)->update([
                    'reference' => 'TMP-'.$reservationId,
                ]);
            }

            foreach ($reservationIds as $reservationId) {
                DB::table('reservations')->where('id', $reservationId)->update([
                    'reference' => 'Rez-'.(999 + (int) $reservationId),
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            $reservationIds = DB::table('reservations')->orderBy('id')->pluck('id');

            foreach ($reservationIds as $reservationId) {
                DB::table('reservations')->where('id', $reservationId)->update([
                    'reference' => 'TMP-'.$reservationId,
                ]);
            }

            foreach ($reservationIds as $reservationId) {
                DB::table('reservations')->where('id', $reservationId)->update([
                    'reference' => 'RSV-'.str_pad((string) $reservationId, 12, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }
};
