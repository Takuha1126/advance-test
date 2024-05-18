<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            [
                'reservation_id' => 1,
                'paid_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            ],
            [
                'reservation_id' => 2,
                'paid_at' => Carbon::now()->subDays(2)->toDateTimeString(),
            ],
            [
                'reservation_id' => 3,
                'paid_at' => Carbon::now()->subDays(3)->toDateTimeString(),
            ],
            [
                'reservation_id' => 4,
                'paid_at' => Carbon::now()->subDays(4)->toDateTimeString(),
            ],
            [
                'reservation_id' => 5,
                'paid_at' => Carbon::now()->subDays(5)->toDateTimeString(),
            ],
        ];

        DB::table('payments')->insert($params);
    }
}
