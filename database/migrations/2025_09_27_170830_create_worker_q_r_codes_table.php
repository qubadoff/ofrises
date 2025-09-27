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
        Schema::create('worker_q_r_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id');
            $table->text('qr_code');
            $table->text('qr_code_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_q_r_codes');
    }
};
