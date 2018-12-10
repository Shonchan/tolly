<ul>
    @foreach($categories as $category)
        <li>{{$category->name}}</li>
        <ul>
            @foreach($category->features as $feature)
            <li>ID <b>{{$feature->id}}</b> {{$feature->name}}</li>
                <ul>
                    @foreach($feature->options as $option)
                        <li>{{$option->value}}</li>
                    @endforeach
                </ul>
            @endforeach
        </ul>
    @endforeach
</ul>

