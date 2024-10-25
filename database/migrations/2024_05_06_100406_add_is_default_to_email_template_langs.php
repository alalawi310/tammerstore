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
        Schema::table('email_template_langs', function (Blueprint $table) {
            $table->integer('is_default')->default(0)->after('content');
        });
        if (!Schema::hasColumn('products', 'product_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('product_type')->nullable()->after('sale_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_template_langs', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
