@extends('layouts.layout')

@section('title'){{ $category->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url('category', $category->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')
    <div class="row">
        @foreach($category->products as $p)
            <div class="col-md-4 mb-4 box-shadow">
                <a href="{{ url('/products', $p->slug) }}">{{ $p->name }}</a>
            </div>
        @endforeach
    </div>


@endsection