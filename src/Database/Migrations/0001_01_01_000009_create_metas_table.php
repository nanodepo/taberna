<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->ulidMorphs('subject');

            $table->string('title');
            $table->string('description')->nullable();
            $table->string('canonical')->nullable();
        });
    }
};
