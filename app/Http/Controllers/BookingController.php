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

        if(!$date) {
            return response()->json(['error'=> 'Date is required'], 400);
        }
        $dateTime = new \DateTime($date);
        $date = $dateTime->format('Y-m-d');

        $booking = new Booking();
        $booking->date = $date;
        $booking->save();

        return response()->json(['success' => true, 'date' => $date]);
    }

    public function getBookedDates()
    {
        $dates = Booking::pluck('date'); // Lekérjük az összes foglalt dátumot

        return response()->json(['dates' => $dates]); // Helyes változónév
    }
}
