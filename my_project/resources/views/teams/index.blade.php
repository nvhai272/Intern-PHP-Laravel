<h1>Danh sách Teams</h1>
<ul>
    @foreach ($teams as $team)
        <li><a href="{{ url('/teams/' . $team->id) }}">{{ $team->name }}</a></li>
    @endforeach
</ul>
