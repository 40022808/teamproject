<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function checkBooking(Request $request)
{
    $date = $request->query('date');
    $time = $request->query('time');

    $booked = Booking::where('date', $date)
                     ->where('time', $time)
                     ->exists(); // Ellenőrizzük, hogy a dátum és idő foglalt-e

    return response()->json(['booked' => $booked]);
}

public function storeBooking(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'time' => 'required|date_format:H:i', // Idő validálása
    ]);

    $booking = new Booking();
    $booking->date = $validated['date'];
    $booking->time = $validated['time'];
    $booking->save();

    return response()->json(['success' => true, 'date' => $booking->date, 'time' => $booking->time]);
}

public function getBookedDates()
{
    $bookings = Booking::select('date', 'time')->get(); // Dátum és idő lekérdezése

    return response()->json(['bookings' => $bookings]);
}
}
