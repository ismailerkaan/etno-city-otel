<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $reservations = Reservation::query()
            ->with('roomType:id,slug,name')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reservations.index', compact('reservations', 'status'));
    }

    public function show(Reservation $reservation): View
    {
        return view('admin.reservations.show', ['reservation' => $reservation->load('roomType.images')]);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Reservation::STATUS_PAYMENT_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_CANCELLED,
            ])],
        ]);

        $reservation->update($validated);

        return back()->with('success', 'Rezervasyon durumu güncellendi.');
    }
}
