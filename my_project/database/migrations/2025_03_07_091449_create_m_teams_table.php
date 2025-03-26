<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->enum('del_flag', ['0', '1'])->default('0');

            $table->timestamp('ins_datetime')->useCurrent(); // Tạo mặc định khi insert
            $table->timestamp('upd_datetime')->nullable()->useCurrentOnUpdate(); // Cập nhật khi update

        });

        DB::statement('ALTER TABLE m_teams AUTO_INCREMENT = 1');
        // DB::statement('ALTER TABLE m_teams ADD CONSTRAINT check_id_max CHECK (id <= 99999999999)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_teams');
    }
};
