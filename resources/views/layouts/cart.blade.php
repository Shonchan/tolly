<div id="cart" class="col-md-2 pull-right">
    <span style="display: block;">Корзина</span>
    <a href="{{ url('cart') }}">@if ($cart_total > 0){{ "в корзине: ".$cart_total }}@else{{ "нет товаров" }}@endif</a>
</div>