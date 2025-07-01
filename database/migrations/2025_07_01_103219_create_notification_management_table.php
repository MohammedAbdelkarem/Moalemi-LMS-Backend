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
        Schema::create('notification_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('steps_notification')->default(1);
            $table->boolean('water_notification')->default(1);
            $table->boolean('sleep_notification')->default(1);
            $table->boolean('weight_notification')->default(1);
            $table->boolean('general_notification')->default(1);
            $table->boolean('articles_notification')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_management');
    }
};
