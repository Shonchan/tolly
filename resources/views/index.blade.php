@extends('layouts.layout')

@section('title'){{'Интернет-магазин TOLLY.ru — товары для дома в Москве'}}@endsection
@section('description'){{'Мировые бренды товаров для дома и уюта по ценам производителя в наличии и под заказ в интернет-магазине TOLLY. Доставка от 1 дня!'}}@endsection
@section('canonical'){{ url('') }}@endsection
@section('ogtitle'){{'Интернет-магазин товаров для дома и уюта'}}@endsection
@section('ogdescription'){{'Мировые бренды товаров для дома по ценам производителя в наличии и под заказ в интернет-магазине TOLLY.'}}@endsection

@section('content')


        @include('layouts.banner')

        <div class="catalog row">
            <div class="main">
                <h2>Лучшие товары сегодня</h2>
                    <div class="row">
                        @foreach ($new_products as $p)
                        <div class="post post_md" itemscope itemtype="http://schema.org/Product">
                            <div class="pc">
                                <div class="pc-image">
                                    <a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}"  itemprop="url">
                                        <img src="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAADIAgMAAADkatA4AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAACVBMVEX3+Pn3+Pn///8nn3pkAAAAAXRSTlP+GuMHfQAAAAFiS0dEAmYLfGQAAAAHdElNRQfiCggRJyTjfr+UAAAAf0lEQVRo3u3WsQ2AMAxFwSznASjYf5UsQAESKB/nPMDpWXLhcb48AwgEAoFAIBAIBAKBQCAQCAQCgUAgEAgEAoFAIBAI/Bg84guB7cH7R9hm5S3Bii8EAteCFV8IBAKBF/PokeuxMrAzWPGFG4IVXxgOVnwhEAgEAoFAIPA/4AR2FcFRxoLp5gAAAABJRU5ErkJggg==" data-scr="{{ $p->img }}" alt="{{ $p->name }}" />
                                        <noscript><img src="{{ $p->img }}" alt="{{ $p->name }}" itemprop="image" /></noscript>
                                    </a>
                                </div>
                                <div class="pc-data">
                                  <div class="pc-name"><a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}"><span itemprop="name">{{ $p->name }} {{ $p->vname }}@if($p->seo)<span>, {{ $p->seo }}</span>@endif</span></a></div>
                                  <div class="pc-desc" itemprop="description">
                                    <ul>
                                      {{--<li><span>Производитель:</span><i>KAZANOV.A</i></li>--}}
                                      {{--<li><span>Коллекция:</span><i>Dela Rose (антрацит) цветы</i></li>--}}
                                      {{--<li><span>Размер:</span><i>1.5 спальный</i></li>--}}
                                    </ul>
                                  </div>
                                  <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <div class="pc-price"><span itemprop="price">{{ $p->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
                                    <div class="pc-payment"><a href="javascript:;"><span>Купить</span><i class="fas fa-shopping-basket"></i></a></div>
                                </div>
                              </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
            </div>
            <div class="main">
                <h2>Товары специально для вас</h2>
                    <div class="row">
                        @foreach ($popular_products as $p)
                        <div class="post post_md" itemscope itemtype="http://schema.org/Product">
                            <div class="pc">
                                <div class="pc-image">
                                    <a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}"  itemprop="url">
                                        <img src="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAADIAgMAAADkatA4AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAACVBMVEX3+Pn3+Pn///8nn3pkAAAAAXRSTlP+GuMHfQAAAAFiS0dEAmYLfGQAAAAHdElNRQfiCggRJyTjfr+UAAAAf0lEQVRo3u3WsQ2AMAxFwSznASjYf5UsQAESKB/nPMDpWXLhcb48AwgEAoFAIBAIBAKBQCAQCAQCgUAgEAgEAoFAIBAI/Bg84guB7cH7R9hm5S3Bii8EAteCFV8IBAKBF/PokeuxMrAzWPGFG4IVXxgOVnwhEAgEAoFAIPA/4AR2FcFRxoLp5gAAAABJRU5ErkJggg==" data-scr="{{ $p->img }}" alt="{{ $p->name }}" />
                                        <noscript><img src="{{ $p->img }}" alt="{{ $p->name }}" itemprop="image" /></noscript>
                                    </a>
                                </div>
                                <div class="pc-data">
                                  <div class="pc-name"><a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}"><span itemprop="name">{{ $p->name }} {{ $p->vname }}@if($p->seo)<span>, {{ $p->seo }}</span>@endif</span></a></div>
                                  <div class="pc-desc" itemprop="description">
                                    <ul>
                                      {{--<li><span>Производитель:</span><i>KAZANOV.A</i></li>--}}
                                      {{--<li><span>Коллекция:</span><i>Dela Rose (антрацит) цветы</i></li>--}}
                                      {{--<li><span>Размер:</span><i>1.5 спальный</i></li>--}}
                                    </ul>
                                  </div>
                                  <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <div class="pc-price"><span itemprop="price">{{ $p->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
                                    <div class="pc-payment"><a href="javascript:;"><span>Купить</span><i class="fas fa-shopping-basket"></i></a></div>
                                </div>
                              </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
            </div>
        </div>

        {!! Form::open(['id' => 'add_to_cart']) !!}

        <div class="blank" id="order_content_cat" style="display: none">
            <input type="hidden" name="variant_id" value="">
            <div class="blank-title">Товар добавлен в корзину</div>
            <div class="basket">
                <div class="basket-item">
                    <div class="basket-product">
                        <div class="basket-image">
                            <a href=""></a>
                        </div>
                        <div class="basket-wrap">
                            <div class="basket-title"><a href=""></a></div>
                        </div>
                    </div>
                    <div class="basket-spinner">
                        {!! Form::hidden('variants[id][]', 0) !!}
                        {!! Form::hidden('variants[price][]', 0) !!}
                        {!! Form::hidden('variants[amount][]', 0, ['data-max'=>20]) !!}
                        <div class="ui-spin">
                            <span class="minus" onclick=""></span>
                            <div class="count">0</div>
                            <span class="plus" onclick=""></span>
                        </div>
                        <div class="cost"></div>
                    </div>
                    <div class="basket-total"><span>{{ "0 руб." }}</span></div>
                    <div class="basket-remove"></div>
                </div>
                <div class="blank-more">
                    <div class="blank-submit">
                        <a href="javascript:;"  onclick="$.fancybox.close();" class="btn btn2">Продолжить покупки</a>
                        <a href="{{ url('cart') }}" class="btn btn3">Перейти в корзину</a>
                    </div>
                </div>
            </div>

        </div>
        {!! Form::close() !!}





@endsection
