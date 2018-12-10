@extends('layouts.layout')

@section('title'){{'Ошибка 404'}}@endsection
@section('description')@endsection
@section('canonical')@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')
    <div class="msg404">
        <h1><span>4</span><b></b><span>4</span></h1>
        <h2>Тут ничего нет</h2>
        <p>Попробуйте вернуться назад или поищите что-нибудь другое.</p>
        <p><a href="{{ url('/') }}" class="btn btn1">ПЕРЕЙТИ В КАТАЛОГ</a></p>
    </div>


@endsection