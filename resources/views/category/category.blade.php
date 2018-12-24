@extends('layouts.layout')

@section('title')
@if(isset($mark))
@if($mark->meta_title){{ $mark->meta_title }}@else{{ 'Купить' }} {{ mb_lcfirst($mark->name) }}{{' в Москве | TOLLY.ru'}}@endif
@else
@if($category->meta_title){{ $category->meta_title }}@else{{ 'Купить' }} {{ mb_lcfirst($category->name) }}{{' в Москве в интернет-магазине TOLLY.ru'}}@endif
@endif
@endsection

@section('description')
@if(isset($mark))
@if($mark->meta_description){{ $mark->meta_description }}@else{{ $mark->name }}{{' от '}}{{ $max_min_price->min_price }}{{' рублей в интернет-магазине Москвы. Выбрать и купить из'}} {{ $products->total() }} {{ plural($products->total(), ["товар","товаров", "товаров"]) }} {{'с доставкой от 1 дня!'}}@endif
@else
@if($category->meta_description){{ $category->meta_description }}@else{{ $category->name }}{{' по цене от '}}{{ $max_min_price->min_price }}{{' руб. Большой каталог из 2000 моделей. Покупай, доставим по Москве от 1 дня!'}}@endif
@endif
@endsection

@section('keywords')
@if(isset($mark))
@if($mark->meta_keywords){{ mb_lcfirst($mark->meta_keywords) }}@else{{ mb_lcfirst($mark->name) }}, купить {{ mb_lcfirst($mark->name) }}, {{ mb_lcfirst($mark->name) }} в москве, {{ mb_lcfirst($mark->name) }} недорого@endif
@else
@if($category->meta_keywords){{ mb_lcfirst($category->meta_keywords) }}@else{{ mb_lcfirst($category->name) }}, {{ mb_lcfirst($category->name) }} купить, {{ mb_lcfirst($category->name) }} в москве@endif
@endif
@endsection

@section('canonical')@if(isset($mark)){{ url($mark->slug) }}@else{{ url($category->slug) }}@endif
@endsection

@section('ogtitle')@if(isset($mark)){{ $mark->name }}{{' в интернет-магазине TOLLY.ru'}}@else{{ $category->name }}{{' в интернет-магазине TOLLY.ru'}}@endif
@endsection

@section('ogdescription')
@if(isset($mark))
@if($mark->meta_description){{ $mark->meta_description }}@else{{ $mark->name }}{{' по цене от '}}{{ $max_min_price->min_price }}{{' руб. Большой каталог из 2000 моделей. Покупай, доставим по Москве от 1 дня!'}}@endif
@else
@if($category->meta_description){{ $category->meta_description }}@else{{ $category->name }}{{' по цене от '}}{{ $max_min_price->min_price }}{{' руб. Большой каталог из 2000 моделей. Покупай, доставим по Москве от 1 дня!'}}@endif
@endif
@endsection

@section('ogimage') @if(isset($products[0]) ){{ $products[0]->img }}@endif @endsection

@section('pager')

<ul class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <li>
    <a href="/" itemprop="url"><span itemprop="title">Главная</span></a>
  </li>
  @if($category->parent)
  <li>
    <a href="{{ url($category->parent->slug) }}" itemprop="url"><span itemprop="title">{{ $category->parent->name }}</span></a>
  </li>
  @endif
  @if(isset($mark) && $mark->parent)
  <li>
    <a href="{{ url($category->slug) }}" itemprop="url"><span itemprop="title">{{ $category->name }}</span></a>
  </li>
  @endif
  @if(isset($mark) && $mark->parent->parent_id != 0)
  <li>
    <a href="{{ url($mark->parent->slug) }}" itemprop="url"><span itemprop="title">{{ $mark->parent->name }}</span></a>
  </li>
  @endif
</ul>
@endsection


