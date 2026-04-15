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
    Schema::create('report_entries', function (Blueprint $table) {
        $table->id();

        // ✅ THIS IS THE MOST IMPORTANT FIX
        $table->foreignId('report_id')->constrained()->onDelete('cascade');

        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->string('activity');
        $table->text('details')->nullable();
        $table->text('remarks')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_entries');
    }
};
