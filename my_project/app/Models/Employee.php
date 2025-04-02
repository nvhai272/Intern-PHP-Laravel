<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;
    protected $table      = 'm_employees';
    protected $primaryKey = 'id';
    public $incrementing  = true;
    public $timestamps    = true;
    const CREATED_AT      = 'ins_datetime';
    const UPDATED_AT      = 'upd_datetime';

    protected $guarded = [
        'id',
        'ins_id',
        'ins_datetime',
    ];

    // Để các trường thời gian tự động quản lý
    // protected $dates = ['birthday'];  // laravel v<6

    protected $casts = [
        'birthday'     => 'date',
        'ins_datetime' => 'datetime:d-m-Y H:i:s',
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

    // Accesstor and mutator

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
