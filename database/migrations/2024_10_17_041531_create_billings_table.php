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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('law_case_id');
            $table->foreign('law_case_id')->references('id')->on('law_cases');
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->date('billing_date');
            $table->date('due_date');
            $table->date('fully_paid_date')->nullable();
            $table->enum('status', config('enums.billing_status'))->default('unpaid');
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
        Schema::dropIfExists('billings');
    }
};
