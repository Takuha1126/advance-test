<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class DeleteExpiredReservations extends Command
{
    protected $signature = 'reservations:delete-expired';
    protected $description = 'Delete expired reservations';

    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now();

        $expiredReservations = Reservation::whereDate('date', $currentDate)
                                        ->where('reservation_time', '<', $currentTime->subMinutes(15))
                                        ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
        }

        $this->info('Expired reservations for today deleted successfully.');
    }
}
