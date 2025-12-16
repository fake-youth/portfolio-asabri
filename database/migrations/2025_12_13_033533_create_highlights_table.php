<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['fund_fact_sheet', 'laporan_mingguan', 'laporan_bulanan', 'laporan_tahunan']);
            $table->string('title'); // e.g., "Manulife Saham Andalan (MSA)"
            $table->text('description')->nullable();
            $table->string('image_path')->nullable(); // For thumbnail/preview
            $table->string('manager')->nullable(); // e.g., "Manulife", "Aycel"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};