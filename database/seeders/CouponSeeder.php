<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code'              => 'WELCOME15',
                'type'              => 'percentage',
                'value'             => 15.00,
                'usage_count'       => 124,
                'usage_limit'       => null,
                'min_order_amount'  => 0,
                'expires_at'        => '2025-12-31',
                'is_active'         => true,
            ],
            [
                'code'              => 'SAVE50',
                'type'              => 'fixed',
                'value'             => 50.00,
                'usage_count'       => 38,
                'usage_limit'       => 200,
                'min_order_amount'  => 150,
                'expires_at'        => '2025-06-30',
                'is_active'         => true,
            ],
            [
                'code'              => 'SUMMER25',
                'type'              => 'percentage',
                'value'             => 25.00,
                'usage_count'       => 0,
                'usage_limit'       => null,
                'min_order_amount'  => 0,
                'expires_at'        => '2025-09-30',
                'is_active'         => true,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
