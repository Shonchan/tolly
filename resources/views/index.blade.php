@extends('layouts.layout')

@section('title'){{'Tolly'}}@endsection
@section('description')@endsection
@section('canonical'){{ url('') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')
    <div class="row">
        @foreach($cats as $c)
            <div class="col-md-4 mb-4 box-shadow">
                <a href="{{ url('/category', $c->slug) }}">{{ $c->name }}</a>
            </div>
        @endforeach
    </div>


@endsection