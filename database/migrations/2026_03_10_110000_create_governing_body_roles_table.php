<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('governing_body_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_font_size')->default('1.2rem');
            $table->string('name_color')->default('#2c3e50');
            $table->string('designation_font_size')->default('1rem');
            $table->string('designation_color')->default('#3498db');
            $table->string('badge_bg_color')->default('#27ae60');
            $table->string('badge_text_color')->default('#ffffff');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('governing_body_members', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('governing_body_roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('governing_body_members', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::dropIfExists('governing_body_roles');
    }
};
