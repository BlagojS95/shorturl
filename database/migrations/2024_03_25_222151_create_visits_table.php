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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')->constrained()->cascadeOnDelete();
            $table->date('visit_date')->default(now()->toDateString());
            $table->unsignedInteger('visits_day')->default(0);
            $table->unsignedInteger('visits_week')->default(0);
            $table->unsignedInteger('visits_month')->default(0);
            $table->unsignedInteger('visits_year')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
