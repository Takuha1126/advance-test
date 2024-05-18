<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationCancelled;

class DeleteExpiredReservations extends Command
{
    protected $signature = 'reservations:delete-expired';
    protected $description = 'Delete expired reservations';

    public function handle()
{
    $currentDate = now()->toDateString();
    $currentTime = now();

    $expiredReservations = Reservation::whereDate('date', $currentDate)
                                    ->where('reservation_time', '<', $currentTime->subMinutes(15))
                                    ->get();

    foreach ($expiredReservations as $reservation) {
        $reservation->payment()->delete();

        Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));

        $reservation->delete();
    }

    $this->info('Expired reservations for today deleted successfully.');
}

}
