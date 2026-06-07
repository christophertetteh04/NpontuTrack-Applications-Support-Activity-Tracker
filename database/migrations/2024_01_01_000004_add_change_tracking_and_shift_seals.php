<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('previous_status')->nullable()->after('status');
            $table->string('ip_address')->nullable()->after('updated_at_time');
            $table->index(['log_date']);
            $table->index(['activity_id']);
            $table->index(['updated_by']);
        });

        Schema::create('shift_seals', function (Blueprint $table) {
            $table->id();
            $table->date('sealed_date');
            $table->string('shift'); // morning, afternoon, night
            $table->foreignId('sealed_by')->constrained('users')->restrictOnDelete();
            $table->text('pdf_path')->nullable();
            $table->text('summary')->nullable();
            $table->integer('total_activities')->default(0);
            $table->integer('completed_activities')->default(0);
            $table->integer('pending_activities')->default(0);
            $table->timestamps();
            
            $table->unique(['sealed_date', 'shift']);
            $table->index(['sealed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_seals');
        
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['log_date']);
            $table->dropIndex(['activity_id']);
            $table->dropIndex(['updated_by']);
            $table->dropColumn(['previous_status', 'ip_address']);
        });
    }
};
