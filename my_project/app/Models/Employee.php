<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_employees';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'ins_datetime';
    const UPDATED_AT = 'upd_datetime';

    protected $guarded = [
        'id',
        'ins_id',
        'ins_datetime',
    ];

    // Để các trường thời gian tự động quản lý
    // protected $dates = ['birthday'];  // laravel v<6

    protected $casts = [
        'birthday' => 'date', // Chuyển đổi birthday thành kiểu Date
        'ins_datetime' => 'datetime:d-m-Y H:i:s', // Tùy chỉnh định dạng
        'upd_datetime' => 'datetime',
    ];

    // Tạo quan hệ với bảng team
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new IsNotDeletedScope());
    }



}
