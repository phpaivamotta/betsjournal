<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('sport')->nullable();
            $table->string('match');
            $table->date('match_date')->nullable();
            $table->time('match_time')->nullable();
            $table->string('bookie')->nullable();
            $table->string('bet_type')->nullable();
            $table->string('bet_description')->nullable();
            $table->string('bet_pick')->nullable();
            $table->decimal('bet_size');
            $table->float('odds');
            $table->boolean('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
};
