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
        <div class="basket-content">
            <div class="basket-head">
                <div class="basket-name">Товар</div>
                <div class="basket-price">Цена</div>
                <div class="basket-cost">Количество</div>
                <div class="basket-total">Сумма</div>
            </div> @if ($cart)

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

                <h3>Итого: {{ $total." руб." }}</h3>
            @else
                В корзине нет товаров
            @endif

        </div>
        <div class="basket-complete">
            <div class="basket-row">
                <div class="basket-col">
                    <ul class="basket-box">
                        <li>
                            <span>Всего 9 товаров на сумму</span>
                            <b>{{ $total." руб." }}</b>
                        </li>
                        <li>
                            <span>Все скидки</span>
                            <b>-1 941 руб.</b>
                        </li>
                        <li class="total">
                            <span>Итого</span>
                            <b>55 979 руб.</b>
                        </li>
                    </ul>
                </div>
                <div class="basket-col">
                    <button type="submit" class="btn btn1 block">Продолжить оформление</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>



@endsection