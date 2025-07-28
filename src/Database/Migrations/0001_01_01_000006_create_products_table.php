<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Option;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Brand::class)->constrained();

            $table->string('sku');
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('intro')->nullable();
            $table->text('description')->nullable();

            $table->integer('price');
            $table->integer('discount')->default(0);
            $table->integer('quantity')->default(1);

            $table->boolean('is_active')->default(true);
            $table->boolean('has_variants')->default(false);
            $table->boolean('is_main')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('options',  function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->after('attribute_id')->nullable()->constrained();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
        });

        Schema::create('attribute_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(\NanoDepo\Taberna\Models\Attribute::class)->constrained();

            $table->string('value');
            $table->foreignIdFor(Option::class)->nullable()->constrained()->nullOnDelete();
        });

        Schema::create('variants', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Product::class)->constrained();

            $table->string('sku');

            $table->integer('price');
            $table->integer('discount')->default(0);
            $table->integer('quantity')->default(1);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('option_variant', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Option::class)->constrained();
            $table->foreignIdFor(Variant::class)->constrained();
        });

        Schema::create('addons', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('name');
            $table->text('description')->nullable();

            $table->integer('price');
            $table->string('code');

            $table->integer('max_quantity')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addon_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Addon::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();

            $table->boolean('is_default_selected')->default(false);
        });
    }
};
