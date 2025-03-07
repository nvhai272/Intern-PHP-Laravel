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
        Schema::create('m_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->integer('ins_id');
            $table->integer('upd_id')->nullable();
            $table->timestamp('ins_datetime')->useCurrent();             // Tự động gán thời gian khi tạo mới bản ghi
            $table->timestamp('upd_datetime')->useCurrent()->nullable(); // Tự động gán thời gian khi tạo mới và có thể null khi không cập nhật
            $table->char('del_flag', 1)->default('0');

            // $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_teams');
    }
};
