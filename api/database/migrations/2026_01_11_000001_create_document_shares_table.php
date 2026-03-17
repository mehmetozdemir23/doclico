<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_shares', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained()->restrictOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->integer('downloads_count');
            $table->timestamp('last_downloaded_at')->nullable();
            $table->integer('views_count');
            $table->timestamp('first_viewed_at')->nullable();
            $table->timestamp('shared_at');
            $table->index('shared_at');
            $table->timestamp(column: 'reminded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_shares');
    }
};
