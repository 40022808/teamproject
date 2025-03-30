<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'address',
        'house_number',
        'city',
        'postal_code',
        'phone',
    ];
}