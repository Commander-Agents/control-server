<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('command');
            $table->enum('type', ['command', 'playbook']); // Type de tâche
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('task_agent', function (Blueprint $table) {
            $table->string('uid', 50)->primary(); // Random uid
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('agent_id');
            $table->dateTime('scheduled_at');
            $table->unsignedMediumInteger('inactive_after')->default(300); // After 300 seconds in the "acknowledged" or "in_progress" state, the task will be resetted to "pending"
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'acknowledged'])->default('pending');
            $table->text('output')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_agent');
    }
};