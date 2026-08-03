<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return Booking::with('car')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'days' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
        ]);

        $car = Car::findOrFail($data['car_id']);

        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $days = (int) $data['days'];
        $endDate = $startDate->copy()->addDays($days - 1);

        $overlap = Booking::where('car_id', $car->id)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'This car is already booked for the selected dates.',
            ], 422);
        }

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'car_id' => $car->id,
            'customer_name' => $data['customer_name'],
            'phone' => $data['phone'],
            'start_date' => $startDate->toDateString(),
            'days' => $days,
            'end_date' => $endDate->toDateString(),
            'total_price' => $car->price_per_day * $days,
            'status' => 'confirmed',
        ]);

        return $booking->load('car');
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking is already cancelled'], 422);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return $booking->load('car');
    }

    public function adminIndex()
    {
        return Booking::with(['car', 'user'])->latest()->get();
    }
}
