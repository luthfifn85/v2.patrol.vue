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
        Schema::create('patrol_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->foreignId('patrol_location_id')->index();
            $table->string('uuid');
            $table->string('name');
            $table->tinyInteger('mode')->default(1)->nullable()->comment('1:realtime,null:from gallery');
            $table->string('photo')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('radius')->nullable();
            $table->tinyInteger('is_active')->default(1)->index()->nullable()->comment('1:active,null:inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_checkpoints');
    }
};
