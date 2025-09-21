<?php

namespace App\Services;

use Illuminate\Support\Str;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method

        if (Order::where('external_order_id', $data['order_id'])->exists()) {
            return;
        }

        $merchant = Merchant::where('domain', $data['merchant_domain'])->first();

        if (!$merchant) {
            throw new \Exception('Unable to find Merchant with the given domain.');
        }
        
        $affiliate = Affiliate::where('merchant_id', $merchant->id)
                ->where('email', $data['customer_email'])
                ->first();
        
        if (!$affiliate) {
            $registeredAffiliate = $this->affiliateService->register(
                $merchant,
                $data['customer_email'],
                $data['customer_name'],
                0.10
            );

            $affiliate = Affiliate::where('merchant_id', $merchant->id)
                        ->where('discount_code', $data['discount_code'])
                        ->firstOrFail();         
        }

        Order::create([
            'merchant_id'       => $merchant->id,
            'affiliate_id'      => $affiliate->id,
            'subtotal'          => $data['subtotal_price'],
            'commission_owed'   => $data['subtotal_price'] * $affiliate->commission_rate,
            'external_order_id' => $data['order_id'] ?? Str::uuid(),
            'discount_code'     => $data['discount_code'] ?? null,
            'payout_status'     => Order::STATUS_UNPAID,
        ]);
    }
}
