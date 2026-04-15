<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'review_comment')) {
                // Stores admin review notes without changing the existing report entry structure.
                $table->text('review_comment')->nullable()->after('reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'review_comment')) {
                $table->dropColumn('review_comment');
            }
        });
    }
};
