<?php

namespace App\Http\Controllers;


use App\Models\Shop;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Genre;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImportCsvRequest;


class CsvImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.import');
    }

    public function import(ImportCsvRequest $request)
{
    $file = $request->file('csv_file');
    $path = $file->getRealPath();

    DB::beginTransaction();

    try {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $records = Statement::create()->process($csv);

        $errors = [];

        foreach ($records as $record) {
            try {
                $this->validateRecord($record);

                $genreId = $this->getGenreId($record['genre']);
                $areaId = $this->getAreaId($record['area']);

                if (!$genreId || !$areaId) {
                    throw new \Exception('無効なジャンルまたは地域です');
                }

                $photoUrl = $this->processImageUrl($record['image_url']);

                Shop::create([
                    'shop_name' => $record['shop_name'],
                    'genre_id' => $genreId,
                    'area_id' => $areaId,
                    'description' => $record['description'],
                    'photo_url' => $photoUrl,
                ]);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            DB::rollBack();
            return redirect()->route('import.form')->with('error', 'CSVインポートに失敗しました: ' . implode(', ', $errors));
        }

        DB::commit();
        return redirect()->route('import.form')->with('success', 'CSVインポートが完了しました');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('import.form')->with('error', 'CSVインポートに失敗しました: ' . $e->getMessage());
    }
}

    private function validateRecord($record)
    {
        if (strlen($record['shop_name']) > 50) {
            throw new \Exception('店舗名が50文字を超えています');
        }

        $validAreas = ['東京都', '大阪府', '福岡県'];
        if (!in_array($record['area'], $validAreas)) {
            throw new \Exception('無効な地域です');
        }

        $validGenres = ['寿司', '焼肉', 'イタリアン', '居酒屋', 'ラーメン'];
        if (!in_array($record['genre'], $validGenres)) {
            throw new \Exception('無効なジャンルです');
        }

        if (strlen($record['description']) > 400) {
            throw new \Exception('店舗概要が400文字を超えています');
        }

        $imageUrl = $record['image_url'];
        $extension = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpeg', 'jpg', 'png'];

        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception('無効な画像形式です');
        }

        if (filter_var($imageUrl, FILTER_VALIDATE_URL) === false && !file_exists($imageUrl)) {
            throw new \Exception('無効な画像URLまたはローカルファイルです');
        }
    }

    private function getGenreId($genreName)
    {
        $genre = Genre::where('genre_name', $genreName)->first();
        return $genre ? $genre->id : null;
    }

    private function getAreaId($areaName)
    {
        $area = Area::where('area_name', $areaName)->first();
        return $area ? $area->id : null;
    }

    private function processImageUrl($imageUrl)
    {
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $imageContents = file_get_contents($imageUrl);
            $imageName = basename($imageUrl);
            $localPath = storage_path('app/public/images/' . $imageName);

            if (!file_exists(dirname($localPath))) {
                mkdir(dirname($localPath), 0755, true);
            }

            file_put_contents($localPath, $imageContents);

            return 'storage/images/' . $imageName;
        } else {
            $filePath = $imageUrl;

            if (!file_exists($filePath)) {
                throw new \Exception('ローカルファイルが見つかりません: ' . $filePath);
            }

            $imageName = basename($filePath);
            $localPath = storage_path('app/public/images/' . $imageName);

            if (!file_exists(dirname($localPath))) {
                mkdir(dirname($localPath), 0755, true);
            }

            copy($filePath, $localPath);

            return 'storage/images/' . $imageName;
        }
    }

}
