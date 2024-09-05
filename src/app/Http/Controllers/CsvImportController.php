<?php

namespace App\Http\Controllers;


use App\Models\Shop;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Genre;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImportCsvRequest;
use App\Services\CsvImportService;


class CsvImportController extends Controller
{
    protected $csvImportService;

    public function __construct(CsvImportService $csvImportService)
    {
        $this->csvImportService = $csvImportService;
    }

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

                $requiredHeaders = ['shop_name', 'area', 'genre', 'description', 'image_url'];
                $headers = $csv->getHeader();
                if ($headers !== $requiredHeaders) {
                    throw new \Exception('CSVファイルに必要なヘッダーが含まれていません。');
                }

                $records = Statement::create()->process($csv);
                $recordsArray = iterator_to_array($records);

                $validationResult = $this->csvImportService->validateRecords($recordsArray);

                if ($validationResult !== true) {
                    DB::rollBack();
                    $errors = array_reduce($validationResult, function($carry, $item) {
                        return $carry . "行 {$item['row']}: " . implode(', ', $item['errors']) . "\n";
                    }, '');
                    return redirect()->route('import.form')->with('error', "CSVインポートに失敗しました:\n" . $errors);
                }

                $errors = [];

                foreach ($recordsArray as $record) {
                    try {
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