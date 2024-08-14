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
        Schema::create('empplye_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_file');
            $table->enum('status',['pending','approved','rejected']);
            $table->unsignedBigInteger('freelancer_id');
            $table->foreign('freelancer_id') ->references('id')->on('freelancers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empplye_licenses');
    }
};
