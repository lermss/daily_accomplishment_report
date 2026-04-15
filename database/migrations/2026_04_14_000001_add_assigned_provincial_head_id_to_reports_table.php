<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'assigned_provincial_head_id')) {
                $table->unsignedBigInteger('assigned_provincial_head_id')
                    ->nullable()
                    ->after('user_id');
            }
        });

        DB::table('reports')
            ->leftJoin('users as staff_users', 'staff_users.id', '=', 'reports.user_id')
            ->leftJoin('users as provincial_heads', function ($join) {
                $join->on('provincial_heads.office', '=', 'staff_users.office')
                    ->where('provincial_heads.role', '=', 'ph-admin')
                    ->where('provincial_heads.status', '=', 'active');
            })
            ->whereNull('reports.assigned_provincial_head_id')
            ->whereNotNull('staff_users.office')
            ->update([
                'reports.assigned_provincial_head_id' => DB::raw('provincial_heads.id'),
            ]);
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'assigned_provincial_head_id')) {
                $table->dropColumn('assigned_provincial_head_id');
            }
        });
    }
};
