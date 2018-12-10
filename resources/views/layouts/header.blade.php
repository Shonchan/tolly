<div class="head">
    <div class="navbar-mobile"><span></span></div>
    <div class="navbar-mobile-basket"><a rel="nofollow" href="{{ url('cart') }}"><span>{{ $cart_total }}</span></a></div>
    <div class="navbar">
        <div class="navbar-logo"><a href="{{ url('/') }}"><img src="{{ url('/storage/logo.png') }}" alt="TOLLY" /></a></div>
        <div class="navbar-menu">
            <div class="navbar-item navbar-item_location">
                <i class="fas fa-map-marker-alt"></i>
                <div class="navbar-name">Ваш город</div>
                <div class="navbar-button js-location">Москва</div>
                <div class="navbar-location">
                    <form action="javascript:;">
                        <input type="text" placeholder="Укажите город">
                    </form>
                    <div class="nano">
                        <div class="nano-content">
                            <ul>
                                <li><a rel="nofollow" href="javascript:;">Москва</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-item">
                <i class="fas fa-truck"></i>
                <div class="navbar-name">Условия доставки</div>
                <div class="navbar-button"><a rel="nofollow" href="{{ url('dostavka') }}">31 пункт выдачи заказов</a></div>
            </div>
            <div class="navbar-item navbar-item_tallme @if(date('H') <= 8 || date('H') >= 21){{'disable'}}@endif">
                <i class="fas fa-phone"></i>
                <div class="navbar-name"><a rel="nofollow" href="tel:+74951209083">8 (495) 120-90-83</a></div>
                <div class="navbar-button"><a rel="nofollow" class="tallme" href="#tallme">перезвоните мне</a></div>
                <div class="navbar-tallme fancybox-content" id="tallme">
                    <button data-fancybox-close="" class="fancybox-close-small"><svg viewBox="0 0 32 32"><path d="M10,10 L22,22 M22,10 L10,22"></path></svg></button>
                    <div class="navbar-title">Перезвоните мне</div>
                    <form id="callbackform">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Ваше имя">
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" data-mask="+7 (999) 999-99-99" class="form-control" placeholder="Ваш телефон">
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn1 block">Заказать звонок</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="navbar-item">
                <i class="fas fa-user-alt"></i>
                <div class="navbar-name">Личный кабинет</div>
                <div class="navbar-button"><a rel="nofollow" href="javascript:;" data-fancybox="" data-src="#auth">авторизоваться</a></div>
                <div class="navbar-auth fancybox-content" id="auth">
                    <div class="navbar-title">Войти в кабинет</div>
                    <form action="javascript:;">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Ваш E-mail">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Ваш пароль">
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn1 block">Войти</button>
                            <div class="msg"><a rel="nofollow" href="javascript:;">Забыли пароль?</a></div>
                        </div>
                        <div class="form-register"><a rel="nofollow" href="javascript:;">Создать учетную запись</a></div>
                    </form>
                </div>
            </div>
            <div class="navbar-item navbar-item_basket">
                <i class="fas fa-shopping-basket"></i>
                <div class="navbar-name">Корзина</div>
                <div class="navbar-button"><a rel="nofollow" href="{{ url('cart') }}">@if ($cart_total > 0){{ "в корзине: ".$cart_total }}@else{{ "нет товаров" }}@endif</a></div>
            </div>
        </div>
    </div>
    <nav>
        <div class="search"></div>
        <div class="search-form">
            <form>
                <input type="text" placeholder="Найти...">
                <label for="searchbutton"><button type="submit" id="searchbutton"></button></label>
            </form>
            <div class="search-item">
                <ul class="search-list">
                    <li class="search-field"><a rel="nofollow" href="javascript:;"></a></li>
                </ul>
            </div>
        </div>
        <ul class="nav">
            @foreach($cats as $c)
                <li class="nav-item @if (count($c->getChilds)>0){{'nav-item_sub'}}@endif">
                    <a href="{{ url($c->slug) }}">{{ $c->name }}</a>
                    @if (count($c->getChilds)>0)
                        <ul class="nav-child">
                            @foreach($c->getChilds as $ch)
                                @if($ch->enabled)
                                    <li><a href="{{ url($ch->slug) }}">{{ $ch->name }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach

        </ul>
    </nav>
</div>