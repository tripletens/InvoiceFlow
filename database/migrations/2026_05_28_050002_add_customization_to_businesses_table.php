<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('font_family')->default('Inter')->after('accent_color');
            $table->boolean('show_tax')->default(true)->after('font_family');
            $table->boolean('show_qty')->default(true)->after('show_tax');
            $table->boolean('show_notes')->default(true)->after('show_qty');
            $table->boolean('show_tagline')->default(true)->after('show_notes');
            $table->string('template_style')->default('modern')->after('show_tagline');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'font_family', 'show_tax', 'show_qty', 'show_notes', 'show_tagline', 'template_style'
            ]);
        });
    }
};
