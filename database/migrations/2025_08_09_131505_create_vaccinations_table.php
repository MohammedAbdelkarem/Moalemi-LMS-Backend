<?php

use App\Enums\ChildAgeEnum;
use App\Enums\VaccineEnum;
use App\Enums\VaccineVisitEnum;
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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->enum('vaccine_visit' , VaccineVisitEnum::values());
            $table->enum('child_age' , ChildAgeEnum::values());
            $table->enum('vaccine' , VaccineEnum::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
