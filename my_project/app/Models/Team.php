<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    // sử dụng traint để fake data

    // Đặt tên bảng nếu không phải theo quy tắc mặc định
    protected $table = 'm_teams';

    // Nếu bạn không muốn sử dụng timestamps tự động (created_at, updated_at) == true
    // ở đây tạm thời tắt đi xem tự động thế nào?
    public $timestamps = false;

    // Các cột có thể được gán hàng loạt (mass assignable)
    // hoặc ngược lại sử dụng protected $guarded = ['id']; để không cho gán
    protected $fillable = ['name'];

    // Quan hệ với bảng employees (mối quan hệ 1 - nhiều)
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
