@extends('layouts.layout')
@section('title')Купить @if($variant->name){{ mb_lcfirst($product->name) }} {{ $variant->name }}@else{{ mb_lcfirst($product->name) }}@endif @if($product->seo){{ $product->seo }} @endif{{'в интернет-магазине TOLLY.ru'}}@endsection
@section('description')@if($variant->name){{ $product->name }} {{ $variant->name }}@else{{ $product->name }}@endif @if($product->seo) {{ $product->seo }}@endif{{' купить по выгодной цене в Москве. Отзывы покупателей. Доставка от 1 дня!'}}@endsection
@section('canonical'){{ url('product', $variant->id) }}@endsection
@section('ogtitle')@if($variant->name){{ $product->name }} {{ $variant->name }}@else{{ $product->name }}@endif  @if($product->seo){{ $product->seo }} @endif{{'в интернет-магазине TOLLY.ru'}}@endsection
@section('ogdescription'){{'В наличии '}}{{ $product->variant()->stock }}{{' шт., цена '}}{{ $product->variant()->price }}{{' рублей, доставка 1 день!'}}@endsection
@section('ogimage'){{ url('storage', $product->images) }}@endsection

@section('pager')
<ul class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <li>
    <a href="/" itemprop="url"><span itemprop="title">Главная</span></a>
  </li>
  @if($product->categories[0])
  <li>
    <a href="{{ url($product->categories[0]->slug) }}" itemprop="url"><span itemprop="title">{{ $product->categories[0]->name }}</span></a>
  </li>
  @endif
</ul>
@endsection

@section('content')

<script type="text/javascript">
    options = {!! json_encode($variants) !!};
    var variant_id = {!! $vid !!};
