<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PayoutOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Use the API service to send a payout of the correct amount.
     * Note: The order status must be paid if the payout is successful, or remain unpaid in the event of an exception.
     *
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        // TODO: Complete this method

        DB::transaction(function () use ($apiService) {

            $affiliate = $this->order->affiliate;

            $affiliateUser = $affiliate->user;

            $email = $affiliateUser->email;
            $commission_owed = $this->order->commission_owed;

            $apiService->sendPayout(
                $email,
                $commission_owed
            );

            // Mark as paid only if no exception is thrown
            $this->order->update([
                'payout_status' => Order::STATUS_PAID,
            ]);
        });
    }
}
