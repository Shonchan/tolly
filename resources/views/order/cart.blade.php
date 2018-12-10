@extends('layouts.layout')

@section('title'){{ "Корзина" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('cart') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">


        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <span itemprop="title">Корзина</span>
        </li>
    </ul>
@endsection

@section('content')

    <div class="basket">
        {!! Form::open(['action' => 'Views\OrderController@create', 'method' => 'post']) !!}
        @if(isset($total_amount))<h1>В корзине <amount>{{ $total_amount }} {{ Lang::choice('товар|товара|товаров', $cart_total) }}</amount></h1>@else<h1>Корзина</h1>@endif
        @if (isset($variants) && count($variants)>0)
            <div class="basket-row">
                <div class="basket-content">
                    @foreach ($variants as $v)
                        <div class="basket-item">
                            <div class="basket-product">
                                <div class="basket-image">
                                    <a href="{{ url('product', $v->id) }}" style="background-image: url('{{  $v->image }}')"></a>
                                </div>
                                <div class="basket-wrap">
                                    <div class="basket-title"><a href="{{ url('product', $v->id) }}">{{ $v->product->name }}</a></div>
                                    @if($v->name)<div class="basket-type">Вариант: <a href="javascript:;" data-variant_id="{{$v->id}}" data-product_id="{{$v->product->id}}" data-type="ajax" data-src="{{ url('ajax/select_variant') }}">{{ $v->name }}</a></div>@endif
                                    <div class="basket-number">@if ($v->stock > 1)<span class="text-green">{{ "В наличие на складе" }}@elseif($v->stock >= 1 && $v->stock <=2)<span class="text-red">{{"В наличии 1 штука, поторопитесь!"}}
                                    @else<span class="text-red">{{"Нет в наличии"}}@endif</span></div>

                                </div>
                            </div>
                            <div class="basket-price">
                                {!! Form::hidden('variants[id][]', $v->id) !!}
                                <span class="price"><u>Цена: </u><span>{{ $v->price." руб." }}</span></span>
                            </div>
                            <div class="basket-spinner">
                                <input type="text" class="spinner" name="variants[amount][]" value="{{ $v->amount }}" max="{{ $v->stock }}" readonly>
                               <div class="cost">@if($v->amount > 1)<amount>{{$v->amount}}</amount> шт. х {{$v->price}} руб.@endif</div>
                            </div>
                            <div class="basket-total">
                                <span>{{ $v->price*$v->amount." руб." }}</span>
                                {{--<s>19 500 руб</s>--}}
                                <div class="basket-remove">Удалить</div>
                            </div>

                        </div>
                    @endforeach

                </div>
                <div class="basket-sidebar">
                    @include('layouts.basket-sidebar')
                    <div class="basket-sidebar-button">
                        <button type="submit" class="btn btn1 block">ПЕРЕЙТИ К ОФОРМЛЕНИЮ</button>
                    </div>
                </div>
            </div>
            <div class="basket-best">
                <h2>Почему стоит оформить заказ прямо сейчас?</h2>
                <div class="swiper-container js-basketBest">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="best1"></div>
                            <b>Удобная доставка</b>
                            Бесплатная доставка при заказе от&nbsp;2000&nbsp;рублей. Срочная доставка через 2&nbsp;часа.
                        </div>
                        <div class="swiper-slide">
                            <div class="best2"></div>
                            <b>250 пунктов самовывоза в&nbsp;Москве</b>
                            Покупки можно забирать где удобно.
                        </div>
                        <div class="swiper-slide">
                            <div class="best3"></div>
                            <b>Оплата любыми способами</b>
                            7&nbsp;способов оплаты для твоего удобства.
                        </div>
                    </div>
                </div>
            </div>
        {{--<div class="basket-content">--}}
            {{--<div class="basket-head">--}}
                {{--<div class="basket-name">Товар</div>--}}
                {{--<div class="basket-price">Цена</div>--}}
                {{--<div class="basket-cost">Количество</div>--}}
                {{--<div class="basket-total">Сумма</div>--}}
            {{--</div>--}}

                    {{--@foreach ($variants as $v)--}}
                        {{--<div class="basket-item">--}}
                            {{--<div class="basket-product">--}}
                                {{--<div class="basket-image">--}}
                                    {{--<a href="{{ url('product', $v->id) }}" style="background-image: url('{{  $v->image }}')"></a>--}}
                                {{--</div>--}}
                                {{--<div class="basket-wrap">--}}
                                    {{--<div class="basket-title"><a href="{{ url('product', $v->id) }}">{{ $v->product->name }} {{ $v->name }}</a></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="basket-price">--}}
                                {{--{!! Form::hidden('variants[id][]', $v->id) !!}--}}
                                {{--<span class="price"><u>Цена: </u><span>{{ $v->price." руб." }}</span></span>--}}
                            {{--</div>--}}
                            {{--<div class="basket-spinner">--}}
                                {{--<input type="text" class="spinner" name="variants[amount][]" value="{{ $v->amount }}">--}}
                            {{--</div>--}}
                            {{--<div class="basket-total"><u>Итого: </u><span>{{ $v->price*$v->amount." руб." }}</span></div>--}}
                            {{--<div class="basket-remove"></div>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
        {{--</div>--}}
        {{--<div class="basket-coupone">--}}
                {{--<div class="basket-coupone-name">Укажите купон</div>--}}
                {{--<div class="basket-coupone-form">--}}
                        {{--<div class="form-group">--}}
                                {{--<input type="text" class="form-control" placeholder="Имя купона" value="{{$couponeCode}}" />--}}
                                {{--<span class="error">Купон не найден</span>--}}
                        {{--</div>--}}
                        {{--<div class="form-submit">--}}
                                {{--<button class="btn btn3">Применить</button>--}}
                                {{--<div class="loader" style="display: none"></div>--}}
                        {{--</div>--}}
                {{--</div>--}}
        {{--</div>--}}
        {{--<div class="basket-complete">--}}
            {{--<div class="basket-row">--}}
                {{--<div class="basket-col">--}}
                    {{--<ul class="basket-box">--}}
                        {{--<li>--}}
                            {{--<span>Всего <amount>{{ $cart_total }}</amount> товаров на сумму</span>--}}
                            {{--<b>{{ $total." руб." }}</b>--}}
                        {{--</li>--}}
                        {{--<li class="discount">--}}
                            {{--<span>Все скидки</span>--}}
                            {{--<b>-{{$discountValue}} руб.</b>--}}
                        {{--</li>--}}
                        {{--<li class="total">--}}
                            {{--<span>Итого</span>--}}
                            {{--<b>{{ $total - $discountValue." руб." }}</b>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                {{--<div class="basket-col">--}}
                    {{--<button type="submit" class="btn btn1 block">Продолжить оформление</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {!! Form::close() !!}
        @else
            <div class="basket-blank">
                <h2>В&nbsp;вашей корзине пусто? Это не&nbsp;страшно!</h2>
                <p>Если Вы&nbsp;зарегистрированы у&nbsp;нас на&nbsp;сайте и&nbsp;в&nbsp;вашей корзине были товары, то&nbsp;чтобы их&nbsp;увидеть необходимо авторизоваться</p>
                <p><a href="{{ url('') }}" class="btn btn1">ПЕРЕЙТИ К&nbsp;ПОКУПКАМ</a></p>
            </div>
        @endif
    </div>




@endsection