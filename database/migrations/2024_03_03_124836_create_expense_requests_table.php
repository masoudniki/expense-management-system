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
        Schema::create('expense_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('description');
            $table->integer('amount');
            $table->string('iban');
            $table->string('national_code');

            $table->boolean('is_confirmed')->nullable()->default(null);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('expense_request_type_id');
            $table->foreign('expense_request_type_id')->references('id')->on('expense_request_types');

            $table->string('reject_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_request');
    }
};