@section('content')
<div class="catalog row">
  <div class="catalog-head">
    <h1>@if(isset($mark)){{ $mark->name }}@else{{ $category->name }}@endif</h1>
  </div>
  @include('layouts.filter')
  <div class="product">
    @if(isset($marks) && count($marks)>0)
    <ul class="product-tag">
      @foreach ($marks as $m)
          <li>
              @if(isset($mark) && $m->id == $mark->id)
                  <span class="active">@if($m->seo[0]){{ $m->seo[0] }}@else{{ $m->name }}@endif</span>
              @else
                  <a href="{{ url($m->slug) }}">@if($m->seo[0]){{ $m->seo[0] }}@else{{ $m->name }}@endif</a>
              @endif
          </li>

      @endforeach
          <li class="open"><a href="javascript:;">еще &gt;</a></li>
    </ul>
    @endif
    <div class="product-head">
      <div class="filter-mobile">Фильтр</div>
      <div class="product-sorting">
        <div class="product-sorting-name">Сортировать&nbsp;по</div>
        <div class="product-sorting-control">
          <select id="sort" class="category-sort">
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
          <div class="pc-name"><a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}" target="_blank"><span itemprop="name">{{ $p->name }}@if($p->seo) <span>{{ $p->vname }}, {{ $p->seo }}</span>@else <span>{{ $p->vname }}</span>@endif</span></a></div>
          <div class="pc-desc" itemprop="description">
            <ul>
              @foreach($p->options as $option)
              <li><span>{{$option->name}}</span><i>{{$option->value}}</i></li>
              @endforeach
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
      {{-- $products->links() --}}
      {{--<div class="pagination">--}}
        {{--<ul role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">--}}
          {{--<li class="current"><a itemprop="url" href="javascript:;"><span itemprop="name">1</span></a></li>--}}
          {{--<li><a itemprop="url" href="javascript:;"><span itemprop="name">2</span></a></li>--}}
          {{--<li><a itemprop="url" href="javascript:;"><span itemprop="name">3</span></a></li>--}}
          {{--<li><a itemprop="url" href="javascript:;"><span itemprop="name">&raquo;</span></a></li>--}}
        {{--</ul>--}}
      {{--</div>--}}
    </div>
    @if(isset($mark))
    <h2>Как выбрать и купить @if(isset($mark->seo[1])){{ mb_lcfirst($mark->seo[1]) }}@else{{ mb_lcfirst($mark->name) }}@endif в Москве</h2>
    <p>Выбрать и купить @if(isset($mark->seo[1])){{ mb_lcfirst($mark->seo[1]) }}@else{{ mb_lcfirst($mark->name) }}@endif недорого в интернет магазине TOLLY вам помогут подробные характеристики, цены, фото и отзывы покупателей. В каталоге вы найдете @if(isset($mark->seo[2])){{ mb_lcfirst($mark->seo[2]) }}@else{{ mb_lcfirst($mark->name) }}@endif по цене от {{ $max_min_price->min_price }} рублей. Заказ можно оформить онлайн или по телефону 8 (495) 120-90-83. В наличии {{ $products->total() }} {{ plural($products->total(), ["товар","товаров", "товара"]) }} с доставкой по Москве от 1 дня! Возможен самовывоз из 31 пунктов.</p>
    @endif
    @if(!isset($mark))
    <h2>Как выбрать и купить @if(isset($category->seo[1])){{ mb_lcfirst($category->seo[1]) }}@else{{ mb_lcfirst($category->name) }}@endif в Москве</h2>
    <p>В интернет-магазине TOLLY.ru можно подобрать и купить @if(isset($category->seo[1])){{ mb_lcfirst($category->seo[1]) }}@else{{ mb_lcfirst($category->name) }}@endif благодаря подробным характеристикам, выгодной цене, детальным фото и честным отзывам покупателей. В каталоге вы найдете @if(isset($category->seo[2])){{ mb_lcfirst($category->seo[2]) }}@else{{ mb_lcfirst($category->name) }}@endif по цене от {{ $max_min_price->min_price }} рублей. Заказ можно оформить онлайн или по телефону 8 (495) 120-90-83. В наличии {{ $products->total() }} {{ plural($products->total(), ["товар","товаров", "товара"]) }} с доставкой по Москве от 1 дня!</p>
    @endif
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
</div>
@endsection
