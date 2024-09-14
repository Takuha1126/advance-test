<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Http\Requests\ShopUpdateRequest;


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
        $user = Auth::user();
        $feedbacks = Review::where('shop_id', $shop_id)
                            ->where('user_id', $user->id)
                            ->get();

        return view('detail', [
            'shop' => $shop,
            'feedbacks' => $feedbacks,
        ]);
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
        $images = Storage::disk('public')->files('uploaded_images');
        return view('shops.shop', compact('shop', 'images'));
    }

    public function update(ShopUpdateRequest $request, $id)
    {
        $shop = $this->findShopById($id);
        $this->updateShop($shop, $request);
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
        return view('shops.upload', ['shopId' => $shopId]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $path = 'uploaded_images/' . $fileName;

            $image->storeAs('public/uploaded_images', $fileName);

            $url = Storage::disk('public')->url('uploaded_images/' . $fileName);

            return response()->json([
                'success' => true,
                'message' => '画像のアップロードに成功しました。',
                'url' => $url
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '画像のアップロード中にエラーが発生しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listShops(Request $request)
    {
        $sort = $request->input('sort', 'random');

        $shops = Shop::with('reviews')->get()->map(function ($shop) {

            $shop->average_rating = $shop->reviews->avg('rating') ?? 0;
            $shop->feedback_count = $shop->reviews->count();

            $shop->weighted_rating = $shop->average_rating * $shop->feedback_count;

            return $shop;
        });

        $shopsWithRatings = $shops->filter(function ($shop) {
            return $shop->average_rating > 0;
        });

        $shopsWithoutRatings = $shops->filter(function ($shop) {
            return $shop->average_rating == 0;
        });

        switch ($sort) {
            case 'highest-rating':
                $shopsWithRatings = $shopsWithRatings->sort(function ($a, $b) {
                    return $b->weighted_rating <=> $a->weighted_rating;
                })->values();
                $shopsWithoutRatings = $shopsWithoutRatings->sortBy('id')->values();
                break;
            case 'lowest-rating':
                $shopsWithRatings = $shopsWithRatings->sort(function ($a, $b) {
                    return $a->weighted_rating <=> $b->weighted_rating;
                })->values();
                $shopsWithoutRatings = $shopsWithoutRatings->sortBy('id')->values();
                break;
            case 'random':
                $shops = $shops->shuffle()->values();
                $shopsWithRatings = $shops->filter(function ($shop) {
                    return $shop->average_rating > 0;
                });
                $shopsWithoutRatings = $shops->filter(function ($shop) {
                    return $shop->average_rating == 0;
                });
                break;
            default:
                $shopsWithRatings = $shopsWithRatings->shuffle();
                $shopsWithoutRatings = $shopsWithoutRatings->sortBy('id')->values();
                break;
        }

        if ($sort !== 'random') {
            $shops = $shopsWithRatings->concat($shopsWithoutRatings)->values();
        } else {
            $shops = $shopsWithRatings->concat($shopsWithoutRatings)->shuffle()->values();
        }

        return view('index', ['shops' => $shops]);
    }


}

