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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_male')->nullable();
            $table->string('relation')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('smoking' , SmokeEnum::values())->nullable();
            $table->boolean('alcohol')->nullable();
            //modified columns
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->enum('blood_type' , BloodTypeEnum::values())->nullable();
            $table->string('chronic_diseases')->nullable();
            $table->string('notes')->nullable();
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
