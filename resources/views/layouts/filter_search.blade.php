<div class="filter">
    <div class="filter-head">
        <span>Фильтр</span>
        <div class="close"></div>
    </div>
    <div class="filter-block hidden-xs">
        <h3>Категории</h3>
        <ul class="filter-menu" id="category-search">
            @foreach ($categories as $cat)
                <li class="filter-menu-block">
                    <a href="javascript:;" cid="{{ $cat->id }}" class="category"><span></span>{{ $cat->name }} ({{ $cat->countPositions }})</a>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="filter-block">
        <form id="filter" class="search-page">
            <input type="hidden" name="page" value="1">
            <input type="hidden" name="sort" value="popular">
            <input type="hidden" name="query" value="{{$search->name}}">
            <input type="hidden" name="category_id" value="0">
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
                                <input name="min_price" type="text" value="{{ $search->min_price }}" id="lower_bound">
                            </div>
                        </div>
                        <div class="filter-price-item">
                            <label for="to">до</label>
                            <div class="control">
                                <input name="max_price" type="text" value="{{ $search->max_price }}" id="upper_bound">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-complete"><button type="submit">Показать</button></div>
        </form>
    </div>
</div>