<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu không theo quy tắc mặc định (Laravel sẽ mặc định là employees)
    protected $table = 'm_employees';

    // Các cột có thể được gán hàng loạt (mass assignable)
    protected $fillable = [
        'team_id', 'email', 'first_name', 'last_name', 'password',
        'gender', 'birthday', 'address', 'avatar', 'salary',
        'position', 'status', 'type_of_work', 'ins_id', 'upd_id', 'del_flag'
    ];

    // Để các trường thời gian tự động quản lý
    protected $dates = ['birthday', 'ins_datetime', 'upd_datetime'];

    // Tạo quan hệ với bảng `teams`
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Các phương thức hoặc logic khác có thể thêm vào đây

}