</script>

    <div class="single" itemscope itemtype="http://schema.org/Product">
      <meta itemprop="name" content="{{ $product->name }} {{ $variant->name }}@if($product->seo), {{ $product->seo }}@endif">
      <div class="single-row" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="single-carousel">
              <div class="fotorama" data-nav="thumbs">
                @foreach ($product->images as $i)
                <a href="{{ url('storage', $i) }}" data-fancybox="gallery"><img itemprop="image" src="{{ $product->imgSize(320,200, $i) }}" alt="{{ $product->name }} {{ $variant->name }}@if($product->seo), {{ $product->seo }}@endif"></a>
                @endforeach
              </div>
            </div>
            <div class="single-content">
                <div class="single-fixed">
                    <h1>{{ $product->name }} <span>{{ $variant->name }}</span>@if($product->seo), {{ $product->seo }}@endif</h1>
                    <div class="single-rating">
                        <div class="star">
                            <ul class="star-list">
                                @for($i = 1; $i <= 5; $i++)
                                <li class="star-field @if($i <= $averageStar) current @endif"></li>
                                @endfor
                            </ul>
                        </div>
                        <div class="review"><a href="#block2" title="Оставить отзыв">@if (count($reviews) > 0){{ count($reviews) }} {{ plural(count($reviews), ["отзыв","отзывов", "отзыва"]) }}@else{{ 'нет отзывов' }}@endif</a></div>
      									<div class="comment">Артикул {{ $variant->external_id }}</div>
                    </div>
                    <ul class="single-option">
                    @if ($variant->stock == 3)<li class="text-orange">Поторопитесь, скоро закончится!</li>
                    @elseif ($variant->stock == 2)<li class="text-orange">Поторопитесь, осталось две штуки!</li>
                    @elseif ($variant->stock == 1)<li class="text-orange">Поторопитесь, осталась одна штука!</li>
                    @elseif ($variant->stock == 0)<li class="text-red">К сожалению, товар разобрали</li>
                    @else<li class="text-green">В наличии на складе</li>
                    @endif
                    </ul>


                    <ul class="single-price">
                        @if($variant->compare_price)
                        <li><span class="old">{{ $variant->compare_price }} руб.</span> <span class="discount"> -{{ 100-round($variant->price/$variant->compare_price*100)  }} %</span></li>
                        @endif
                        <li>
                            <div class="price"><span itemprop="price">{{ $variant->price }}</span> <span class="cur"> руб.</span></div>
                            <meta itemprop="priceCurrency" content="RUB">
                            <link itemprop="availability" href="http://schema.org/InStock">
                        </li>
                    </ul>

                    @if (count($variants) > 1)
                    <div class="single-setting">
                        <b>Вариант</b>
                        <div class="single-setting-list">
                            @foreach ($variants as $v)
                                <a data-stock="{{$v->stock}}" @if($variant->compare_price)data-compare_price="{{$v->compare_price}}"@endif data-price="{{$v->price}}" data-id="{{$v->id}}" href="{{ url('product', $v->id) }}"@if($v->id == $variant->id) class="current"@endif>{{ $v->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="single-submit">
                        {!! Form::open(['id' => 'add_to_cart']) !!}
                        <input type="hidden" name="variant_id" value="{{ $vid }}">
                        <div class="control">
                        @if($variant->stock > 0)
                            <button href="#order_content" class="btn btn1 block add_to_cart">Добавить в корзину</button>
                        @else
                            <button href="#order_content" class="btn btn1 block add_to_cart" disabled>Товар закончился</button>
                        @endif
                        </div>
                        <div class="blank" id="order_content" style="display: none">
                            <div class="blank-title">Товар добавлен в корзину</div>
                            <div class="basket">
                                <div class="basket-item">
                                    <div class="basket-product">
                                        <div class="basket-image">
                                            <a href="{{ url('product', $variant->id) }}" style="background-image: url('@if(isset($product->images[0])){{ $product->imgSize(320,200, $product->images[0]) }}@endif')"></a>
                                        </div>
                                        <div class="basket-wrap">
                                            <div class="basket-title">
                                                <a href="{{ url('product', $variant->id) }}">{{ $product->name }} <span>{{ $variant->name }}</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="basket-spinner">
                                        {!! Form::hidden('variants[id][]', $variant->id) !!}
                                        {!! Form::hidden('variants[price][]', $variant->price) !!}
                                        {!! Form::hidden('variants[amount][]', $amount, ['data-max'=>$variant->stock]) !!}
                                        <div class="ui-spin">
                                            <span class="minus" onclick=""></span>
                                            <div class="count">{{$amount}}</div>
                                            <span class="plus" onclick=""></span>
                                        </div>
                                        <div class="cost"></div>
                                    </div>
                                    <div class="basket-total">
                                       <span>{{ $vprice*$amount." руб." }}</span>
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


                            <!--div class="click"@if($variant->stock == 0) style="display: none"@endif>
                                <button type="button" href="#buy_one_click_form" class="btn link one_click">купить в 1 клик</button>
                            </div-->
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
                    @if($variant->stock > 0)
                    <ul class="single-delivery">
                        <li><b>Доставка курьером</b><br />
                            @php
                            $info = 'послезавтра и позже';
                            $sec = time() - strtotime('today');

                            if($sec >= 0 && $sec <= 42000)
                                $info = 'завтра и позже';

                            @endphp
                            @if($product->variant()->price > 2999)
                            {{ $info.' - бесплатно' }}
                            @else
                            {{ $info.' - 300 руб' }}
                            @endif
                        </li>
                        <li><b>Самовывоз</b><br />
                            @php
                            $info = 'послезавтра и позже';
                            $sec = time() - strtotime('today');

                            if($sec >= 0 && $sec <= 42000)
                                $info = 'завтра и позже';

                            @endphp
                            @if($product->variant()->price > 2999)
                            {{ $info.' - бесплатно' }}
                            @else
                            {{ $info.' - 150 руб' }}
                            @endif
                        </li>
                        <li><b>Оплата</b><br />Наличными или банковской картой</li>
                    </ul>
                    @endif
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
                        <h2>Описание и характеристики</h2>
                        <p>{!! $product->body !!} </p>
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
                                <div class="msg-user">
                                  <div class="msg-userpic"><img src="http://placehold.it/40x40" alt=""></div>
                                  <div class="msg-more">
                                    <div class="msg-title">@if(isset($review->user)){{$review->user->name}}@else{{$review->name}}@endif</div>
                                    <div class="msg-time">{{$review->created_at->format('d.m.Y')}}</div>
                                  </div>
                                  <div class="msg-star">
                                    <ul class="star-list">
                                      <li class="star-field current"></li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="msg-text">
                                  <p>{{$review->comment}}</p>
                                  <div class="msg-rank">
                                    <span>Отзыв был полезен?</span>
                                    <ul>
                                      <li><span class="plus"><i class="fas fa-thumbs-up"></i></span></li>
                                      <li><span class="minus"><i class="fas fa-thumbs-down"></i></span></li>
                                    </ul>
                                  </div>
                                </div>
                                @if(isset($review->commentManager))
                                <div class="msg-item">
                                  <div class="msg-user">
                                    <div class="msg-userpic"><img src="http://placehold.it/40x40" alt=""></div>
                                    <div class="msg-more">
                                      <div class="msg-title">Менеджер {{$review->commentManager->manager->name}}</div>
                                      <div class="msg-time">{{$review->commentManager->created_at->format('d.m.Y')}}</div>
                                    </div>
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
                                        <input type="text" name="name" value="@if(\Auth::id()){{\Auth::user()->name}}{}@endif" @if(\Auth::id())disabled @endif class="form-control" placeholder="Ваше имя">
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
                                        <div class="pc-image"><a data-similar="{{ $sProduct->id }}" href="{{ url('/product', $sProduct->variant()->id) }}"><img src="@if(isset($sProduct->images[0])){{ $sProduct->imgSize(320,200, $sProduct->images[0]) }}@endif" alt="" itemprop="image"></a></div>
                                        <div class="pc-name"><a data-similar="{{ $sProduct->id }}" href="{{ url('/product', $sProduct->variant()->id) }}" itemprop="name">{{$sProduct->name}} {{$sProduct->variant()->name}}</a></div>
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
    <!-- Rating@Mail.ru counter dynamic remarketing appendix --><script type="text/javascript">var _tmr = _tmr || []; _tmr.push({ type: 'itemView', productid: '{{ $variant->id }}', pagetype: 'product', list: '1', totalvalue: '{{ $product->variant()->price }}' });</script><!-- // Rating@Mail.ru counter dynamic remarketing appendix -->

  <script type="text/javascript">
    window.dataLayer.push ({
      "ecommerce": {
        "currencyCode": "RUB",
        "detail": {
          "products": [
            {
              "id": "{{ $variant->id }}",
              "name" : "{{ $product->name }}",
              "price": {{ $product->variant()->price }},
              "brand": "{{ $product->brand->name }}",
              "category": "{{ $product->categories[0]->name }}",
              "variant" : "{{ $variant->name }}"
            }
          ]
        }
      }
    });
  </script>

@endsection
