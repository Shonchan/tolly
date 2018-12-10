@extends('layouts.layout')

@section('title'){{ $page->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url($page->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection


@section('pager')
    <ul class="breadcrumbs">
      <li>
        <a href="/" itemprop="url"><span itemprop="title">Главная</span></a>
      </li>
    </ul>
@endsection

@section('content')
    <div class="page articles">
        <h1>{{ $page->name }}</h1>
        {!! $page->body !!}
    </div>


@endsection
