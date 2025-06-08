<?php

use App\Enums\BloodTypeEnum;
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
        Schema::create('patient_updated_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            //modified columns
            $table->integer('old_height')->nullable();
            $table->integer('current_height')->nullable();
            
            $table->integer('old_weight')->nullable();
            $table->integer('current_weight')->nullable();

            $table->enum('old_blood_type' , BloodTypeEnum::values())->nullable();
            $table->enum('current_blood_type' , BloodTypeEnum::values())->nullable();

            $table->string('old_chronic_diseases')->nullable();
            $table->string('current_chronic_diseases')->nullable();

            $table->string('old_notes')->nullable();
            $table->string('current_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_updated_infos');
    }
};
