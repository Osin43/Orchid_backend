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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id');
            $table->unsignedBigInteger('user_id')->nullable();

            // $table->foreign('student_id')->references('id')->on('users');
            $table->foreignId('student_id')->constrained('users');

            $table->unsignedBigInteger('subject_id')->nullable();


            $table->foreign('subject_id')->references('id')->on('subjects');

            $table->string('grade');
            $table->string('marks');
            $table->string('percentage');
            $table->string('total');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
