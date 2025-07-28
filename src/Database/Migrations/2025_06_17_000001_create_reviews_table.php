<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NanoDepo\Taberna\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Product::class)->constrained();

            $table->string('name');
            $table->string('contacts')->nullable();
            $table->text('text');
            $table->text('answer')->nullable();
            $table->integer('value');
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }
};
