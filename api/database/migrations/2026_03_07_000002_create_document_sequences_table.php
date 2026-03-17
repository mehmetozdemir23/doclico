<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_sequences', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->restrictOnDelete();
            $table->string('type', 30);
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_number');
            $table->unique(['user_id', 'type', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
    }
};
