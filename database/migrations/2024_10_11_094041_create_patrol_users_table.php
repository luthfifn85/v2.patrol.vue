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
        Schema::create('patrol_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index();
            $table->foreignId('patrol_location_id')->index()->nullable();
            $table->foreignId('patrol_role_id')->index();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('username')->nullable();
            $table->string('email');
            $table->string('mobile_no')->nullable();
            $table->string('password');
            $table->tinyInteger('is_login')->nullable()->comment('1:login,null:logout');
            $table->tinyInteger('is_active')->default(1)->index()->nullable()->comment('1:active,null:inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_users');
    }
};
