<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    // sử dụng traint để fake data

    // Đặt tên bảng nếu không muốn theo quy tắc mặc định
    protected $table = 'm_teams';

    protected $primaryKey = 'id';
    public $incrementing  = true;

    public $timestamps  = true;
    const CREATED_AT    = 'ins_datetime';
    const UPDATED_AT    = 'upd_datetime';

    protected $fillable = ['name', 'upd_id', 'del_flag'];

    // Quan hệ với bảng employees (mối quan hệ 1 - nhiều)
    public function employees()
    {
        return $this->hasMany(Employee::class, 'team_id', 'id');
    }

    protected $casts = [
        // 'birthday' => 'datetime', // Định dạng thành đối tượng Carbon

        'ins_datetime' => 'datetime:d-m-Y H:i:s', // Tùy chỉnh định dạng
        'upd_datetime' => 'datetime',
    ];



}

?>
