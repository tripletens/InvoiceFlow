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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('primary_color')->default('#06b6d4')->after('logo');
            $table->string('accent_color')->default('#14b8a6')->after('primary_color');
            $table->string('tagline')->nullable()->after('accent_color');
            $table->text('invoice_footer')->nullable()->after('tagline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'accent_color', 'tagline', 'invoice_footer']);
        });
    }
};
