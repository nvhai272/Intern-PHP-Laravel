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
        Schema::create('employee_project', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained('m_employees');
            $table->foreignId('project_id')->constrained('projects');

            $table->integer('ins_id');
            $table->integer('upd_id')->nullable();
            $table->enum('del_flag', ['0', '1'])->default('0');
            $table->timestamp('ins_datetime')->useCurrent();
            $table->timestamp('upd_datetime')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_project');
    }
};
