<div class="head">
    <div class="navbar-mobile"><span></span></div>
    <div class="navbar-mobile-basket"><a href="{{ url('cart') }}"><span>{{ $cart_total }}</span></a></div>
    <div class="navbar">
        <div class="navbar-logo"><a href="{{ url('/') }}"><img src="{{ url('/storage/logo.png') }}" /></a></div>
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
                                <li><a href="javascript:;">Москва</a></li>
                                <li><a href="javascript:;">Новосибирск</a></li>
                                <li><a href="javascript:;">Кемерово</a></li>
                                <li><a href="javascript:;">Чита</a></li>
                                <li><a href="javascript:;">Владивосток</a></li>
                                <li><a href="javascript:;">Санкт-Петербург</a></li>
                                <li><a href="javascript:;">Москва</a></li>
                                <li><a href="javascript:;">Новосибирск</a></li>
                                <li><a href="javascript:;">Кемерово</a></li>
                                <li><a href="javascript:;">Чита</a></li>
                                <li><a href="javascript:;">Владивосток</a></li>
                                <li><a href="javascript:;">Санкт-Петербург</a></li>
                                <li><a href="javascript:;">Москва</a></li>
                                <li><a href="javascript:;">Новосибирск</a></li>
                                <li><a href="javascript:;">Кемерово</a></li>
                                <li><a href="javascript:;">Чита</a></li>
                                <li><a href="javascript:;">Владивосток</a></li>
                                <li><a href="javascript:;">Санкт-Петербург</a></li>
                                <li><a href="javascript:;">Москва</a></li>
                                <li><a href="javascript:;">Новосибирск</a></li>
                                <li><a href="javascript:;">Кемерово</a></li>
                                <li><a href="javascript:;">Чита</a></li>
                                <li><a href="javascript:;">Владивосток</a></li>
                                <li><a href="javascript:;">Санкт-Петербург</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-item">
                <i class="fas fa-truck"></i>
                <div class="navbar-name">Доставка и оплата</div>
                <div class="navbar-button">от 1 дня, пунктов 113</div>
            </div>
            <div class="navbar-item navbar-item_tallme">
                <i class="fas fa-phone"></i>
                <div class="navbar-name">8 (800) 555-55-55</div>
                <div class="navbar-button" data-fancybox="" data-src="#tallme">перезвоните мне</div>
                <div class="navbar-tallme fancybox-content" id="tallme">
                    <div class="navbar-title">Перезвоните мне</div>
                    <form action="javascript:;">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Ваше имя">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Ваш телефон">
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
                <div class="navbar-button"><a href="javascript:;" data-fancybox="" data-src="#auth">авторизоваться</a></div>
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
                            <div class="msg"><a href="javascript:;">Забыли пароль?</a></div>
                        </div>
                        <div class="form-register"><a href="javascript:;">Создать учетную запись</a></div>
                    </form>
                </div>
            </div>
            <div class="navbar-item navbar-item_basket">
                <i class="fas fa-shopping-basket"></i>
                <div class="navbar-name">Корзина</div>
                <div class="navbar-button"><a href="{{ url('cart') }}">@if ($cart_total > 0){{ "в корзине: ".$cart_total }}@else{{ "нет товаров" }}@endif</a></div>
            </div>
        </div>
    </div>
    <nav>
        <div class="search"></div>
        <div class="search-form">
            <form action="javascript:;">
                <input type="text" placeholder="Найти...">
                <label for="searchbutton"><button type="submit" id="searchbutton"></button></label>
            </form>
            <div class="search-item">
                <ul class="search-list">
                    <li class="search-field"><a href="javascript:;">Постельное белье 1</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 2</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 3</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 4</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 5</a></li>
                    <li class="search-field"><a href="javascript:;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam nam ipsum, expedita ab, commodi pariatur dignissimos maiores a vero impedit, rerum dolores in odio aperiam sunt blanditiis illo, quibusdam reprehenderit?</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 4</a></li>
                    <li class="search-field"><a href="javascript:;">Постельное белье 5</a></li>
                </ul>
            </div>
        </div>
        <ul class="nav">
            @foreach($cats as $c)
                <li class="nav-item nav-item_sub">
                    <a href="{{ url($c->slug) }}">{{ $c->name }}</a>
                    <ul class="nav-child">
                        @foreach($c->getChilds as $ch)
                            @if($ch->enabled)
                            <li><a href="{{ url($ch->slug) }}">{{ $ch->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endforeach

        </ul>
    </nav>
</div>