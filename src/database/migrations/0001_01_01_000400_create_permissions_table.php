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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Permission::create(["name" => "View group", "description" => "Allow to view this group"]);
        Permission::create(["name" => "Send playbooks", "description" => "Allow to send playbooks to the agents of this group"]);
        Permission::create(["name" => "Send commands", "description" => "Allow to send commands without a playbook to the agents of this group"]);
        Permission::create(["name" => "Use sudo", "description" => "Allow to send commands/playbooks using sudo"]);
        Permission::create(["name" => "Use dangerous commands", "description" => "Allow to send dangerous commands to the agents of this group"]);

        Schema::create('group_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['group_id', 'permission_id']);

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('group_permissions');
    }
};
