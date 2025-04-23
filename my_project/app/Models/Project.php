<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public const CREATED_AT = 'ins_datetime';
    public const UPDATED_AT = 'upd_datetime';
    protected $fillable = ['name', 'del_flag'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // emp - project
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project', 'project_id', 'employee_id')
            ->using(TeamProject::class)
            //load các cột trong bảng trung gian
            ->withPivot(['id', 'ins_datetime', 'upd_datetime', 'employee_id'])
            ->withTimestamps();
    }

    public function unassignedEmps()
    {
        $assignedEmpIds = $this->employees()->select('m_employees.id')->pluck('id');
        // lấy danh sách emp_id của project từ bảng trung gian
        return Employee::with('team')
            ->whereNotIn('id', $assignedEmpIds);
    }

    public function assignedEmps()
    {
        // return $this->employees()->with('team'); // lấy tất cả emp đã gán + load team
        return $this->employees()->with(['team:id,name']); // chỉ lấy team id và team name => nhiều hơn 1 truy vấn vì lấy team

        // nếu trong bảng trung gian chưa có xử lí chỉ lấy các record chưa bị xóa mềm thì sao?
    }

    //team - project
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_project', 'project_id', 'team_id')
            ->using(TeamProject::class)
            ->withPivot(['id', 'ins_datetime', 'upd_datetime', 'team_id', 'ins_id', 'upd_id'])
            ->withTimestamps();
    }

    // danh sách team chưa gán cho project
    public function unassignedTeams()
    {
        $assignedTeamIds = $this->teams()->select('m_teams.id')->pluck('id');

        return Team::whereNotIn('id', $assignedTeamIds);
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

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'ins_id');
    }

    public function getCreateByAttribute()
    {
        return $this->createdBy->full_name;
    }

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'upd_id');
    }

    public function getUpdateByAttribute()
    {
        return optional($this->updatedBy)->full_name ?? 'Unknown';
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

    // tương tự hàm trên
    // protected function insDatetime(): Attribute
    // {
    //     return Attribute::make(
    //         get: static fn($value) => $value ? Carbon::parse($value)->format('d-m-Y') : null
    //     );
    // }
}
