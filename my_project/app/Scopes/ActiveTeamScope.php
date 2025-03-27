<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveTeamScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('del_flag', '0'); // Tự động chỉ lấy team có del_flag = 0
    }
}
