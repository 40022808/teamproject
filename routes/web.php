<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
Route::get('/', function () {
    return view('welcome');
});



Route::get('/test-email', function () {
    Mail::raw('Ez egy teszt e-mail.', function ($message) {
        $message->to('dargai.daniel@gmail.com')
                ->subject('Teszt e-mail');
    });

    return 'E-mail elküldve!';
});
Route::get('/test-queue', function () {
    Mail::raw('Ez egy teszt e-mail a queue használatával.', function ($message) {
        $message->to('dargai.daniel@gmail.com') // Cseréld ki a címzett e-mail címére
                ->subject('Teszt e-mail');
    })->queue();

    return 'E-mail hozzáadva a queue-hoz!';
});
Route::get('/test-email-template', function () {
    $user = (object) ['name' => 'Teszt Felhasználó'];
    return view('emails.registration-successful', compact('user'));
});