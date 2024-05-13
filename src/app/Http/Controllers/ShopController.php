<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Requests\ShopUpdateRequest;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\AwsS3v3\AwsS3Adapter;


class ShopController extends Controller
{

    public function index()
    {
        $shops = Shop::with('area', 'genre')->get();
        return view('index', compact('shops'));
    }

    public function detail($shop_id)
    {
        $shop = $this->findShopById($shop_id);

        return view('detail', ['shop' => $shop]);
    }

    private function findShopById($shop_id)
    {
        return Shop::findOrFail($shop_id);
    }

    public function search(Request $request)
    {
        $area = $request->input('area_id');
        $genre = $request->input('genre_id');
        $keyword = $request->input('search');

        $query = Shop::query();

        $this->applyFilters($query, $area, $genre, $keyword);

        $filteredShops = $query->get();

        return view('index', compact('filteredShops'));
    }

    private function applyFilters($query, $area, $genre, $keyword)
    {
        if (!empty($genre)) {
            $query->where('genre_id', $genre);
        }

        if (!empty($area)) {
            $query->where('area_id', $area);
        }

        if ($keyword) {
            $query->where('shop_name', 'like', '%' . $keyword . '%');
        }
    }


    public function showCreateUpdateForm($id)
    {
        $shop = $this->findShopById($id);
        $images = Storage::disk('s3')->files('atte-ui');

        return view('shops.shop', compact('shop', 'images'));
    }


    public function update(ShopUpdateRequest $request, $id)
    {
        $shop = $this->findShopById($id);

        $this->updateShop($shop, $request);

        $shop->update([
        'photo_url' => $request->photo_url,
    ]);

        return redirect()->back()->with('success', '店舗情報を変更しました。');

    }

    private function updateShop($shop, $request)
    {
        $shop->update([
            'shop_name' => $request->shop_name,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'description' => $request->description,
            'photo_url' => $request->photo_url,
        ]);
    }

    public function showUploadForm()
    {
        $shopId = auth('shop')->user()->shop_id;
        return view('shops.upload',['shopId' => $shopId]);
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();

            $path = 'atte-ui/' . $fileName;

            Storage::disk('s3')->put($path, file_get_contents($image));

            $url = Storage::disk('s3')->url($path);

            return response()->json(['message' => '画像のアップロードに成功しました。', 'url' => $url], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => '画像のアップロード中にエラーが発生しました'], 500);
        }
    }



}
