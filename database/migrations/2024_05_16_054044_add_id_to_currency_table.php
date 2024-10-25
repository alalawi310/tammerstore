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
        if (!Schema::hasColumn('currency', 'id')) {
        Schema::table('currency', function (Blueprint $table) {
            $table->id()->first();
            $table->timestamp('created_at')->nullable()->after('symbol');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currency', function (Blueprint $table) {
            //
        });
    }
};
