<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Map email → customer id
        $customers = Customer::pluck('id', 'email');
        $products  = Product::all()->keyBy('slug');

        // ── Orders from admin HTML ────────────────────────────────
        $orders = [
            [
                'reference'      => 'PH-3891',
                'customer_email' => 'ahmed@example.com',
                'total'          => 387.00,
                'payment_method' => 'بطاقة',
                'status'         => 'delivered',
                'created_at'     => '2025-03-28',
                'items'          => [
                    ['slug' => 'whey-gold-double-chocolate-optimum-nutrition', 'qty' => 2],
                ],
            ],
            [
                'reference'      => 'PH-3890',
                'customer_email' => 'sara@example.com',
                'total'          => 95.00,
                'payment_method' => 'Apple Pay',
                'status'         => 'processing',
                'created_at'     => '2025-03-28',
                'items'          => [
                    ['slug' => 'vitamin-d3-k2-garden-of-life', 'qty' => 1],
                ],
            ],
            [
                'reference'      => 'PH-3889',
                'customer_email' => 'mohamed@example.com',
                'total'          => 620.00,
                'payment_method' => 'كاش',
                'status'         => 'delivered',
                'created_at'     => '2025-03-27',
                'items'          => [
                    ['slug' => 'whey-gold-double-chocolate-optimum-nutrition', 'qty' => 1],
                    ['slug' => 'no-xplode-pre-workout-bsn',                   'qty' => 1],
                    ['slug' => 'creatine-monohydrate-500g-myprotein',         'qty' => 3],
                ],
            ],
            [
                'reference'      => 'PH-3888',
                'customer_email' => 'noora@example.com',
                'total'          => 233.00,
                'payment_method' => 'KNET',
                'status'         => 'pending',
                'created_at'     => '2025-03-27',
                'items'          => [
                    ['slug' => 'marine-collagen-vitamin-c-natures-way', 'qty' => 1],
                    ['slug' => 'vitamin-c-1000mg-zinc-garden-of-life',  'qty' => 1],
                ],
            ],
            [
                'reference'      => 'PH-3887',
                'customer_email' => 'khaled@example.com',
                'total'          => 155.00,
                'payment_method' => 'بطاقة',
                'status'         => 'processing',
                'created_at'     => '2025-03-26',
                'items'          => [
                    ['slug' => 'no-xplode-pre-workout-bsn', 'qty' => 1],
                ],
            ],
            [
                'reference'      => 'PH-3886',
                'customer_email' => 'fatima@example.com',
                'total'          => 451.00,
                'payment_method' => 'Apple Pay',
                'status'         => 'delivered',
                'created_at'     => '2025-03-26',
                'items'          => [
                    ['slug' => 'marine-collagen-vitamin-c-natures-way', 'qty' => 2],
                    ['slug' => 'magnesium-citrate-solgar',              'qty' => 1],
                    ['slug' => 'vitamin-d3-k2-garden-of-life',         'qty' => 1],
                ],
            ],
            [
                'reference'      => 'PH-3885',
                'customer_email' => 'abdulrahman@example.com',
                'total'          => 189.00,
                'payment_method' => 'KNET',
                'status'         => 'cancelled',
                'created_at'     => '2025-03-25',
                'items'          => [
                    ['slug' => 'whey-gold-double-chocolate-optimum-nutrition', 'qty' => 1],
                ],
            ],
            [
                'reference'      => 'PH-3884',
                'customer_email' => 'mariam@example.com',
                'total'          => 892.00,
                'payment_method' => 'بطاقة',
                'status'         => 'delivered',
                'created_at'     => '2025-03-25',
                'items'          => [
                    ['slug' => 'organic-plant-protein-vanilla-optimum', 'qty' => 2],
                    ['slug' => 'ashwagandha-ksm66-solgar',              'qty' => 2],
                    ['slug' => 'omega3-fish-oil-180-now-foods',         'qty' => 2],
                    ['slug' => 'l-glutamine-500g-nutricost',            'qty' => 2],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items          = $orderData['items'];
            $customerEmail  = $orderData['customer_email'];
            $customerId     = $customers[$customerEmail];

            unset($orderData['items'], $orderData['customer_email']);

            $shipping = $orderData['total'] >= 200 ? 0 : 15;

            $order = Order::updateOrCreate(
                ['reference' => $orderData['reference']],
                [
                    ...$orderData,
                    'customer_id'  => $customerId,
                    'subtotal'     => $orderData['total'],
                    'shipping_fee' => $shipping,
                ]
            );

            // Seed order items
            foreach ($items as $item) {
                $product = $products[$item['slug']] ?? null;
                if (!$product) continue;

                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $product->id],
                    [
                        'product_name'  => $product->name,
                        'product_brand' => $product->brand,
                        'unit_price'    => $product->price,
                        'quantity'      => $item['qty'],
                        'subtotal'      => $product->price * $item['qty'],
                    ]
                );
            }
        }
    }
}
