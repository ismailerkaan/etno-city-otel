<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'roomTypeCount' => RoomType::query()->count(),
            'activeReservationCount' => Reservation::query()->whereIn('status', [Reservation::STATUS_PAYMENT_PENDING, Reservation::STATUS_CONFIRMED])->count(),
            'todayCheckInCount' => Reservation::query()->whereDate('check_in', today())->where('status', Reservation::STATUS_CONFIRMED)->count(),
            'pendingPaymentCount' => Reservation::query()->where('status', Reservation::STATUS_PAYMENT_PENDING)->count(),
        ]);
    }
}
