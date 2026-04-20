<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_authorized')) {
                $table->boolean('is_authorized')->default(false)->after('status');
            }

            if (! Schema::hasColumn('users', 'google2fa_authorization_code_hash')) {
                $table->text('google2fa_authorization_code_hash')->nullable()->after('google2fa_enabled');
            }

            if (! Schema::hasColumn('users', 'google2fa_authorization_code_expires_at')) {
                $table->timestamp('google2fa_authorization_code_expires_at')->nullable()->after('google2fa_authorization_code_hash');
            }

            if (! Schema::hasColumn('users', 'google2fa_authorization_sent_at')) {
                $table->timestamp('google2fa_authorization_sent_at')->nullable()->after('google2fa_authorization_code_expires_at');
            }

            if (! Schema::hasColumn('users', 'google2fa_authorized_by')) {
                $table->unsignedBigInteger('google2fa_authorized_by')->nullable()->after('google2fa_authorization_sent_at');
            }

            if (! Schema::hasColumn('users', 'google2fa_authorized_at')) {
                $table->timestamp('google2fa_authorized_at')->nullable()->after('google2fa_authorized_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('users', 'is_authorized') ? 'is_authorized' : null,
                Schema::hasColumn('users', 'google2fa_authorization_code_hash') ? 'google2fa_authorization_code_hash' : null,
                Schema::hasColumn('users', 'google2fa_authorization_code_expires_at') ? 'google2fa_authorization_code_expires_at' : null,
                Schema::hasColumn('users', 'google2fa_authorization_sent_at') ? 'google2fa_authorization_sent_at' : null,
                Schema::hasColumn('users', 'google2fa_authorized_by') ? 'google2fa_authorized_by' : null,
                Schema::hasColumn('users', 'google2fa_authorized_at') ? 'google2fa_authorized_at' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
