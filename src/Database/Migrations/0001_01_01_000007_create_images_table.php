<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->ulidMorphs('subject');

            $table->string('disk');
            $table->string('path');

            $table->boolean('is_primary')->default(false);

            $table->text('alt')->nullable();
        });
    }
};
