<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name'       => 'أحمد الخليفي',
                'email'      => 'ahmed@example.com',
                'phone'      => '+974 5555 1111',
                'city'       => 'الدوحة',
                'address'    => 'الدوحة - الروضة',
                'created_at' => '2024-01-15',
            ],
            [
                'name'       => 'سارة المنصور',
                'email'      => 'sara@example.com',
                'phone'      => '+974 5555 2222',
                'city'       => 'الدوحة',
                'address'    => 'الدوحة - السد',
                'created_at' => '2024-03-20',
            ],
            [
                'name'       => 'محمد الدوسري',
                'email'      => 'mohamed@example.com',
                'phone'      => '+974 5555 3333',
                'city'       => 'الريان',
                'address'    => 'الريان - مدينة خليفة',
                'created_at' => '2023-11-05',
            ],
            [
                'name'       => 'نورة العبدالله',
                'email'      => 'noora@example.com',
                'phone'      => '+974 5555 4444',
                'city'       => 'الدوحة',
                'address'    => 'الدوحة - نجمة',
                'created_at' => '2024-06-12',
            ],
            [
                'name'       => 'خالد الزيد',
                'email'      => 'khaled@example.com',
                'phone'      => '+974 5555 5555',
                'city'       => 'لوسيل',
                'address'    => 'لوسيل - مرينا',
                'created_at' => '2024-02-28',
            ],
            [
                'name'       => 'فاطمة الحسن',
                'email'      => 'fatima@example.com',
                'phone'      => '+974 5555 6666',
                'city'       => 'الدوحة',
                'address'    => 'الدوحة - بن محمود',
                'created_at' => '2024-04-10',
            ],
            [
                'name'       => 'عبدالرحمن النعيمي',
                'email'      => 'abdulrahman@example.com',
                'phone'      => '+974 5555 7777',
                'city'       => 'الوكرة',
                'address'    => 'الوكرة - المنطقة الجنوبية',
                'created_at' => '2024-05-18',
            ],
            [
                'name'       => 'مريم الشمري',
                'email'      => 'mariam@example.com',
                'phone'      => '+974 5555 8888',
                'city'       => 'الدوحة',
                'address'    => 'الدوحة - الدفنة',
                'created_at' => '2023-12-22',
            ],
        ];

        foreach ($customers as $data) {
            Customer::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}
