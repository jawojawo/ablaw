<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('administrative_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('law_case_id');
            $table->foreign('law_case_id')->references('id')->on('law_cases');
            $table->string('type');
            // $table->unsignedBigInteger('administrative_fee_category_id');
            // $table->foreign('administrative_fee_category_id')->references('id')->on('administrative_fee_categories');
            $table->decimal('amount', 15, 2);
            $table->date('fee_date')->nullable();
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
        Schema::dropIfExists('administrative_fees');
    }
};
