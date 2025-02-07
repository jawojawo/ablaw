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
        Schema::create('law_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number');
            $table->string('case_title');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->enum('party_role', config('enums.party_roles'));
            // $table->unsignedBigInteger('associate_id');
            // $table->foreign('associate_id')->references('id')->on('associates');
            $table->string('opposing_party');
            $table->enum('case_type', config('enums.case_types'));
            $table->decimal('total_deposits', 15, 2)->default(0);
            $table->decimal('total_fees', 15, 2)->default(0);
            $table->enum('status', config('enums.case_status'))->default('open');
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
        Schema::dropIfExists('law_cases');
    }
};
