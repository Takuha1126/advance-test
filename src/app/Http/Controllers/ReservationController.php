<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReservationRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\QueryException;
use App\Http\Requests\ReservationUpdateRequest;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCancelled;
use Illuminate\Support\Facades\Mail;
use App\Models\Payment;


class ReservationController extends Controller
{
    private function formatTime($time)
    {
        return date('H:i:s', strtotime($time));
    }

    private function redirectBack()
    {
        return redirect()->back();
    }

    private function redirectBackWithError($errorMessage)
    {
        return redirect()->back()->with('error', $errorMessage)->withInput();
    }

    private function redirectToDone()
    {
        return redirect()->route('done');
    }

    private function routeTo($route)
    {
        return redirect()->route($route);
    }

    public function completed()
    {
        return view('done');
    }

    public function store(ReservationRequest $request)
    {
        $userId = Auth::id();

        if ($this->checkDuplicateReservation($request, $userId)) {
            return $this->redirectBackWithError('同じ時間と場所に他の予約が既にあります。');
        }

        try {
            $reservation = $this->createReservation($request, $userId);

            Mail::to(Auth::user()->email)->send(new ReservationConfirmed($reservation));
        } catch (QueryException $e) {
            return $this->redirectBackWithError('データベースエラーが発生しました。');
        }

        return $this->redirectToDone();
    }

    private function checkDuplicateReservation($request, $userId)
    {
        return DB::transaction(function () use ($request, $userId) {
            $existingReservation = Reservation::where('shop_id', $request->shop_id)
                ->where('date', $request->date)
                ->where('reservation_time', $this->formatTime($request->reservation_time))
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            return $existingReservation !== null;
        });
    }

    private function createReservation($request, $userId)
    {
        $reservationData = $request->only(['shop_id', 'date', 'reservation_time', 'number_of_people', 'status']);
        $reservationData['user_id'] = $userId;
        $reservationData['reservation_time'] = $this->formatTime($request->reservation_time);

        return Reservation::create($reservationData);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        Payment::where('reservation_id', $id)->delete();

        Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));

        $reservation->delete();

        return redirect()->route('mypage')->with('success_message', '予約が削除されました。');
    }

    public function update(ReservationUpdateRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->updateReservation($reservation, $request);
        Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));
        return $this->redirectToDone();
    }

    private function updateReservation($reservation, $request)
    {
        $reservation->update([
            'date' => $request->input('new_date'),
            'number_of_people' => $request->input('new_number_of_people'),
            'reservation_time' => $request->input('new_reservation_time'),
        ]);
    }

    public function show($id)
    {
        $reservation = Reservation::where('id', $id)->first();
        if (!$reservation) {
            return $this->redirectBack();
        }
        $reservationData = json_encode([
            'id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'shop_id' => $reservation->shop_id,
            'date' => $reservation->date,
            'reservation_time' => $reservation->reservation_time,
            'number_of_people' => $reservation->number_of_people,
            'status' => $reservation->status,
        ]);
        $qrCode = QrCode::size(300)->generate($reservationData);
        return view('qr', compact('reservation', 'qrCode', 'reservationData'));
    }

    public function showReservationList()
    {
        $shopId = auth('shop')->user()->shop_id;
        $reservations = Reservation::where('shop_id', $shopId)->paginate(4);
        return view('shops.reservation', ['reservations' => $reservations, 'shopId' => $shopId]);
    }

    public function showQrVerification()
    {
        $shopId = auth('shop')->user()->shop_id;

        return view('shops.verify', ['shopId' => $shopId]);
    }

    public function verify(Request $request)
    {
    $qrCodeData = $request->input('qr_code_data');
    if (!$qrCodeData || empty($qrCodeData)) {
        return response()->json(['error' => '不正なデータ形式です'], 400);
    }
    $reservationData = json_decode($qrCodeData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json(['error' => 'JSONの構文が無効です'], 400);
    }
    if (!is_array($reservationData) || !isset($reservationData['id'])) {
        return response()->json(['error' => '不正なデータ形式です'], 400);
    }
    $reservation = Reservation::find($reservationData['id']);
    if (!$reservation) {
        return response()->json(['error' => '予約が見つかりません'], 404);
    }

    $loggedInShopId = auth('shop')->user()->shop_id;

    $reservationShopId = $reservation->shop_id;

    if ($loggedInShopId !== $reservationShopId) {
        return response()->json(['error' => '他の店舗のQRコードです'], 403);
    }

    $currentDate = now()->toDateString();
    if ($reservation->date !== $currentDate) {
        return response()->json(['error' => '予約の日付が異なります'], 400);
    }


        $responseData = [
            'name' => $reservation->user->name,
            'date' => $reservation->date,
            'time' => $reservation->reservation_time,
            'number_of_people' => $reservation->number_of_people,
        ];

        $message = '予約が正常に確認されました';

    $reservation->delete();

    $responseData['message'] = $message;

    return response()->json($responseData);
    }
}
