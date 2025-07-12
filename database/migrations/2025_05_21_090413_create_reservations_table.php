<?php

use App\Enums\RejectionReasonEnum;
use App\Enums\ReservationStatusEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->text('notes')->nullable();
            $table->enum('status' , ReservationStatusEnum::values())->default(ReservationStatusEnum::PENDING);
            $table->enum('rejection_reason' , RejectionReasonEnum::values())->nullable();
            $table->string('other_rejection_reason')->nullable();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('day_id')->constrained()->nullOnDelete();
            $table->time('shift_start_time')->nullable();
            $table->time('shift_end_time')->nullable();
            $table->date('date')->nullable();
            $table->boolean('visits_available')->default(0);
            $table->time('time_to_come')->nullable();
            $table->boolean('daily_reminded')->default(0);
            $table->boolean('hourly_reminded')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
