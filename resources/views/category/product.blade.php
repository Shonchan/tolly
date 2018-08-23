@extends('layouts.layout')

@section('title'){{ $product->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url('products', $product->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">
        @if($product->categories[0])
            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemref="breadcrumb-1">
                <a href="{{ url($product->categories[0]->slug) }}" itemprop="url"><span itemprop="title">{{ $product->categories[0]->name }}</span></a>
            </li>
        @endif

        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <a {{ url($product->slug) }} itemprop="url"><span itemprop="title">{{ $product->name }}</span></a>
        </li>.
    </ul>
@endsection

@section('content')

    <div class="single" itemscope itemtype="http://schema.org/Product">
        {!! Form::open(['route' => 'addToCart', 'method' => 'post']) !!}
        <h1 itemprop="name">{{ $product->name }}</h1>
        <div class="single-row" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="single-carousel">
                <div class="single-slider js-single" itemprop="image">
                    @foreach ($product->images as $i)
                        <div class="single-slide"><a href="{{ url('storage', $i) }}" data-fancybox="gallery"><img src="{{ url('storage', $i) }}" alt=""></a></div>
                    @endforeach

                </div>
                @if(count($product->images)>1)
                <div class="single-thumbnails">
                    @foreach ($product->images as $i)
                        <a href="javascript:;" data-slide-index="{{ $loop->index }}"><img class="sp-thumbnail" src="{{ url('storage', $i) }}"></a>
                    @endforeach
                </div>
                @endif

            </div>
            <div class="single-content">
                <div class="single-fixed">
                    <div class="single-rating">
                        <div class="star">
                            <ul class="star-list">
                                <li class="star-field current"></li>
                                <li class="star-field current"></li>
                                <li class="star-field current"></li>
                                <li class="star-field"></li>
                                <li class="star-field"></li>
                            </ul>
                        </div>
                        <div class="review">Отзывов (0)</div>
                        <div class="comment"><a href="javascript:;">Оставить отзыв</a></div>
                    </div>
                    <ul class="single-option">
                        <li><b>В наличии:</b> <span>{{ $product->variant()->stock }} шт.</span></li>
                        <li><b>Код товара:</b> <span>{{ $product->variant()->external_id }}</span></li>
                    </ul>
                    <ul class="single-price">
                        @if($product->variant()->compare_price)<li>Цена <span class="old">{{ $product->variant()->compare_price }} руб.</span> <span class="discount">-{{ 100-round($product->variant()->price/$product->variant()->compare_price*100)  }}%</span></li>@endif
                        <li>
                            <div class="price" itemprop="price">{{ $product->variant()->price }} <span class="cur">руб.</span></div>
                            <meta itemprop="priceCurrency" content="RUB">
                            <link itemprop="availability" href="http://schema.org/InStock">
                        </li>
                    </ul>
                    <input type="hidden" name="variant_id" value="{{ $product->variant()->id }}">
                    <div class="single-submit">
                        <div class="control"><button type="submit" class="btn btn1 block">Добавить в корзину</button></div>
                        <div class="click"><button type="button" class="btn link">купить в 1 клик</button></div>
                    </div>
                    <ul class="single-delivery">
                        <li>Доставка за 299 рублей</li>
                        <li>Доступно для доставки {{ $product->variant()->stock }} шт.</li>
                        <li>Самовывоз из <a href="javascript:;">24 пунктов</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="single-description">
            <div class="single-tabs js-tabs">
                <div class="single-tabs-nav">
                    <ul>
                        <li><a href="javascript:;" class="js-tabs-a current" data-id="block1">Характеристики</a></li>
                        <li><a href="javascript:;" class="js-tabs-a" data-id="block2">Цены</a></li>
                        <li><a href="javascript:;" class="js-tabs-a" data-id="block3">Отзывы</a></li>
                    </ul>
                </div>
                <div itemprop="description">
                    <div class="single-block js-tabs-body" data-id="block1" style="display: block">
                        <h2>Описание</h2>
                        <p>{!! $product->body !!} </p>
                        <h3>Основные характеристики</h3>
                        <ul class="single-charact">
                            <li>
                                <span>Производитель</span>
                                <b>{{ $product->brand->name }}</b>
                            </li>
                            @foreach ($options as $o)
                                <li>
                                    <span>{{ $o->name }}</span>
                                    <b>{{ $o->value }}</b>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="single-block js-tabs-body" data-id="block2">
                        <h2>Дополнительная информация</h2>
                        <p>К сожалению на горные лыжи Fischer Progressor 800 Powerrail (14/15) нет ни одного предложения! Но насколько нам известно, последние цены в России на него были от 36 760 до 36 760 рублей. А еще с Fischer Progressor 800 Powerrail (14/15) часто ищут отзывы о head supershape и хотят узнать характеристики salomon suspect.</p>
                    </div>
                    <div class="single-block js-tabs-body" data-id="block3">
                        <h2>Отзывы о товаре</h2>
                        <p>У этого товара пока нет отзывов. Поделитесь своим мнением об этом товаре, и многие будут вам благодарны.</p>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>


@endsection