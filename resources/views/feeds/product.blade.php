@extends('layouts.layout')
@section('title')@if($variant->name){{ $product->name }} {{ $variant->name }}@else{{ $product->name }}@endif @if($product->seo){{ $product->seo }} @endif{{'в интернет-магазине TOLLY.ru'}}@endsection
@section('description')@if($variant->name){{ $product->name }} {{ $variant->name }}@else{{ $product->name }}@endif @if($product->seo) {{ $product->seo }}@endif{{' купить по выгодной цене в Москве. Отзывы покупателей. Доставка от 1 дня!'}}@endsection
@section('canonical'){{ url('product', $product->id) }}@endsection
@section('ogtitle')@if($variant->name){{ $product->name }} {{ $variant->name }}@else{{ $product->name }}@endif  @if($product->seo){{ $product->seo }} @endif{{'в интернет-магазине TOLLY.ru'}}@endsection
@section('ogdescription'){{'В наличии '}}{{ $product->variant()->stock }}{{' шт., цена '}}{{ $product->variant()->price }}{{' рублей, доставка 1 день!'}}@endsection
@section('ogimage'){{ url('storage', $product->images) }}@endsection

@section('pager')
<ul class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    @if($product->categories[0])
    <li>
        <a href="{{ url($product->categories[0]->slug) }}" itemprop="url"><span itemprop="title">{{ $product->categories[0]->name }}</span></a>
    </li>
    @endif
    <li>
        <a href="{{ url('products', $product->id) }}" itemprop="url"><span itemprop="title">{{ $product->name }}</span></a>
    </li>
</ul>
@endsection

@section('content')

<script type="text/javascript">
    options = {!! json_encode($variants) !!};
    var variant_id = {!! $vid !!};
