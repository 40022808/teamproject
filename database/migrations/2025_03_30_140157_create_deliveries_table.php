<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('address');
            $table->string('house_number');
            $table->string('city');
            $table->string('postal_code');
            $table->string('phone');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}