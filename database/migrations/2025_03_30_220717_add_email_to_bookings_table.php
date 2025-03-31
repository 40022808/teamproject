<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('email')->after('gender'); // Az 'email' oszlop hozzáadása
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn('email'); // Az 'email' oszlop eltávolítása
    });
}
};