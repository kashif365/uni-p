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
        Schema::create('verification_tags', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->enum('type', ['meta', 'script', 'custom'])->default('meta');
            $table->string('attribute_key')->nullable();
            $table->string('attribute_value')->nullable();
            $table->text('content')->nullable();
            $table->longText('code')->nullable();
            $table->string('script_attributes')->nullable();
            $table->enum('placement', ['head_start', 'head_end', 'body_start', 'body_end'])->default('head_end');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_tags');
    }
};

