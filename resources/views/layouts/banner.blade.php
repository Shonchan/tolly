<div class="carousel">
    <div class="swiper-container js-carousel">
        @foreach ($banners as $banner)
        <a class="swiper-slide" href="{{$banner->link}}">
            <img src="{{ url('/storage', $banner->image) }}" alt="" />
        </a>
        @endforeach
    </div>
    <div class="carousel-pagination"></div>
</div>
