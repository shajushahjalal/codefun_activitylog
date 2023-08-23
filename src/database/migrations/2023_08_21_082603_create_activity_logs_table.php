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
        Schema::dropIfExists('activity_logs');
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string("uuid");
            $table->string("tableable_type")->nullable();
            $table->unsignedBigInteger("tableable_id")->nullable();
            $table->string("causerable_type")->nullable();
            $table->unsignedBigInteger("causerable_id")->nullable();
            $table->string("event_name")->nullable();
            $table->string("description")->nullable();
            $table->text("old_data")->nullable();
            $table->text("updated_data")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
