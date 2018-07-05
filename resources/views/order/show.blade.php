@extends('layouts.layout')

@section('title'){{ "Заказ оформлен" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('order') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">


        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <span itemprop="title">Заказ оформлен</span>
        </li>
    </ul>
@endsection

@section('content')
    <h2>Оформление заказа завершено</h2>
    <p>Заказ на сумму {{ $order->total_price." руб." }} оформлен</p>
@endsection