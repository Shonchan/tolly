@extends('layouts.layout')

@section('title'){{ "Оформление заказа" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('order') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">


        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <span itemprop="title">Оформление заказа</span>
        </li>
    </ul>
@endsection

@section('content')
    {!! Form::open(['action' => 'Views\OrderController@store', 'method' => 'post']) !!}
    @foreach($variants as $p)
        <input type="hidden" name="purchases[id][]" value="{{ $p->id }}">
        <input type="hidden" name="purchases[amount][]" value="{{ $p->amount }}">
        <input type="hidden" name="purchases[price][]" value="{{ $p->price }}">

    @endforeach
    <div class="order">
        <h1>Оформление заказа</h1>
        <div class="order-row">
            <div class="order-form">

                    <div class="form-group">
                        <label for="">ФИО*</label>
                        <div class="control">
                            <input name="name" type="text" class="form-control" required>
                        </div>
                    </div>
                    {{--<div class="form-group">
                        <label for="">Фамилия*</label>
                        <div class="control">
                            <input  name="lastname" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Отчество*</label>
                        <div class="control">
                            <input name="midname" type="text" class="form-control">
                        </div>
                    </div>--}}
                    <div class="form-group">
                        <label for="">E-mail</label>
                        <div class="control">
                            <input  name="email" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Телефон*</label>
                        <div class="control">
                            <input  name="phone" type="text" class="form-control" required>
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label for="">Доп. телефон</label>--}}
                        {{--<div class="control">--}}
                            {{--<input  name="addphone" type="text" class="form-control">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="">Город</label>--}}
                        {{--<div class="control">--}}
                            {{--<div class="form-location">--}}
                                {{--<div class="location">--}}
                                    {{--<div class="location-button">Москва</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="js-tabs">
                        <div class="form-group">
                            <label for="">Тип доставки</label>
                            <div class="control">
                                <ul class="delivery">
                                    <li><a href="#" class="js-tabs-a" data-id="delivery1">Курьером</a></li>
                                    <li><a href="#" class="js-tabs-a current" data-id="delivery2">Самовывоз из ТК</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="js-tabs-body" data-id="delivery1">
                            <div class="form-group">
                                <label for="">Адрес доставки*</label>
                                <div class="control">
                                    <div class="row-item">
                                        <input type="text"  name="address[street]" class="form-control" placeholder="Улица">
                                    </div>
                                    <div class="row-group">
                                        <div class="row-item">
                                            <input type="text" name="address[house]" class="form-control" placeholder="Дом">
                                        </div>
                                        <div class="row-item">
                                            <input type="text" name="address[corp]" class="form-control" placeholder="Корп. / стр.">
                                        </div>
                                        <div class="row-item">
                                            <input type="text" name="address[apt]" class="form-control" placeholder="Кв. / оф.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Способ доставки</label>
                            <div class="control">
                                <select name="delivery_method_id" class="select">

                                        @foreach ($del_methods as $del)
                                            <option value="{{ $del->id }}">{{ $del->name }}</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Тип оплаты</label>
                            <div class="control">
                                <select name="payment_method_id">
                                    @foreach ($pay_methods as $pay)
                                        <option value="{{ $pay->id }}">{{ $pay->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Комментарий</label>
                            <div class="control">
                                <textarea name="comment" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <div class="basket">
        <div class="basket-complete">
            <div class="basket-row">
                <div class="basket-col">
                    <ul class="basket-box">
                        <li>
                            <span>Всего {{ $total_amount }} товаров на сумму</span>
                            <b>{{ $total_sum }} руб.</b>
                        </li>
                        <li>
                            <span>Все скидки</span>
                            <b>0 руб.</b>
                        </li>
                        <li class="total">
                            <input type="hidden" name="total_sum" value="{{ $total_sum }}">
                            <span>Итого</span>
                            <b>{{ $total_sum }} руб.</b>
                        </li>
                    </ul>
                </div>
                <div class="basket-col">
                    <button type="submit" class="btn btn1 block">Заказать</button>
                </div>
            </div>
        </div>
    </div>

{!! Form::close() !!}

@endsection