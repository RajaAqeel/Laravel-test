<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        if (Merchant::where('email', $email)->exists()) {
            throw new AffiliateCreateException('Merchant with this email already exists');
        }

        if (User::where('email', $email)->where('type', 'merchant')->exists()) {
            throw new AffiliateCreateException('User with this email already registered as a merchant');
        }

        if (Affiliate::where('email', $email)->exists()) {
            throw new AffiliateCreateException('Email already registered as an affiliate');
        }

        if (User::where('email', $email)->where('type', 'affiliate')->exists()) {
            throw new AffiliateCreateException('User with this email already registered as an affiliate');
        }
        
        $userArray = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(12)),
            'type' => User::TYPE_AFFILIATE,
        ];

        $user = User::create($userArray);

        $discount = $this->apiService->createDiscountCode($merchant);

        $affiliateArray = [
            'user_id'         => $user->id,
            'merchant_id'     => $merchant->id,
            'name'            => $name,
            'email'           => $email,
            'commission_rate' => $commissionRate,
            'discount_code'   => (string) $discount['code'],
        ];

        $affiliate = Affiliate::create($affiliateArray);

        Mail::to($affiliate->email)->send(new AffiliateCreated($affiliate));

        return $affiliate;
    }
}
