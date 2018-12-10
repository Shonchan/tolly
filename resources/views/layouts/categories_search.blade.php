@foreach ($categories as $cat)
    <li class="filter-menu-block">
        <a href="javascript:;" cid="{{ $cat->id }}" class="category"><span></span>{{ $cat->name }} ({{ $cat->countPositions }})</a>
    </li>
@endforeach