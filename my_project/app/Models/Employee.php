<?php

namespace App\Models;

use App\Constants\Constant;
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

    // protected $casts = [
    //     'birthday'     => 'date',
    //     'ins_datetime' => 'datetime:d-m-Y H:i:s',
    //     'upd_datetime' => 'datetime',
    // ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    // emp - pro
       public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project', 'employee_id', 'project_id')
        ->using(EmployeeProject::class)
            ->withPivot(['id', 'del_flag', 'ins_datetime', 'upd_datetime','employee_id','project_id'])
            ->withTimestamps();
    }

    // emp - task
      public function tasks()
    {
        return $this->belongsToMany(Project::class, 'employee_task', 'employee_id', 'task_id')
        ->using(EmployeeTask::class)
            ->withPivot(['id', 'del_flag', 'ins_datetime', 'upd_datetime','task_id','employee_id'])
            ->withTimestamps();
    }


    protected static function booted(): void
    {
        static::addGlobalScope(new IsNotDeletedScope());
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

    // Scope search by full_name
    public function scopeSearchFullName($query, $name)
    {
        return $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
    }

    // Scope sort by full_name
    public function scopeSortByFullName($query, $order = 'asc')
    {
        return $query->orderByRaw("CONCAT(first_name, ' ', last_name) $order");
    }

    // Accesstor and mutator

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusAttribute()
    {
        return Constant::$status[$this->attributes['status']] ?? 'Unknown';
    }

    public function getPositionAttribute()
    {
        return Constant::$positions[$this->attributes['position']] ?? 'Unknown';
    }

    public function getGenderAttribute()
    {
        return Constant::$genders[$this->attributes['gender']] ?? 'Unknown';
    }

    public function getWorkAttribute()
    {
        return Constant::$workTypes[$this->type_of_work] ?? 'Unknown';
    }

    public function getSalaryAttribute()
    {
        return "{$this->attributes['salary']}  VND";
    }

    public function getBirthdayAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['birthday'])->format('d-m-Y');
    }
}
