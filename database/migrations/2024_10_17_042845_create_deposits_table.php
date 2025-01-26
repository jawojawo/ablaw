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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id');
            $table->foreign('billing_id')->references('id')->on('billings');
            $table->enum('payment_type', config('enums.payment_types'));
            // $table->unsignedBigInteger('payment_type_id');
            // $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->decimal('amount', 15, 2);
            $table->date('deposit_date');
            $table->string('received_from')->nullable();
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
        Schema::dropIfExists('deposits');
    }
};
