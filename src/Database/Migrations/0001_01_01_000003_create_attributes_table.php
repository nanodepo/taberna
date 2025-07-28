<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NanoDepo\Taberna\Enums\AttributeType;
use NanoDepo\Taberna\Models\Group;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('title');
            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(Group::class)->constrained();

            $table->string('name');
            $table->string('code');
            $table->enum('type', Arr::pluck(AttributeType::cases(), 'value'))->default(AttributeType::Input->value);

            $table->boolean('is_variant_defining')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_required')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(\NanoDepo\Taberna\Models\Attribute::class)->constrained();

            $table->string('name');
            $table->string('code');
        });
    }
};
