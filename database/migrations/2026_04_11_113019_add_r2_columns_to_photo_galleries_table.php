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
        Schema::table('photo_galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('photo_galleries', 'google_drive_folder_link')) {
                $table->string('google_drive_folder_link')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('photo_galleries', 'thumbnail_url')) {
                $table->string('thumbnail_url', 500)->nullable()->after('google_drive_folder_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_galleries', function (Blueprint $table) {
            $table->dropColumn(['google_drive_folder_link', 'thumbnail_url']);
        });
    }
};
