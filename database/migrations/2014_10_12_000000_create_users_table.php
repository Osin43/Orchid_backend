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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->date('dob')->default('1999-01-01');
           
        //      $table->string('mobile_number');           
        //     $table->enum('gender',['male','female']);
        //     $table->string('address');
        //     $table->enum('role',[ 'student','teacher','parent','accountant','admin']);
        //     $table->string('email')->unique();
        //     $table->string('password');
        //     $table->boolean('banned')->default(false);
        //     $table->timestamp('created_at')->nullable();
        //     $table->timestamp('updated_at')->nullable();


            
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
