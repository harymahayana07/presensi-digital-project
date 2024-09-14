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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->double('schedule_latitude');
            $table->double('schedule_longitude');
            $table->time('schedule_waktu_mulai');
            $table->time('schedule_waktu_berakhir');
            $table->double('latitude');
            $table->double('longitude');
            $table->time('waktu_mulai');
            $table->time('waktu_berakhir');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
