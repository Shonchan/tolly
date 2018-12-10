<div class="filter">
    <div class="filter-head">
        <span>Фильтр</span>
        <div class="close"></div>
    </div>
    @if (count($category->getChilds)>0)

    <div class="filter-block hidden-xs">
        <h3>Категории</h3>
        <ul class="filter-menu">
            @foreach ($category->getChilds as $cat)
                @if($cat->enabled)
                    <li class="filter-menu-field"><a href="{{ url($cat->slug) }}">{{ $cat->name }}</a>
                        @if (count($cat->getChilds)>0)
                            <ul class="filter-menu_child">
                                @foreach ($cat->getChilds as $c)

                                    @if($c->enabled)
                                    <li><a href="{{ url($c->slug) }}">{{ $c->name }}</a></li>
                                    @endif

                                @endforeach

                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
            {{--<li class="filter-menu-field">
                <a href="javascript:;">Наволочки</a>
                <ul class="filter-menu_child">
                    <li><a href="javascript:;">Название раздела 01</a></li>
                    <li><a href="javascript:;">Название раздела 02</a></li>
                    <li><a href="javascript:;">Название раздела 03</a></li>
                    <li><a href="javascript:;">Название раздела 04</a></li>
                    <li><a href="javascript:;">Название раздела 05</a></li>
                </ul>
            </li>
            <li class="filter-menu-field"><a href="javascript:;">Наматрасники</a></li>
            <li class="filter-menu-field current">
                <a href="javascript:;">Пододеяльники</a>
                <ul class="filter-menu_child" style="display: block">
                    <li><a href="javascript:;">Название раздела 01</a></li>
                    <li class="current">
                        <a href="javascript:;">Название раздела 02</a>
                        <ul style="display: block">
                            <li><a href="javascript:;">Пункт третьего уровня 01</a></li>
                            <li class="current"><a href="javascript:;">Пункт третьего уровня 02</a></li>
                            <li><a href="javascript:;">Пункт третьего уровня 03</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:;">Название раздела 03</a></li>
                </ul>
            </li>
            <li class="filter-menu-field"><a href="javascript:;">Простыни</a></li>
            <li class="filter-menu-field"><a href="javascript:;">Простынь на резинке</a></li>
            <li class="filter-menu-field"><a href="javascript:;">Простынь без резинки</a></li>--}}
        </ul>
    </div>

    @endif
    <div class="filter-block">
        <form id="filter" class="category-page">
            <input type="hidden" name="page" value="1">
            <input type="hidden" name="sort" value="popular">
            <input type="hidden" name="category" value="{{ $category_id }}">
            <h3>Фильтр</h3>
            <div class="filter-collapse">
                <div class="filter-collapse-name current">Цена, руб.</div>
                <div class="filter-collapse-hide current">
                    <div class="filter-price">
                        <div class="filter-price-slider">
                            <div class="price-slider"></div>
                        </div>
                        <div class="filter-price-item">
                            <label for="to">от</label>
                            <div class="control">
                                <input name="min_price" type="text" value="{{ $max_min_price->min_price }}" id="lower_bound">
                            </div>
                        </div>
                        <div class="filter-price-item">
                            <label for="to">до</label>
                            <div class="control">
                                <input name="max_price" type="text" value="{{ $max_min_price->max_price }}" id="upper_bound">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($features as $f)
                <div class="filter-collapse">
                    <div class="filter-collapse-name">{{ $f->name }}</div>
                    <div class="filter-collapse-hide">
                        <div class="nano">
                            <div class="nano-content">
                                <ul class="filter-menu">
                                    @foreach ($f->options as $o)
                                        <li class="filter-field"><div class="checkbox"><input @if (isset($mark) && isset($mark->features[$f->id]) && in_array($o->value, $mark->features[$f->id]))
                                            checked
                                        @endif
                                        type="checkbox" name="features[{{ $f->id }}][]" value="{{ $o->value }}"><span>{{ $o->value }}</span></div></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            {{--<div class="filter-collapse">
                <div class="filter-collapse-name">Размер</div>
                <div class="filter-collapse-hide">
                    <div class="nano">
                        <div class="nano-content">
                            <ul class="filter-menu">
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-collapse">
                <div class="filter-collapse-name">Материал</div>
                <div class="filter-collapse-hide">
                    <div class="nano">
                        <div class="nano-content">
                            <ul class="filter-menu">
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                                <li class="filter-field"><div class="checkbox"><input type="checkbox"><span>90х200 см</span></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-collapse">
                <div class="filter-collapse-name">Цвет</div>
                <div class="filter-collapse-hide">
                    <div class="nano">
                        <div class="nano-content">
                            <ul class="filter-menu">
                                <li class="filter-field">
                                    <div class="checkbox color"><input type="checkbox"><span>Синий</span><i style="background-color: blue"></i></div>
                                </li>
                                <li class="filter-field">
                                    <div class="checkbox color"><input type="checkbox"><span>Красный</span><i style="background-color: red"></i></div>
                                </li>
                                <li class="filter-field">
                                    <div class="checkbox color"><input type="checkbox"><span>Желтый</span><i style="background-color: yellow"></i></div>
                                </li>
                                <li class="filter-field">
                                    <div class="checkbox color"><input type="checkbox"><span>Зеленый</span><i style="background-color: green"></i></div>
                                </li>
                                <li class="filter-field">
                                    <div class="checkbox color"><input type="checkbox"><span>Черный</span><i style="background-color: black"></i></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="filter-complete"><button type="submit">Показать</button></div>
        </form>
    </div>
</div>