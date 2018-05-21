@extends('layouts.layout')

@section('title'){{ $page->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url($page->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')
    <div class="row">
        <h1>{{ $page->name }}</h1>

        <h3>{{ $page->created_at }}</h3>

        <p>
            {!! $page->body !!}
        </p>

    </div>


@endsection