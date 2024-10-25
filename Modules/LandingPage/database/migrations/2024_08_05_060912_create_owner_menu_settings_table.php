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
        Schema::create('owner_menu_settings', function (Blueprint $table) {
            $table->id();
            $table->string('menus_id')->nullable();
            $table->string('enable_header')->nullable();
            $table->string('enable_login')->nullable();
            $table->string('enable_footer')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_menu_settings');
    }
};
