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
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('law_case_id');
            $table->foreign('law_case_id')->references('id')->on('law_cases');
            $table->string('title');
            $table->dateTime('hearing_date');
            $table->unsignedBigInteger('court_branch_id');
            $table->foreign('court_branch_id')->references('id')->on('court_branches');
            $table->enum('status', config('enums.hearing_status'))->default('upcoming');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
