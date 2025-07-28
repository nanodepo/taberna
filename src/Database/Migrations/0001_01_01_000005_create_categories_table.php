<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NanoDepo\Taberna\Models\Category;
use NanoDepo\Taberna\Models\Option;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Category::class)->nullable();

            $table->string('name');
            $table->string('slug');

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_virtual')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attribute_category', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\NanoDepo\Taberna\Models\Attribute::class)->constrained();
            $table->foreignIdFor(Category::class)->constrained();

            $table->boolean('is_required')->default(false);
            $table->string('default_value')->nullable();
            $table->foreignIdFor(Option::class)->nullable()->constrained();
        });
    }
};
