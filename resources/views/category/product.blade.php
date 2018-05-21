@extends('layouts.layout')

@section('title'){{ $product->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url('products', $product->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')
    <div class="row">
        <h1>{{ $product->name }}</h1>
        @foreach ($product->images as $i)
            <img src="{{ url('storage', $i) }}" alt="">
        @endforeach

        <p>
            {!! $product->body !!}
        </p>

    </div>


@endsection