<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // Automatikus ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Felhasználó ID
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Termék ID
            $table->integer('quantity')->default(1); // Mennyiség
            $table->timestamps(); // Létrehozás és frissítés időbélyege
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}