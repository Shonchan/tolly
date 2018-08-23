@foreach($products as $p)
    <div data-page="{{ $page }}" class="post" itemscope itemtype="http://schema.org/Product">
        <div class="pc">
            <div class="pc-image"><a href="{{ url('/products', $p->slug) }}"><img src="{{ $p->img }}" alt="" itemprop="image"></a></div>
            <div class="pc-name"><a href="{{ url('/products', $p->slug) }}" itemprop="name">{{ $p->name }}</a></div>
            <meta content="{{ $p->name }}" itemprop="description">
            <div class="pc-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <div class="pc-price"><span itemprop="price">{{ $p->price }}</span> руб. <meta content="RUB" itemprop="priceCurrency"></div>
            </div>
        </div>
    </div>
@endforeach

{{--{{ $products->links() }}--}}