@extends('layouts.layout')

@section('title'){{ $category->name }}@endsection
@section('description')@endsection
@section('canonical'){{ url($category->slug) }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">
        @if($category->parent)
            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemref="breadcrumb-1">
                <a href="{{ url($category->parent->slug) }}" itemprop="url"><span itemprop="title">{{ $category->parent->name }}</span></a>
            </li>
        @endif

        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <a {{ url($category->slug) }} itemprop="url"><span itemprop="title">{{ $category->name }}</span></a>
        </li>
    </ul>
@endsection


@section('content')


    <div class="catalog row">
        <div class="catalog-head">
            <h1>{{ $category->name }}</h1>
        </div>
        @include('layouts.filter')
        <div class="product">

            <div class="product-head">
                <div class="filter-mobile">Фильтр</div>
                <div class="product-sorting">
                    <div class="product-sorting-name">Сортировать&nbsp;по</div>
                    <div class="product-sorting-control">
                        <select>
                            <option value="1">популярности</option>
                            <option value="2">цене</option>
                            <option value="3">скидке</option>
                            <option value="4">рейтингу</option>
                        </select>
                    </div>
                </div>
                <div class="product-view">
                    <ul>
                        <li class="block current">
                            <span class="view1"></span>
                            <span class="view2"></span>
                        </li>
                        <li class="inline current">
                            <span class="view1"></span>
                            <span class="view2"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                @foreach($category->products as $p)
                    <div class="post" itemscope itemtype="http://schema.org/Product">
                        <div class="pc">
                            <div class="pc-image"><a href="{{ url('/products', $p->slug) }}"><img src="{{ url('storage', $p->img()) }}" alt="" itemprop="image"></a></div>
                            <div class="pc-name"><a href="{{ url('/products', $p->slug) }}" itemprop="name">{{ $p->name }}</a></div>
                            <meta content="{{ $p->name }}" itemprop="description">
                            <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <div class="pc-price"><span itemprop="price">{{ $p->variant()->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
                    <div class="pagination">
                        <ul role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
                            <li class="current"><a itemprop="url" href="javascript:;"><span itemprop="name">1</span></a></li>
                            <li><a itemprop="url" href="javascript:;"><span itemprop="name">2</span></a></li>
                            <li><a itemprop="url" href="javascript:;"><span itemprop="name">3</span></a></li>
                            <li><a itemprop="url" href="javascript:;"><span itemprop="name">&raquo;</span></a></li>
                        </ul>
                    </div>
            </div>


        </div>
    </div>



@endsection