<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->unique();
            $table->string('address_text')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('license_number')->unique();
            $table->boolean('is_center')->default(0);
            $table->string('bio');
            $table->string('join_reason');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->double('rate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
