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
        Schema::table('roles', function (Blueprint $table) {
            // Check if the 'name' column exists
            if (Schema::hasColumn('roles', 'name')) {
                // Check if the unique constraint exists on the 'name' column
                $hasUnique = collect(DB::select("SHOW INDEX FROM roles WHERE Column_name = 'name' AND Non_unique = 0 AND Key_name != 'PRIMARY'"))->isNotEmpty();

                if ($hasUnique) {
                    // Drop the unique constraint
                    $table->dropUnique('roles_name_unique');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Add back the unique constraint if needed
            $table->unique('name');
        });
    }
};
