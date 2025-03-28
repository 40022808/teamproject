<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;

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
        'time' => 'required|date_format:H:i',
        'gender' => 'required|string',
        'email' => 'required|email',
    ]);

    $alreadyBooked = Booking::where('date', $validated['date'])
                            ->where('time', $validated['time'])
                            ->exists();

    if ($alreadyBooked) {
        return response()->json(['success' => false, 'message' => 'This time slot is already booked.'], 400);
    }

    $booking = new Booking();
    $booking->date = $validated['date'];
    $booking->time = $validated['time'];
    $booking->gender = $validated['gender'];
    $booking->save();

    // E-mail küldése
    Mail::to($validated['email'])->queue(new BookingConfirmationMail($validated));

    return response()->json(['success' => true, 'booking' => $booking]);
}


public function getBookedDates()
{
    $bookings = Booking::select('date', 'time')->get(); // Dátum és idő lekérdezése

    return response()->json(['bookings' => $bookings]);
}
}
