@extends('layouts.layout')

@section('title')По запросу &laquo;{{$search->name}}&raquo; найдено {{$search->count}} позиций@endsection
@section('description')Большой каталог товаров. Доставка от 1 дня. Скидки.@endsection
@section('canonical'){{ url($search->url) }}@endsection
@section('ogtitle')По запросу &laquo;{{$search->name}}&raquo; найдено {{$search->count}} позиций@endsection
@section('ogdescription')Большой каталог товаров. Доставка от 1 дня. Скидки.@endsection
@section('ogimage') @if(isset($products[0]) ){{ $products[0]->img }}@endif
@endsection

@section('pager')

<ul class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <li>
    <a href="/" itemprop="url"><span itemprop="title">Главная</span></a>
  </li>
</ul>
@endsection

@section('content')
<div class="catalog row">
  <div class="catalog-head">
    <h1>По запросу &laquo;{{$search->name}}&raquo; найдено {{$search->count}} позиций</h1>
  </div>
  @if($search->count > 0)
        @include('layouts.filter_search')
        <div class="product">
          <div class="product-head">
            <div class="filter-mobile">Фильтр</div>
            <div class="product-sorting">
              <div class="product-sorting-name">Сортировать&nbsp;по</div>
              <div class="product-sorting-control">
                <select id="sort" class="search-sort">
                  <option value="popular">популярности</option>
                  <option value="price_asc">возрастанию цены</option>
                  <option value="price_desc">убыванию цены</option>
                  <option value="discount">скидке</option>
                  <option value="rating">рейтингу</option>
                </select>
              </div>
            </div>
            <div class="product-view">
              <ul>
                <li class="block current">
                  <span class="view1"></span>
                  <span class="view2"></span>
                </li>
                <li class="inline">
                  <span class="view1"></span>
                  <span class="view2"></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
          @foreach($products as $p)
          <div data-page="{{ $page }}" class="post" itemscope itemtype="http://schema.org/Product">
            <div class="pc">
              <div class="pc-image">
                <a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}"  itemprop="url" target="_blank">
                  <img src="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAADIAgMAAADkatA4AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAACVBMVEX3+Pn3+Pn///8nn3pkAAAAAXRSTlP+GuMHfQAAAAFiS0dEAmYLfGQAAAAHdElNRQfiCggRJyTjfr+UAAAAf0lEQVRo3u3WsQ2AMAxFwSznASjYf5UsQAESKB/nPMDpWXLhcb48AwgEAoFAIBAIBAKBQCAQCAQCgUAgEAgEAoFAIBAI/Bg84guB7cH7R9hm5S3Bii8EAteCFV8IBAKBF/PokeuxMrAzWPGFG4IVXxgOVnwhEAgEAoFAIPA/4AR2FcFRxoLp5gAAAABJRU5ErkJggg==" data-scr="{{ $p->img }}" alt="{{ $p->name }}" />
                  <noscript><img src="{{ $p->img }}" alt="{{ $p->name }}" itemprop="image" /></noscript>
                </a>
              </div>
              <div class="pc-data">
                <div class="pc-name"><a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}" target="_blank"><span itemprop="name">{{ $p->name }} {{ $p->vname }}@if($p->seo)<span>, {{ $p->seo }}</span>@endif</span></a></div>
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
    @endif

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
                    <input type="text" class="spinner_one" name="variants[amount][]" value="0">
                    <div class="basket-price">
                        {!! Form::hidden('variants[id][]', 0) !!}
                        {!! Form::hidden('price', 0) !!}
                        <span class="price"><u>Цена: </u>{{ "0 руб/шт" }}</span>
                    </div>
                </div>
                <div class="basket-total"><u>Итого: </u><span>{{ "0 руб." }}</span></div>
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
</div>
@endsection
