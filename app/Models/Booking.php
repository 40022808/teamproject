<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $fillable = ['date', 'time', 'gender', 'email'];
}