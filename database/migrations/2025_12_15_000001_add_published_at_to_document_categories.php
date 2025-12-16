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
        Schema::table('document_categories', function (Blueprint $table) {
            $table->date('published_at')->nullable()->after('description');
            // We keep 'order' but make it nullable if we want to phase it out, 
            // or just leave it alone. Let's strictly follow plan: add published_at.
            // If user wants to replace order, we can drop it or ignore it.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};
