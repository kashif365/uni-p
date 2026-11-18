<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('stores', 'views_count')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0);
            });
        }
    }

    public function down(): void
    {
        // The column may predate this repair on an existing installation.
    }
};
