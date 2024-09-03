<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbacksTableSeeder extends Seeder
{
    public function run()
    {
        $params = [
            [
                'user_id' => 1,
                'shop_id' => 1,
                'rating' => 5,
                'comment' => 'Excellent service and great food!',
                'image' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg',
            ],
            [
                'user_id' => 2,
                'shop_id' => 2,
                'rating' => 4,
                'comment' => 'Nice ambiance, but the food was average.',
                'image' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/italian.jpg',

            ],
            [
                'user_id' => 3,
                'shop_id' => 3,
                'rating' => 3,
                'comment' => 'Okay experience, but could be improved.',
                'image' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/yakiniku.jpg',
            ],
            [
                'user_id' => 4,
                'shop_id' => 4,
                'rating' => 2,
                'comment' => 'Not satisfied with the service.',
                'image' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/italian.jpg',
            ],
            [
                'user_id' => 5,
                'shop_id' => 5,
                'rating' => 1,
                'comment' => 'Terrible experience, would not recommend.',
                'image' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg',
            ],
        ];

        DB::table('feedbacks')->insert($params);
    }
}

