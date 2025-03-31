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

    \Log::info('Received date:', ['date' => $validated['date']]);

    $alreadyBooked = Booking::where('date', $validated['date'])
                            ->where('time', $validated['time'])
                            ->exists();

    if ($alreadyBooked) {
        return response()->json(['success' => false, 'message' => 'Ez az időpont már foglalt.'], 400);
    }

    $booking = new Booking();
    $booking->date = $validated['date'];
    $booking->time = $validated['time'];
    $booking->gender = $validated['gender'];
    $booking->email = $validated['email'];
    $booking->save();

    Mail::to($validated['email'])->queue(new BookingConfirmationMail($validated));

    return response()->json(['success' => true, 'booking' => $booking]);
}


public function getBookedDates()
{
    $bookings = Booking::select('date', 'time')->get(); // Dátum és idő lekérdezése

    return response()->json(['bookings' => $bookings]);
}
public function getUserBookings(Request $request)
{
    $email = $request->query('email'); // A frontend küldi az email címet

    if (!$email) {
        return response()->json(['success' => false, 'message' => 'Email is required.'], 400);
    }

    $bookings = Booking::where('email', $email)->get();

    return response()->json(['success' => true, 'bookings' => $bookings]);
}
public function getBookings(Request $request)
{
    $userRole = $request->query('role'); // A frontend küldi a szerepkört
    $userEmail = $request->query('email'); // A frontend küldi az email címet

    if ($userRole === '2') { 
        $bookings = Booking::all(); 
    } elseif ($userRole === '1') { 
        $bookings = Booking::all(); 
    } else { // Felhasználó
        $bookings = Booking::where('email', $userEmail)->get(); 
    }

    return response()->json(['success' => true, 'bookings' => $bookings]);
}
public function getAllBookings(Request $request)
{
    $userRole = $request->query('role', null); // A frontend küldi a szerepkört

    if ($userRole === '2' || $userRole === '1') { // Csak admin és szuperadmin
        $bookings = Booking::all(); // Minden foglalás
        return response()->json(['success' => true, 'bookings' => $bookings]);
    } else {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}

}
