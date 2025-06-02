<?php

use App\Enums\TreatmentStatusEnum;
use App\Enums\TreatmentTypeEnum;
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
        Schema::create('instructions', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->enum('status' , TreatmentStatusEnum::values());
            $table->date('end_date')->nullable();
            $table->string('other_end_date')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('visit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_latest')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructions');
    }
};
