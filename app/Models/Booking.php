<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use Illuminate\Http\Request;


class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['date'];
}