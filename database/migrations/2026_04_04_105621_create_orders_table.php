<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();           // PH-3891
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('payment_method');                // بطاقة / Apple Pay / KNET / كاش
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                ->default('pending');
            $table->string('city')->nullable()->default('الدوحة');
            $table->string('address')->nullable();
            $table->string('coupon_code')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
