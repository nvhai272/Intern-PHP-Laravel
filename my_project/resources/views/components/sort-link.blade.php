@props(['column', 'label', 'route', 'model' => null])

@php
    // dd($model);

    $routeName = $route . '.list';
    $isSorted = request('sort_by') === $column;

    $newOrder = $isSorted && request('order') === 'asc' ? 'desc' : 'asc';

    $icon = $isSorted ? (request('order') === 'asc' ? '▲' : '▼') : '';

    $queryParams = $model
        ? ['section' => 'status', 'sort_by' => $column, 'order' => $newOrder]
        : ['sort_by' => $column, 'order' => $newOrder];

    $routeParams = $model ? array_merge([$model], $queryParams) : $queryParams;

    if (Str::contains($label, 'Task') && $route === 'project') {
        $routeName = $route . '.detail';
    }
@endphp

<a href="{{ route($routeName, $routeParams) }}" class="text-blue-500 hover:underline">
    {{ $label }} {!! $icon !!}
</a>
