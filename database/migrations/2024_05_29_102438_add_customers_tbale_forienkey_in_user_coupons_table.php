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
        // Disable foreign key checks
        \DB::statement('SET foreign_key_checks = 0;');

        Schema::table('user_coupons', function (Blueprint $table) {
            // Check if 'user_id' column exists
            if (Schema::hasColumn('user_coupons', 'user_id')) {
                // Drop the foreign key constraint if it exists
                $foreignKeys = \DB::select('SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = "user_coupons" AND COLUMN_NAME = "user_id" AND REFERENCED_TABLE_NAME = "users"');
                if (!empty($foreignKeys)) {
                    \DB::statement('ALTER TABLE user_coupons DROP FOREIGN KEY ' . $foreignKeys[0]->CONSTRAINT_NAME);
                }
            }
        });

        Schema::table('user_coupons', function (Blueprint $table) {
            // Drop the column if it exists
            if (Schema::hasColumn('user_coupons', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        Schema::table('user_coupons', function (Blueprint $table) {
            // Recreate the column as nullable
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Add the new foreign key constraint
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
        });

        // Enable foreign key checks
        \DB::statement('SET foreign_key_checks = 1;');
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        \DB::statement('SET foreign_key_checks = 0;');

        Schema::table('user_coupons', function (Blueprint $table) {
            // Check if 'user_id' column exists
            if (Schema::hasColumn('user_coupons', 'user_id')) {
                // Drop the foreign key constraint if it exists
                $foreignKeys = \DB::select('SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = "user_coupons" AND COLUMN_NAME = "user_id" AND REFERENCED_TABLE_NAME = "customers"');
                if (!empty($foreignKeys)) {
                    \DB::statement('ALTER TABLE user_coupons DROP FOREIGN KEY ' . $foreignKeys[0]->CONSTRAINT_NAME);
                }
            }
        });

        Schema::table('user_coupons', function (Blueprint $table) {
            // Drop the column if it exists
            if (Schema::hasColumn('user_coupons', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        Schema::table('user_coupons', function (Blueprint $table) {
            // Recreate the column as nullable
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Add the new foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Enable foreign key checks
        \DB::statement('SET foreign_key_checks = 1;');
    }
};
