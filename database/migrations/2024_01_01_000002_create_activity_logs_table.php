<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->date('log_date');    
            $table->enum('status', ['pending', 'in_progress', 'done', 'escalated'])->default('pending');
            $table->text('remark')->nullable();

            
            $table->string('expected_value')->nullable(); 
            $table->string('actual_value')->nullable();   
            $table->string('variance')->nullable();       

            $table->string('shift')->nullable(); 
            $table->timestamp('updated_at_time')->useCurrent(); 
            $table->timestamps();

            
            $table->index(['log_date', 'activity_id']);
            $table->index(['log_date', 'updated_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
