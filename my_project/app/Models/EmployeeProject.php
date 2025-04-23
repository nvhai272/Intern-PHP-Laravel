<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeProject extends Pivot
{
    use HasFactory;

    protected $table = 'employee_project';
    protected $primaryKey = 'id';
    public $incrementing = true;

    public $timestamps = true;
    public const CREATED_AT = 'ins_datetime';
    public const UPDATED_AT = 'upd_datetime';
    protected $fillable = [
        'del_flag',
        // ...
        'employee_id',
        'project_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    protected static function boot()
    {
        parent::boot();
        // dùng hàm này để gán giá trị thời gian tốt hơn là gán trực tiếp trên thuộc tính không ?
        static::creating(static function ($model) {
            $model->ins_id = auth()->check() ? auth()->user()->id : 1;
            $model->del_flag = IS_NOT_DELETED;
        });

        static::updating(static function ($model) {
            $model->upd_id = auth()->check() ? auth()->user()->id : 1;
        });
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new IsNotDeletedScope());
    }
}
