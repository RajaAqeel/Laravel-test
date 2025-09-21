<?php

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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable(false);
            $table->string('email')->unique();
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.
            // $table->float('commission_rate');
            $table->decimal('commission_rate', 5, 4);
            $table->text('discount_code')->unique();
            $table->timestamps();

            // Added cascadeOnDelete to merchant_id to maintain referential integrity when a merchant is deleted.
            // Changed float to decimal for commission_rate to ensure precision in money calculations.
            // Changed string to text for discount_code to allow for longer codes.
            // Added unique constraint to email and discount_code to prevent duplicates.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
};
