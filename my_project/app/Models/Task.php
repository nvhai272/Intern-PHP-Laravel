<?php

namespace App\Models;

use App\Constants\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public const CREATED_AT = 'ins_datetime';
    public const UPDATED_AT = 'upd_datetime';
    protected $fillable = ['name', 'del_flag', 'task_status', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    // emp - task
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_task', 'task_id', 'employee_id')
            ->using(EmployeeTask::class)
            ->withPivot(['id', 'del_flag', 'ins_datetime', 'upd_datetime', 'task_id', 'employee_id'])
            ->withTimestamps();
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

    public function getStatusAttribute()
    {
        // return Constant::$tasks[$this->attributes['task_status']] ?? 'Unknown';
        // do ten ham khong trung vs ten thuoc tinh model nen goi thang model
        return Constant::$tasks[$this->task_status] ?? 'Unknown';
    }

    protected function updDatetime(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => $value ? Carbon::parse($value)->format('d-m-Y') : null
        );
    }

    public function getInsDatetimeAttribute()
    {
        return Carbon::parse($this->attributes['ins_datetime'])->format('d-m-Y');
    }


    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'ins_id');
    }

    public function getCreateByAttribute()
    {
        return $this->createdBy->full_name;
    }
}
