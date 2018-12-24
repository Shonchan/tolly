
    <div class="basket-sidebar-complete">
        <ul>
            <li>Товары <amount>({{$total_amount}})</amount> <span>{{$total}} руб.</span></li>
            {{--<li>Доставка <span class="text-green">бесплатно</span></li>--}}
            {{--<li>Скидка на товары <span class="text-bold text-red">-500 руб.</span></li>--}}
            @if($discountValue)<li>Скидка <br>по промокоду <span class="text-bold text-red">-{{$discountValue}} руб.</span></li>@endif
            <li class="text-bold">Итого <span>{{ $total - $discountValue." руб." }}</span></li>
        </ul>
    </div>
