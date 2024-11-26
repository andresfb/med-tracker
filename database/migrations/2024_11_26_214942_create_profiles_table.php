<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->unsignedSmallInteger('age')->nullable();
            $table->unsignedSmallInteger('height_inches')->nullable();
            $table->unsignedSmallInteger('weight_pounds')->nullable();
            $table->float('bmi')->nullable();
            $table->char('gender', 1)->nullable();
            $table->longText('history')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
