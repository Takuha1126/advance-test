<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shopIds = Shop::pluck('id')->toArray();

        $params = [
            [
                'shop_id' => $shopIds[array_rand($shopIds)],
                'rating' => 5,
                'comment' => 'とても良いお店でした。',
            ],
            [
                'shop_id' => $shopIds[array_rand($shopIds)],
                'rating' => 4,
                'comment' => '普通より良いお店でした。',
            ],
            [
                'shop_id' => $shopIds[array_rand($shopIds)],
                'rating' => 3,
                'comment' => '普通です。',
            ],
            [
                'shop_id' => $shopIds[array_rand($shopIds)],
                'rating' => 2,
                'comment' => '普通よりは悪いお店でした。',
            ],
            [
                'shop_id' => $shopIds[array_rand($shopIds)],
                'rating' => 1,
                'comment' => '最悪の経験でした。',
            ],
        ];
        
        DB::table('reviews')->insert($params);
    }
}

