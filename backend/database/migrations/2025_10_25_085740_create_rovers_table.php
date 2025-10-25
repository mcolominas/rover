<?php

use App\Enums\Direction;
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
        Schema::create('rovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planet_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('x');
            $table->unsignedInteger('y');
            $table->enum('direction', [Direction::NORTH, Direction::SOUTH, Direction::EAST, Direction::WEST]);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rovers');
    }
};
