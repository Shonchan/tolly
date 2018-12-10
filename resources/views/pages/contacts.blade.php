@extends('layouts.layout')

@section('title'){{ "Контактная информация" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('kontakty') }}/@endsection
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
        <h1>Контактная информация</h1>
        <div class="maps">
            <div class="maps-content">
                <ul>
                    <li>
                        <b>Время работы контакт центра</b>
                        <p>с 9:00 до 21:00</p>
                        <p>с понедельника <br>по воскресенье</p>
                    </li>
                    <li>
                        <p><span><a href="tel:84951209083">8 (495) 120-90-83</a></span></p>
                    </li>
                    <li>
                        <p>г. Москва, Хорошевское шоссе, 38Гс136</p>
                    </li>
                    <li>
                        <i><a href="mailto:mail@tolly.ru">mail@tolly.ru</a></i>
                        <p>Вы можете написать нам свои замечания или комментарии к работе сайта</p>
                    </li>
                </ul>
            </div>
            <div class="maps-area">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A1109c42616b217a6932c824010a7d18a2cddb85422eadb972b5bba939ae4bb01&amp;source=constructor" width="100%" height="600" frameborder="0"></iframe>
            </div>
        </div>
    </div>


@endsection
