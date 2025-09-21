<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\User;   // 👈 add this
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Affiliate>
 */
class AffiliateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // return [
        //     'discount_code' => $this->faker->uuid(),
        //     'commission_rate' => round(rand(1, 5) / 10, 1)
        // ];

        return [
            'user_id'         => User::factory(),
            'merchant_id'     => Merchant::factory(),
            'name'            => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'commission_rate' => round(rand(1, 5) / 10, 1),
            'discount_code'   => $this->faker->uuid(),
        ];
    }
}
