@extends('layouts.layout')

@section('title'){{'Tolly'}}@endsection
@section('description')@endsection
@section('canonical'){{ url('') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('content')


        @include('layouts.banner')

        <div class="catalog row">
            <div class="main">
                <h2>Лучшие цены в июне!</h2>
                    <div class="row">
                        @foreach ($new_products as $p)
                            <div class="post post_md" itemscope itemtype="http://schema.org/Product">
                                <div class="pc">
                                    <div class="pc-image"><a href="{{ url('/products', $p->slug) }}"><img src="{{  $p->img }}" alt="" itemprop="image"></a></div>
                                    <div class="pc-name"><a href="{{ url('/products', $p->slug) }}" itemprop="name">{{ $p->name }}</a></div>
                                    <meta content="{{ $p->name }}" itemprop="description">
                                    <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <div class="pc-price"><span itemprop="price">{{ $p->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
            </div>
            <div class="main">
                <h2>Товары специально для вас</h1>
                    <div class="row">
                        @foreach ($popular_products as $p)
                            <div class="post post_md" itemscope itemtype="http://schema.org/Product">
                                <div class="pc">
                                    <div class="pc-image"><a href="{{ url('/products', $p->slug) }}"><img src="{{  $p->img }}" alt="" itemprop="image"></a></div>
                                    <div class="pc-name"><a href="{{ url('/products', $p->slug) }}" itemprop="name">{{ $p->name }}</a></div>
                                    <meta content="{{ $p->name }}" itemprop="description">
                                    <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <div class="pc-price"><span itemprop="price">{{ $p->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
            </div>
        </div>





@endsection