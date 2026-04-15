<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->default('')->after('name');
            }

            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }

            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->default('')->after('middle_name');
            }
        });

        DB::table('users')
            ->select(['id', 'name', 'first_name', 'middle_name', 'last_name'])
            ->orderBy('id')
            ->chunkById(200, function ($users): void {
                foreach ($users as $user) {
                    $existingFirst = trim((string) ($user->first_name ?? ''));
                    $existingLast = trim((string) ($user->last_name ?? ''));

                    if ($existingFirst !== '' || $existingLast !== '') {
                        continue;
                    }

                    $parts = preg_split('/\s+/', trim((string) ($user->name ?? ''))) ?: [];
                    $firstName = $parts[0] ?? '';
                    $lastName = count($parts) > 1 ? $parts[count($parts) - 1] : '';
                    $middleName = count($parts) > 2 ? implode(' ', array_slice($parts, 1, -1)) : null;

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $columnsToDrop = array_values(array_filter([
                Schema::hasColumn('users', 'first_name') ? 'first_name' : null,
                Schema::hasColumn('users', 'middle_name') ? 'middle_name' : null,
                Schema::hasColumn('users', 'last_name') ? 'last_name' : null,
            ]));

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

