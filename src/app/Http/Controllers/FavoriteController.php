<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function getFavoriteStatus($shopId)
    {
        $userId = Auth::id();
        $isFavorite = Favorite::where('user_id', $userId)
                            ->where('shop_id', $shopId)
                            ->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }

    public function toggle(Request $request, $shopId)
    {
        $userId = Auth::id();

        DB::beginTransaction();
        try {
            $existingFavorite = Favorite::where('user_id', $userId)->where('shop_id', $shopId)->first();

            if ($existingFavorite) {
                $existingFavorite->delete();
                DB::commit();
                return response()->json(['status' => 'removed']);
            }

            Favorite::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
            ]);

            DB::commit();
            return response()->json(['status' => 'added']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
