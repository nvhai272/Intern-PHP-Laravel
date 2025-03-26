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
        Schema::create('m_employees', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('team_id')->constrained('m_teams')->onDelete('cascade');; // Khóa ngoại liên kết với bảng m_teams
            $table->foreignId('team_id')->nullable()->constrained('m_teams')->nullOnDelete(); // Không mất dữ liệu khi xóa team và trở thành Null

            $table->string('email', 128)->unique();
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('password', 64);
            $table->enum('gender', ['1', '2']); // 1: Male, 2: Female

            $table->date('birthday');
            $table->string('address', 256);
            $table->string('avatar', 128);
            $table->integer('salary');
//
            $table->enum('position', ['1', '2', '3', '4', '5']); // 1: Manager, 2: Team leader, 3: BSE, 4: Dev, 5: Tester
            $table->char('status', 1);                                                  // Trạng thái (1/On Working, 2/Retired)
//
            $table->enum('type_of_work', ['1', '2', '3', '4']); // 1: Fulltime, 2: Partime, 3: Probationary Staff, 4: Intern
            $table->integer('ins_id');                                                  // ID người tạo
            $table->integer('upd_id')->nullable();                                      // ID người cập nhật

            $table->timestamp('ins_datetime')->useCurrent(); // Tạo mặc định khi insert
            $table->timestamp('upd_datetime')->nullable()->useCurrentOnUpdate(); // Cập nhật khi update

            $table->enum('del_flag', ['0', '1'])->default('0');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_employees');
    }
};
