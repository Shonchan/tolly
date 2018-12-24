<div class="basket-sidebar-content">
    <div class="basket-sidebar-top">@if($total < $freeDelivery)Ещё немного! До&nbsp;бесплатной доставки осталось &nbsp;{{$freeDelivery-$total}}&nbsp;руб.@elseУра! Вы получили <span class="text-green">бесплатную доставку</span> на ваш заказ!@endif</div>
    <div class="basket-sidebar-list">
        <ul>
            <li>Регион: <a href="javascript:;" class="text-red is-city">Москва</a></li>
            <li>
                Доставка курьером
                <i>{{ $dateDelivery }} и позже — @if($total < $freeDelivery)300 руб@else<span class="text-green">бесплатно</span>@endif</i>
            </li>
            <li>
                Самовывоз
                <i>{{ $dateDelivery }} и позже — @if($total < $freeDelivery)150 руб@else<span class="text-green">бесплатно</span>@endif</i>
            </li>
            <li>
                Оплата
                <i>Картой онлайн, наличными при получении</i>
            </li>
        </ul>
    </div>
    <!-- is-success добавить данный класс для "coupone" в случае успеха -->
    <!-- is-focus добавить данный класс для "coupone" в случае начала ввода -->
    <!-- is-error добавить данный класс для "coupone" в случае ошибки -->
    <div class="coupone is-faq">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Промокод на скидку" value="{{$couponeCode}}">
            <button type="button" class="submit"><i class="fas fa-arrow-circle-right"></i></button>
            {{--<button type="button" class="cancel"><i class="fas fa-exclamation-circle"></i></button>--}}
            {{--<b><i class="fas fa-check-circle"></i></b>--}}
            <button class="cancel"><i class="fas fa-check-circle"></i></button>
            <b><i class="fas fa-exclamation-circle"></i></b>
            <span class="success text-green">Теперь ваш заказ стоит на&nbsp;500&nbsp;руб. дешевле!</span>
            <span class="danger text-red">Неправильный промокод</span>
            <span class="faq"><i class="fas fa-question-circle tooltip" title="Укажите ваш промо-код, для получения скидки на товары"></i></span>
        </div>
    </div>
    <div class="basket-sidebar-complete">
        <ul>
            <li>Товары <amount>({{$total_amount}})</amount> <span>{{$total}} руб.</span></li>
            {{--<li>Доставка @if($total < $freeDelivery)<span>300 руб</span>@else<span class="text-green">бесплатно</span>@endif</li>--}}
            {{--<li>Скидка на товары <span class="text-bold text-red">-500 руб.</span></li>--}}
            @if($discountValue)<li>Скидка <br>по промокоду <span class="text-bold text-red">-{{$discountValue}} руб.</span></li>@endif
            <li class="text-bold">Итого <span>{{ $total - $discountValue." руб." }}</span></li>
        </ul>
    </div>
</div>