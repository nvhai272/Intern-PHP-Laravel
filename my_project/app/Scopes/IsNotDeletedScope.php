<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class IsNotDeletedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('del_flag', IS_NOT_DELETED); // Tự động chỉ lấy Model có del_flag = 0
    }
}
