<?php

use App\Enums\DaysToTakeEnum;
use App\Enums\TreatmentStatusEnum;
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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->enum('status' , TreatmentStatusEnum::values());
            $table->date('end_date')->nullable();
            $table->string('other_end_date')->nullable();
            $table->enum('days_to_take' , DaysToTakeEnum::values());
            $table->foreignId('visit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_latest')->default(1);
            $table->morphs('userable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
