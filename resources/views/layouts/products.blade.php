@foreach($products as $p)
<div data-page="{{ $page }}" class="post" itemscope itemtype="http://schema.org/Product">
    <div class="pc">
        <div class="pc-image">
            <a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}" itemprop="url" target="_blank">
                <img src="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAADIAgMAAADkatA4AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAACVBMVEX3+Pn3+Pn///8nn3pkAAAAAXRSTlP+GuMHfQAAAAFiS0dEAmYLfGQAAAAHdElNRQfiCggRJyTjfr+UAAAAf0lEQVRo3u3WsQ2AMAxFwSznASjYf5UsQAESKB/nPMDpWXLhcb48AwgEAoFAIBAIBAKBQCAQCAQCgUAgEAgEAoFAIBAI/Bg84guB7cH7R9hm5S3Bii8EAteCFV8IBAKBF/PokeuxMrAzWPGFG4IVXxgOVnwhEAgEAoFAIPA/4AR2FcFRxoLp5gAAAABJRU5ErkJggg==" data-scr="{{ $p->img }}" alt="{{ $p->name }}" />
                <noscript><img src="{{ $p->img }}" alt="{{ $p->name }}" itemprop="image" /></noscript>
            </a>
        </div>
        <div class="pc-data">
          <div class="pc-name"><a data-id="{{ $p->id }}" rel="nofollow" href="{{ url('/product', $p->vid) }}" target="_blank"><span itemprop="name">{{ $p->name }}@if($p->seo) <span>{{ $p->vname }}, {{ $p->seo }}</span>@else <span>{{ $p->vname }}</span>@endif  </span></a></div>
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
