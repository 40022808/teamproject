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
    \Log::info('Request data:', $request->all()); 
    $validated = $request->validate([
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'gender' => 'required|string',
    ]);
    $alreadyBooked = Booking::where('date', $validated['date'])
                            ->where('time', $validated['time'])
                            ->exists();

    if ($alreadyBooked) {
        return response()->json(['success' => false, 'message' => 'This time slot is already booked.'], 400);
    }

    // Ha nem foglalt, mentjük a foglalást
    $booking = new Booking();
    $booking->date = $validated['date'];
    $booking->time = $validated['time'];
    $booking->gender = $validated['gender'];
    $booking->save();

    return response()->json(['success' => true, 'booking' => $booking]);
}


public function getBookedDates()
{
    $bookings = Booking::select('date', 'time')->get(); // Dátum és idő lekérdezése

    return response()->json(['bookings' => $bookings]);
}
}
