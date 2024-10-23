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
        Schema::create('patrols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->foreignId('patrol_location_id')->index();
            $table->foreignId('patrol_checkpoint_id')->index();
            $table->foreignId('patrol_event_id')->index();
            $table->foreignId('patrol_user_id')->index();
            $table->longText('note');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('views')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrols');
    }
};
