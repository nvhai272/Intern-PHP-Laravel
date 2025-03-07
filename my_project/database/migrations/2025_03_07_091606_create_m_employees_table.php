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
            $table->id(); // Cột id
            $table->foreignId('team_id')->constrained('m_teams')->onDelete('cascade'); ; // Khóa ngoại liên kết với bảng m_teams
            $table->string('email', 128)->unique(); // Email nhân viên (duy nhất)
            $table->string('first_name', 128); // Tên
            $table->string('last_name', 128); // Họ
            $table->string('password', 64); // Mật khẩu
            $table->char('gender', 1); // Giới tính (1/Male, 2/Female)
            $table->date('birthday'); // Ngày sinh
            $table->string('address', 256); // Địa chỉ
            $table->string('avatar', 128); // Hình đại diện
            $table->integer('salary'); // Mức lương
            $table->char('position', 1); // Vị trí (1/Manager, 2/Team leader, 3/BSE, 4/Dev, 5/Tester)
            $table->char('status', 1); // Trạng thái (1/On Working, 2/Retired)
            $table->char('type_of_work', 1); // Loại công việc (1/Fulltime, 2/Partime, 3/Probationary Staff, 4/Intern)
            $table->integer('ins_id'); // ID người tạo
            $table->integer('upd_id')->nullable(); // ID người cập nhật
            $table->timestamp('ins_datetime')->useCurrent(); // Thời gian tạo bản ghi
            $table->timestamp('upd_datetime')->useCurrent()->nullable(); // Thời gian cập nhật bản ghi
            $table->char('del_flag', 1)->default('0'); // Cờ xóa (0/Active, 1/Deleted)
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
