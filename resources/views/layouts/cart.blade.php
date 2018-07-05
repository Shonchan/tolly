<div id="cart" class="col-md-2 pull-right">
    <span style="display: block;">Корзина</span>
    <a href="{{ url('cart') }}">@if ($cart_total > 0){{ "В корзине: ".$cart_total }}@else{{ "Нет товаров" }}@endif</a>
</div>