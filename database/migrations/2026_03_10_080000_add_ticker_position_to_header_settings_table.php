<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('header_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('header_settings', 'ticker_position')) {
                $table->string('ticker_position')->default('below_slider')->after('notice_ticker_limit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('header_settings', function (Blueprint $table) {
            $table->dropColumn('ticker_position');
        });
    }
};
