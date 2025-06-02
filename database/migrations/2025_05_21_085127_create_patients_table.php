<?php

use App\Enums\BloodTypeEnum;
use App\Enums\SmokeEnum;
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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_owner')->default(0);
            $table->string('full_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->boolean('is_male')->nullable();
            $table->string('relation')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('smoking' , SmokeEnum::values())->nullable();
            $table->boolean('alcohol')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
