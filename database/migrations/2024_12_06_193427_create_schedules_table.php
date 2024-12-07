<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->char('frequency', 2);
            $table->string('time_of_day');
            $table->date('start_date');
            $table->date('end_date')
                ->nullable()
                ->default('2100-01-01');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
