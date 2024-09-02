<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Genre;
use App\Models\Area;


class CsvImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.import');
    }

    public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('csv_file');
    $path = $file->getRealPath();

    try {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $records = Statement::create()->process($csv);

        foreach ($records as $record) {
            $this->validateRecord($record);

            $genreId = $this->getGenreId($record['genre']);
            $areaId = $this->getAreaId($record['area']);

            if (!$genreId || !$areaId) {
                throw new \Exception('無効なジャンルまたは地域です');
            }

            Shop::create([
                'shop_name' => $record['shop_name'],
                'genre_id' => $genreId,
                'area_id' => $areaId,
                'description' => $record['description'],
                'photo_url' => $record['image_url'],
            ]);
        }

        return redirect()->route('import.form')->with('success', 'CSVインポートが完了しました');
    } catch (\Exception $e) {
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
    $allowedExtensions = ['jpeg', 'jpg', 'png'];
    $extension = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        throw new \Exception('無効な画像形式です');
    }

    // URL形式の検証（オプション）
    if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        throw new \Exception('無効なURL形式です');
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


}
