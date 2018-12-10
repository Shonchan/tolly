<li>
    <span>Производитель</span>
    <b>{{ $brand->name }}</b>
</li>
@foreach ($options as $o)
    <li>
        <span>{{ $o->name }}</span>
        <b>{{ implode(', ', $o->values) }}</b>
    </li>
@endforeach