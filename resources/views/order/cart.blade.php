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
        <h1>Корзина</h1>
        @if (isset($variants) && count($variants)>0)
        <div class="basket-content">
            <div class="basket-head">
                <div class="basket-name">Товар</div>
                <div class="basket-price">Цена</div>
                <div class="basket-cost">Количество</div>
                <div class="basket-total">Сумма</div>
            </div>

                    @foreach ($variants as $v)
                        <div class="basket-item">
                            <div class="basket-product">
                                <div class="basket-image">
                                    <a href="javascript:;" style="background-image: url('{{ url('storage', $v->product->img()) }}')"></a>
                                </div>
                                <div class="basket-wrap">
                                    <div class="basket-title"><a href="javascript:;">{{ $v->product->name }} {{ $v->name }}</a></div>
                                </div>
                            </div>
                            <div class="basket-price">
                                {!! Form::hidden('variants[id][]', $v->id) !!}
                                <span class="price"><u>Цена: </u>{{ $v->price." руб." }}</span>
                            </div>
                            <div class="basket-spinner">
                                <input type="text" class="spinner" name="variants[amount][]" value="{{ $v->amount }}">
                            </div>
                            <div class="basket-total"><u>Итого: </u>{{ $v->price*$v->amount." руб." }}</div>
                            <div class="basket-remove"></div>
                        </div>

                    @endforeach




        </div>
        <div class="basket-complete">
            <div class="basket-row">
                <div class="basket-col">
                    <ul class="basket-box">
                        <li>
                            <span>Всего <amount>{{ $cart_total }}</amount> товаров на сумму</span>
                            <b>{{ $total." руб." }}</b>
                        </li>
                        <li class="discount">
                            <span>Все скидки</span>
                            <b>-0 руб.</b>
                        </li>
                        <li class="total">
                            <span>Итого</span>
                            <b>{{ $total." руб." }}</b>
                        </li>
                    </ul>
                </div>
                <div class="basket-col">
                    <button type="submit" class="btn btn1 block">Продолжить оформление</button>
                </div>
            </div>
        </div>
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