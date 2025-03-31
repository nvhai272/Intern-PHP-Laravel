<?php

use Illuminate\Support\HtmlString;

if (!function_exists('sort_link')) {
    function sort_link($column, $sortBy, $order, $routeName)
    {
        $newOrder = $sortBy === $column && $order === 'asc' ? 'desc' : 'asc';

        if ($order === 'asc') {
            $icon = $sortBy === $column ? ('▲ ▼') : '< >';
        } else {
            $icon = $sortBy === $column ? ('▼ ▲') : '< >';
        }

//        return '<a href="' . route($routeName, ['sort_by' => $column, 'order' => $newOrder]) . '" class="no-underline text-blue-500 hover:text-blue-700">' . ucfirst($column) . " " . $icon . '</a>';
        return new HtmlString('<a href="' . route($routeName, ['sort_by' => $column, 'order' => $newOrder]) . '" class="no-underline text-blue-500 hover:text-blue-700">' . ucfirst($column) . " " . $icon . '</a>');

    }
}
