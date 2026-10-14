<?php

namespace Database\Seeders;

use App\Models\ReservationGuarantee;
use Illuminate\Database\Seeder;

class ReservationGuaranteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationGuarantee::seedDefaults();
    }
}
