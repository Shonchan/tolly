@foreach($products as $p)
<div data-page="{{ $page }}" class="post" itemscope itemtype="http://schema.org/Product">
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
