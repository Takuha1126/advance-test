<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Http\Requests\StoreFeedbackRequest;


class FeedbackController extends Controller
{
    public function index($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $reviews = Review::where('shop_id', $shopId)->get();

        return view('feedbacks.index', compact('shop', 'reviews'));
    }

    public function create($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        return view('feedbacks.create', compact('shop'));
    }

    public function storeFeedback(StoreFeedbackRequest $request, $shopId)
    {
        $userId = auth()->id();

        $existingReview = Review::where('shop_id', $shopId)
                                ->where('user_id', $userId)
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', '既にこの店舗にレビューを送信しています。');
        }

        try {
            DB::beginTransaction();

            $review = new Review();
            $review->rating = $request->input('rating');
            $review->comment = $request->input('comment');
            $review->shop_id = $shopId;
            $review->user_id = $userId;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/review_images');
                $review->image = basename($imagePath);
            }

            $review->save();

            DB::commit();

            return redirect()->route('home', $shopId);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'レビューの送信に失敗しました。');
        }
    }

    public function edit($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $shop = Shop::findOrFail($review->shop_id);

        return view('feedbacks.edit', compact('review', 'shop'));
    }

    public function update(StoreFeedbackRequest $request, $reviewId)
    {
        $userId = auth()->id();
        $review = Review::findOrFail($reviewId);

        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/review_images');
            $review->image = basename($imagePath);
        }

        $review->save();

        return redirect()->route('detail', $review->shop_id);
    }

    public function destroy($reviewId)
    {
        $userId = auth()->id();
        $review = Review::findOrFail($reviewId);

        if ($review->user_id !== $userId) {
            return redirect()->back();
        }

        $review->delete();
        return redirect()->back();
    }

    public function showShops()
    {
        $shops = Shop::all();
        return view('admin.list', compact('shops'));
    }

    public function showFeedbacks($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $reviews = Review::where('shop_id', $shopId)->get();

        return view('admin.feedbacks', compact('shop', 'reviews'));
    }

    public function adminDestroy($reviewId)
    {
        $user = auth()->user();

        $review = Review::findOrFail($reviewId);
        $review->delete();

        return redirect()->back();
    }
}
