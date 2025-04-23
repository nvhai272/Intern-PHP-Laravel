<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class SortLink extends Component
{
    public $route;
    public $column;
    public $label;
    public $model;

    public function __construct($column, $label,$model = null)
    {
        $this->route = $this->getRoute();
        $this->column = $column;
        $this->label = $label;
        $this->model =$model;
    }

    private function getRoute()
    {
        $currentRouteName = Route::currentRouteName();
        return match (true) {
            str_contains($currentRouteName, 'project') => 'project',
            str_contains($currentRouteName, 'team')    => 'team',
            str_contains($currentRouteName, 'task')    => 'task',
            str_contains($currentRouteName, 'emp')     => 'emp',
            default                                => 'default.route',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sort-link');
    }
}
