<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('book_loans', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('book_id');
        $table->date('loan_date')->nullable();
        $table->date('due_date')->nullable();
        $table->date('return_date')->nullable();
        $table->char('extended', 3)->default('NOT');
        $table->date('extension_date')->nullable();
        $table->integer('penalty_amount')->nullable();
        $table->string('penalty_status', 15)->default('INACTIVE');
        $table->string('status', 20)->default('PENDING');
        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('book_id')->references('id')->on('books');
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
