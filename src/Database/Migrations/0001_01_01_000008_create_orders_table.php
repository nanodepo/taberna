<?php

use App\Domains\User\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\OrderItem;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(User::class)->constrained();

            $table->integer('price');
            $table->enum('status', ['pending', 'processing', 'sent', 'completed', 'canceled', 'failed'])->default('pending');

            $table->string('shipping_address');
            $table->string('payment_method');
            $table->text('comment');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Order::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(Variant::class)->nullable()->constrained();

            $table->integer('quantity');

            $table->integer('price_at_purchase');
            $table->integer('discount_at_purchase');
            $table->string('product_name_at_purchase');
            $table->text('variant_details_at_purchase');
            $table->string('sku_at_purchase');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_item_selected_addons', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(OrderItem::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Addon::class)->constrained();

            $table->string('addon_name_at_purchase');
            $table->integer('price_at_purchase');
            $table->integer('quantity');
        });
    }
};
