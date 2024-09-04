<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use App\Models\Feedback;
use App\Http\Requests\StoreFeedbackRequest;


class FeedbackController extends Controller
{
    public function index($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $feedbacks = Feedback::where('shop_id', $shopId)->get();

        return view('feedbacks.index', compact('shop', 'feedbacks'));
    }

    public function create($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        return view('feedbacks.create', compact('shop'));
    }

    public function storeFeedback(StoreFeedbackRequest $request, $shopId)
    {
        $userId = auth()->id();

        try {
            DB::beginTransaction();

            $feedback = new Feedback();
            $feedback->rating = $request->input('rating');
            $feedback->comment = $request->input('comment');
            $feedback->shop_id = $shopId;
            $feedback->user_id = $userId;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/feedback_images');
                $feedback->image = basename($imagePath);
            }

            $feedback->save();

            DB::commit();

            return redirect()->route('home', $shopId);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function edit($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $shop = Shop::findOrFail($feedback->shop_id);

        return view('feedbacks.edit', compact('feedback', 'shop'));
    }

    public function update(StoreFeedbackRequest $request, $feedbackId)
    {
        $userId = auth()->id();
        $feedback = Feedback::findOrFail($feedbackId);

        if ($feedback->user_id !== $userId) {
            return redirect()->back()->with('error', '権限がありません。');
        }

        $feedback->rating = $request->input('rating');
        $feedback->comment = $request->input('comment');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/feedback_images');
            $feedback->image = basename($imagePath);
        }

        $feedback->save();

        return redirect()->route('detail', $feedback->shop_id);
    }

    public function destroy($feedbackId)
    {
        $userId = auth()->id();
        $feedback = Feedback::findOrFail($feedbackId);

        if ($feedback->user_id !== $userId) {
            return redirect()->back();
        }

        $feedback->delete();

        return redirect()->back();;
    }

    public function showShops()
    {
        $shops = Shop::all();
        return view('admin.list', compact('shops'));
    }

    public function showFeedbacks($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $feedbacks = Feedback::where('shop_id', $shopId)->get();

        return view('admin.feedbacks', compact('shop', 'feedbacks'));
    }

    public function adminDestroy($feedbackId)
    {
        $user = auth()->user();

        $feedback = Feedback::findOrFail($feedbackId);
        $feedback->delete();

        return redirect()->back();
    }
}

