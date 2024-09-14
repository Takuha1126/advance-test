<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreFeedbackRequest;




class EvaluationController extends Controller
{
    public function index($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $userId = auth()->id();

        $existingReview = Review::where('shop_id', $shopId)->where('user_id', $userId)->first();

        return view('evaluations.show', ['shop' => $shop, 'review' => $existingReview]);
    }


    public function store(StoreFeedbackRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $review = Review::updateOrCreate(
                ['shop_id' => $request->shop_id, 'user_id' => auth()->id()],
                [
                    'rating' => $validatedData['rating'],
                    'comment' => $validatedData['comment'],
                ]
            );

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/review_images');
                $review->image = basename($imagePath);
                $review->save();
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('home', ['shopId' => $request->shop_id]);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'レビューの保存に失敗しました。');
        }
    }


    public function show(Request $request)
    {
        $shopId = auth('shop')->user()->shop_id;
        $reviews = Review::where('shop_id', $shopId)->latest()->paginate(4);
        return view('shops.reviews', compact('shopId', 'reviews'));
    }

}

