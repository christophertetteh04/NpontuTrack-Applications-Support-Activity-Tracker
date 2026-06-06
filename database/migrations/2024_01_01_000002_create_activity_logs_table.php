<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * activity_logs captures every status update made by personnel.
     * Multiple logs can exist per activity per day (shift handover history).
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->date('log_date');    // The working date this log belongs to
            $table->enum('status', ['pending', 'in_progress', 'done', 'escalated'])->default('pending');
            $table->text('remark')->nullable();

            // Quantitative fields for SMS-type metrics
            $table->string('expected_value')->nullable(); // e.g. SMS count from logs
            $table->string('actual_value')->nullable();   // e.g. daily SMS count
            $table->string('variance')->nullable();       // computed or entered variance

            $table->string('shift')->nullable(); // morning / afternoon / night
            $table->timestamp('updated_at_time')->useCurrent(); // exact timestamp of update
            $table->timestamps();

            // Index to speed up daily dashboard queries
            $table->index(['log_date', 'activity_id']);
            $table->index(['log_date', 'updated_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
