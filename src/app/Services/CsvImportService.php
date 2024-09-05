<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class CsvImportService
{
    /**
     * CSVレコードのバリデーションを行います。
     *
     * @param array $records
     * @return array|bool
     */
    public function validateRecords(array $records)
    {
        $errors = [];

        foreach ($records as $index => $record) {
            $validator = Validator::make($record, [
                'shop_name' => 'required|max:50',
                'area' => 'required|in:東京都,大阪府,福岡県',
                'genre' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                'description' => 'required|max:400',
                'image_url' => 'nullable', // 'url' ルールを削除
            ], [
                'shop_name.required' => '店舗名が入力されていません。',
                'shop_name.max' => '店舗名が50文字を超えています。',
                'area.required' => '地域が入力されていません。',
                'area.in' => '無効な地域です。',
                'genre.required' => 'ジャンルが入力されていません。',
                'genre.in' => '無効なジャンルです。',
                'description.required' => '店舗概要が入力されていません。',
                'description.max' => '店舗概要が400文字を超えています。',
                'image_url.url' => '無効な画像URLです。',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 1,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            if (isset($record['image_url']) && !empty($record['image_url'])) {
                $imageUrl = $record['image_url'];
                $extension = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpeg', 'jpg', 'png'];

                if (!in_array($extension, $allowedExtensions)) {
                    $errors[] = [
                        'row' => $index + 1,
                        'errors' => ['画像形式が無効です。jpeg または png 形式のみ対応しています。']
                    ];
                }
            }
        }

        return !empty($errors) ? $errors : true;
    }
}
