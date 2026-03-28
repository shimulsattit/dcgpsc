<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
            if (! Schema::hasColumn('important_links', 'menu_id')) {
                $table->foreignId('menu_id')
                    ->nullable()
                    ->constrained('menus')
                    ->nullOnDelete()
                    ->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
            if (Schema::hasColumn('important_links', 'menu_id')) {
                $table->dropForeign(['menu_id']);
                $table->dropColumn('menu_id');
            }
        });
    }
};

