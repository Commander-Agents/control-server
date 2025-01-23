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
        Schema::create('operating_system', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', [0, 1, 2])->default(0);
            $table->dateTime('last_contact')->nullable();

            $table->unsignedBigInteger('operating_system_id')->nullable();
            $table->string('hostname')->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->enum('protocol', ['tcp', 'udp'])->nullable();
            $table->text('secret_key')->nullable();
            $table->string('secret_key_hash', 256)->nullable()->index(); // SHA-256 hash of 'secret_key'
            $table->string('uid', 256)->nullable(); // SHA-256 hash

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('operating_system_id')->references('id')->on('operating_system')->nullOnDelete();
        });

        Schema::create('agent_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->primary(['agent_id', 'group_id']);

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
        Schema::dropIfExists('agent_groups');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('operating_system');
    }
};
