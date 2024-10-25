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
        Schema::table('', function (Blueprint $table) {
            if (!Schema::hasColumn('owner_menu_settings','enable_dropdown')) {
                Schema::table('owner_menu_settings', function (Blueprint $table) {
                    $table->string('enable_dropdown')->nullable()->after('enable_footer');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('owner_menu_settings','enable_dropdown')) {
            Schema::table('owner_menu_settings', function (Blueprint $table) {
                $table->dropColumn('enable_dropdown');
            });
         }
    }
};
