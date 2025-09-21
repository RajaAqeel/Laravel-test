<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('merchant_id')->constrained();
            // $table->foreignId('affiliate_id')->nullable()->constrained();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('affiliate_id')->nullable()->constrained()->cascadeOnDelete();
            // TODO: Replace floats with the correct data types (very similar to affiliates table)
            // $table->float('subtotal');
            // $table->float('commission_owed')->default(0.00);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('commission_owed', 10, 2)->default(0.00);
            $table->string('external_order_id')->unique()->nullable();
            $table->string('payout_status')->default(Order::STATUS_UNPAID);
            $table->text('discount_code')->nullable();
            $table->timestamps();

            // Added external_order_id as unique to prevent duplicate orders and this column was missing when I ran the tests.
            // Added cascadeOnDelete to foreign keys to maintain referential integrity when a merchant or affiliate is deleted.
            // Changed float to decimal for monetary values to ensure precision in money calculations.
            // Changed string to text for discount_code to allow for longer codes.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
