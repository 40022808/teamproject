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
        $bookings = Booking::where('email', $userEmail)->get(); 
    return response()->json(['success' => true, 'bookings' => $bookings]);
}
public function getAllBookings(Request $request)
{
    $userRole = $request->query('role'); // A frontend küldi a szerepkört
    $userEmail = $request->query('email'); // A frontend küldi az email címet

    if ($userRole === '2' || $userRole === '1') { // Csak admin és szuperadmin
        $bookings = Booking::orderBy('date', 'asc')->orderBy('time', 'asc')->get(); // Rendezés dátum és idő szerint
        return response()->json(['success' => true, 'bookings' => $bookings]);
    } else {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    return response()->json(['success' => true, 'bookings' => $bookings]);
}

public function updateBooking(Request $request, $id)
{
    // 1. Validáció
    $validated = $request->validate([
        'date' => 'sometimes|date',
        'time' => 'sometimes|date_format:H:i',
        'gender' => 'sometimes|string',
    ]);

    // 2. Foglalás keresése
    $booking = Booking::find($id);

    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
    }

    // 3. Felhasználói adatok lekérése
    $userRole = $request->query('role');
    $userEmail = $request->query('email');

    // 4. Jogosultság ellenőrzése
    if ($booking->email !== $userEmail && $userRole !== '2' && $userRole !== '1') {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    // 5. Foglalás frissítése (csak a megadott mezők)
    if ($request->has('date')) {
        $booking->date = $validated['date'];
    }

    if ($request->has('time')) {
        $booking->time = $validated['time'];
    }

    if ($request->has('gender')) {
        $booking->gender = $validated['gender'];
    }

    $booking->save();

    // 6. Sikeres válasz
    return response()->json(['success' => true, 'booking' => $booking]);
}
public function deleteBooking(Request $request, $id)
{
    $user = $request->user(); // Check if the user is authenticated

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
    }

    $booking = Booking::find($id);

    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
    }

    // Check permissions
    if ($user->role !== 1 && $user->role !== 2 && $booking->user_id !== $user->id) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $booking->delete();

    return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
}
}


