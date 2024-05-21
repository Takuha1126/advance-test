<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;


class EvaluationController extends Controller
{
    public function index($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        return view('evaluations.show', ['shop' => $shop]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $review = new Review();
            $review->rating = $validatedData['rating'];
            $review->comment = $validatedData['comment'];
            $review->shop_id = $request->shop_id;
            $review->save();

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }


            return redirect()->route('home', ['shopId' => $request->shop_id]);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function show(Request $request)
    {
        $shopId = auth('shop')->user()->shop_id;
        $reviews = Review::where('shop_id', $shopId)->latest()->paginate(4);
        return view('shops.reviews', compact('shopId', 'reviews'));
    }
}
