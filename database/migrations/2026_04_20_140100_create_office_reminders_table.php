<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('office');
            $table->text('message');
            $table->string('type', 20);
            $table->timestamp('triggered_at');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('office_reminder_schedule_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_reminders');
    }
};
