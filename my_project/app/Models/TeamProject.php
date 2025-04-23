<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamProject extends Pivot
{
    use HasFactory;

    protected $table = 'team_project';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public const CREATED_AT = 'ins_datetime';
    public const UPDATED_AT = 'upd_datetime';
    protected $fillable = [
        'del_flag',
        'team_id',
        'project_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function project()
    {
        return $this->belongsTo(Task::class, 'project_id');
    }

    protected static function boot()
    {
        parent::boot();

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
