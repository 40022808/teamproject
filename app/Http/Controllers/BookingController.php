<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function checkBooking(Request $request)
    {
        $date = $request->query('date');
        $booked = Booking::where('date', $date)->exists();
        return response()->json(['booked' => $booked]);
    }

    public function storeBooking(Request $request)
    {
        $date = $request->input('date');
        $booking = new Booking();
        $booking->date = $date;
        $booking->save();
        return response()->json(['success' => true]);
    }

    public function getBookedDates()
    {
        $dates = Booking::pluck('date');
        return response()->json(['dates' => $dates]);
    }
}
