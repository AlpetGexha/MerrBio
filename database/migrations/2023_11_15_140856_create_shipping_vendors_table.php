<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('delivery_estimation')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->double('price')->default(0)->nullable();

            $table->boolean('is_activated')->default(0)->nullable();

            $table->json('integration')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_vendors');
    }
};