</script>

    <div class="single" itemscope itemtype="http://schema.org/Product">
        <h1 itemprop="name">{{ $product->name }} <span>{{ $variant->name }}</span>@if($product->seo), {{ $product->seo }}@endif</h1>
        <div class="single-row" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="single-carousel">
                <div class="single-slider js-single" itemprop="image">
                    @foreach ($product->images as $i)
                        <div class="single-slide"><a href="{{ url('storage', $i) }}" data-fancybox="gallery"><img src="{{ $product->imgSize(500,500, $i) }}" alt=""></a></div>
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
                                @for($i = 1; $i <= 5; $i++)
                                <li class="star-field @if($i <= $averageStar) current @endif"></li>
                                @endfor
                            </ul>
                        </div>
                        <div class="review">Отзывов ({{count($reviews)}})</div>
                        <div class="comment"><a href="#add_review_form" class="add_review_open">Оставить отзыв</a></div>
                    </div>
                    <ul class="single-option">
                        <li><b>В наличии:</b> <span>{{ $variant->stock }} шт.</span></li>
                        <li><b>Код товара:</b> <span>{{ $variant->external_id }}</span></li>
                    </ul>
                    <ul class="single-price">
                        @if($product->variant()->compare_price)
                        <li>Цена <span class="old">{{ $product->variant()->compare_price }} руб.</span> <span class="discount">-{{ 100-round($product->variant()->price/$product->variant()->compare_price*100)  }} %</span></li>
                        @endif
                        <li>
                            <div class="price" itemprop="price">{{ $product->variant()->price }} <span class="cur">руб.</span></div>
                            <meta itemprop="priceCurrency" content="RUB">
                            <link itemprop="availability" href="http://schema.org/InStock">
                        </li>
                    </ul>

                    @if (count($variants) > 1)
                    <div class="single-setting">
                        <b>Вариант</b>
                        <select class="select-single"></select>
                    </div>
                    @endif

                    <div class="single-submit">
                        {!! Form::open(['id' => 'add_to_cart']) !!}
                        <input type="hidden" name="variant_id" value="{{ $vid }}">
                        <div class="control">
                            <button href="#order_content" class="btn btn1 block add_to_cart">Добавить в корзину</button>
                        </div>
                        <div class="blank" id="order_content" style="display: none">
                            <h1>Товар успешно добавлен</h1>
                            <div class="basket">
                                <div class="basket-item">
                                    <div class="basket-product">
                                        <div class="basket-image">
                                            <a href="{{ url('product', $product->id) }}" style="background-image: url('{{ $product->imgSize(320,200, $product->images[0]) }}')"></a>
                                        </div>
                                        <div class="basket-wrap">
                                            <div class="basket-title">
                                                <a href="{{ url('product', $product->id) }}">{{ $product->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="basket-spinner">
                                        <input type="text" class="spinner_one" name="variants[amount][]" value="{{ $amount }}">
                                        <div class="basket-price">
                                            {!! Form::hidden('variants[id][]', $vid) !!}
                                            {!! Form::hidden('price', $vprice) !!}
                                            <span class="price"><u>Цена: </u>{{ $vprice." руб/шт" }}</span>
                                        </div>
                                    </div>
                                    <div class="basket-total">
                                        <u>Итого: </u><span>{{ $vprice*$amount." руб." }}</span>
                                    </div>
                                    <div class="basket-remove"></div>
                                </div>
                                <div class="blank-more">
                                    {{--<div class="blank-content articles">--}}
                                    {{--<a href="javascript:;">В корзине 2 товара</a> на сумму 8200 рублей--}}
                                    {{--</div>--}}
                                    <div class="blank-submit">
                                        <a href="javascript:;" onclick="$.fancybox.close();" class="btn btn2">Продолжить покупки</a>
                                        <a href="{{ url('cart') }}" class="btn btn3">Перейти в корзину</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="click">
                            <button type="button" href="#buy_one_click_form" class="btn link one_click">купить в 1 клик</button>
                        </div>
                        <div class="navbar-tallme" id="buy_one_click_form" style="display: none">
                            <div class="navbar-title">Заказ в 1 клик</div>
                            <p></p>
                            {!! Form::open(['id' => 'on_click']) !!}

                            {!! Form::hidden('count', 1) !!}
                            {!! Form::hidden('vid', $vid) !!}
                            {!! Form::hidden('pid', $product->id) !!}
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Ваше имя">
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" data-mask="+7 (999) 999-99-99" class="form-control" placeholder="Ваш телефон">
                            </div>
                            <div class="form-submit">
                                <button type="submit" class="btn btn1 block">Купить</button>
                            </div>
                            <div class="errors"></div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <ul class="single-delivery">
                        <li>Доставка за 299 рублей</li>
                        <li>Доступно для доставки {{ $product->variant()->stock }} шт.</li>
                        <li>Самовывоз из <a rel="nofollow" href="{{ url('dostavka') }}">31 пункта</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="single-description">
            <div class="single-tabs js-tabs">
                <div class="single-tabs-nav">
                    <ul>
                        <li><a href="javascript:;" class="js-tabs-a current" data-id="block1">Характеристики</a></li>
                        <li><a href="javascript:;" class="js-tabs-a" data-id="block2">Отзывы</a></li>
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
                                    <b>{{ implode(', ', $o->values) }}</b>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="single-block js-tabs-body" data-id="block2">
                        <h2>Отзывы о товаре</h2>
                        @if(count($reviews) == 0)
                            <p>У этого товара пока нет отзывов. Поделитесь своим мнением об этом товаре, и многие будут вам благодарны.</p>
                        @else
                            <div class="msg">
                                @foreach($reviews as $review)
                                    <div class="msg-item">
                                        <div class="msg-more">
                                            <div class="msg-title">@if(isset($review->user)){{$review->user->name}}@else{{$review->name}}@endif</div>
                                            <div class="msg-time">{{$review->created_at->format('d.m.Y')}}</div>
                                        </div>
                                        <div class="msg-text">
                                            <p>{{$review->comment}}</p>
                                        </div>
                                        @if(isset($review->commentManager))
                                            <div class="msg-item">
                                                <div class="msg-more">
                                                    <div class="msg-title">
                                                        Менеджер {{$review->commentManager->manager->name}}</div>
                                                    <div class="msg-time">{{$review->commentManager->created_at->format('d.m.Y')}}</div>
                                                </div>
                                                <div class="msg-text">
                                                    <p>{{$review->commentManager->comment}}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="msg-reply"><a href="#add_review_form" class="add_review_open">Добавить отзыв</a>
                        </div>
                        <div style="display: none;" id="add_review_form" class="msg-popup">
                            <div class="msg-form">
                                <h3>Добавить отзыв</h3>
                                {!! Form::open(['id' => 'add_review']) !!}
                                <div class="row">
                                    <div class="form-group">
                                        <input type="text" name="name"
                                               value="@if(\Auth::id()){{\Auth::user()->name}}@endif"
                                               @if(\Auth::id())disabled @endif class="form-control"
                                               placeholder="Ваше имя">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email"
                                               value="@if(\Auth::id()){{\Auth::user()->email}}@endif"
                                               @if(\Auth::id())disabled @endif class="form-control"
                                               placeholder="Ваше e-mail">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="rating">
                                        <label for="rank">Ваша оценка</label>
                                        <div class="control">
                                            <div class="js-rating"></div>
                                            <input type="hidden" name="rating" value="" id="rating_value">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea rows="8" name="comment" class="form-control"
                                              placeholder="Ваш комментарий"></textarea>
                                </div>
                                {{--<div class="form-group">
                                  <div class="captcha"></div>
                                </div>--}}
                                <div class="form-group">
                                    <button type="submit" class="btn btn1">Добавить отзыв</button>
                                </div>
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="errors"></div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($similarProducts) && count($similarProducts) > 0)
            <div class="single-similar">
                <h3>Похожие товары</h3>
                <div class="single-similar-control">
                    <div class="button prev"></div>
                    <div class="button next"></div>
                </div>
                <div class="js-similar">
                    <div class="swiper-wrapper">
                        @foreach ($similarProducts as $sProduct)
                            <div class="swiper-slide">
                                <div class="post" itemscope itemtype="http://schema.org/Product">
                                    <div class="pc">
                                        <div class="pc-image"><a data-similar="{{ $sProduct->id }}" href="{{ url('/product', $sProduct->variant()->id) }}"><img src="{{ $sProduct->imgSize(320,200, $sProduct->images[0]) }}" alt="" itemprop="image"></a></div>
                                        <div class="pc-name"><a data-similar="{{ $sProduct->id }}" href="{{ url('/product', $sProduct->variant()->id) }}" itemprop="name">{{$sProduct->name}}</a></div>
                                        <meta content="{{$sProduct->name}}" itemprop="description">
                                        <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                            <div class="pc-price"><span itemprop="price">{{$sProduct->variant()->price}}</span> руб.
                                                <meta content="RUB" itemprop="priceCurrency">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection