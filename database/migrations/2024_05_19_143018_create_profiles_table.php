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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('brief')->nullable();
            $table->text('Bio')->nullable();
            $table->string('home_place')->nullable();
            $table->string("work_place")->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable(); 
            $table->text('work_as')->nullable();
            $table->text('overview')->nullable();
            $table->decimal('foundation')->nullable();
            $table->text('ceo')->nullable();
            $table->text('projects')->nullable();
            $table->morphs('profileable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
