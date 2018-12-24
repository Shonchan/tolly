@extends('layouts.layout')

@section('title'){{ "Заказ оформлен" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('order') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection


@section('content')
    <div class="ordering">
        <div class="ordering-content">
            <h1>@if($order->sid == 1){{ 'СПАСИБО ЗА ПОКУПКУ!' }}@else{{ 'Заказ №' }}{{$order->id}}@endif</h1>
            @if($order->sid == 1)
                <p>— Заказ успешно оформлен. В течение ближайших 10 минут мы свяжемся с вами по телефону <span class="text-bold">{{$order->phone}}</span> для подтверждения заказа.
                <p>— Мы будем своевременно информировать вас о состоянии заказа по СМС и эл. почте.</p>

                @if(($order->pmname == 'Картой'))
                    @if($order->paid == 1)
                        {!! Form::open(['action' => 'Views\OrderController@pay', 'method' => 'post']) !!}
                            <input type="hidden" name="slug" value="{{$order->slug}}" />
                            <p><button class="btn btn1" onclick="yaCounter48634619.reachGoal('order_pay'); return true;">ОПЛАТИТЬ ЗАКАЗ ОНЛАЙН</button></p>
                        {!! Form::close() !!}
                    @endif
                @endif
            @endif
            <div class="ordering-delivery">
                <h3><span>Информация о заказе</span></h3>
                <ul>
                    <li><span>Имя и фамилия:</span> <b>{{$order->name}} {{$order->lastname}}</b></li>
                    <li><span>Телефон:</span> <b>{{$order->phone}}</b></li>
                    @if($order->email)<li><span>Электронная почта:</span> <b>{{$order->email}}</b></li>@endif
                    @if($order->sname == 'Новый')<li><span>Номер заказа:</span> <b>{{$order->id}}</b></li>@endif
                    <li><span>Статус заказа:</span> <b>{{$order->sname}}</b></li>
                    <li><span>Способ оплаты:</span> <b>{{$order->pmname}}</b></li>
                    <li><span>Статус оплаты:</span> @if($order->paid == 2)<b class="text-bold text-green">Оплачен@else<b class="text-bold text-red">Не оплачен@endif</b></li>
                </ul>
            </div>
            <div class="ordering-product">
                <h3><span>Состав заказа</span></h3>
                <ul>
                    @foreach($purchases as $purchase)
                    <li><span>{{$purchase->product_name}} {{$purchase->vname}}</span> <b>{{$purchase->amount}} шт. <strong>{{$purchase->price * $purchase->amount}} руб.</strong></b></li>
                    @endforeach
                </ul>
                <h3><span>Доставка</span></h3>
                <ul>
                    @if(($order->dname == 'Курьером'))
                    <li><span>Способ доставки:</span> <b>{{$order->dname}}</b></li>
                    <li><span>Адрес доставки:</span> <b>{{$order->address}}</b></li>
                    <li><span>Дата доставки:</span> <b>{{$order->delivery_date}}</b></li>
                    <li><span>Время доставки:</span> <b>с {{\Carbon\Carbon::parse($order->delivery_time_from)->format('H')}} до {{\Carbon\Carbon::parse($order->delivery_time_to)->format('H')}}</b></li>
                    @else
                    <li><span>Способ доставки:</span> <b>{{$order->dname}}</b></li>
                    <li><span>Адрес самовывоза:</span> <b></b></li>
                    <li><span>Дата поступления в пункт самовывоза:</span> <b></b></li>
                    <li><span>Режим работы:</span> <b></b></li>
                    <li><span>Телефон самовывоза:</span> <b></b></li>
                    <li><span>Как найти пункт самовывоза:</span> <b></b></li>
                    @endif
                </ul>
            </div>
            <div class="ordering-total">
                <h3><span>Итого</span></h3>
                <ul>
                    <li><span>Товары ({{$countPurchases}})</span> <b>{{$totalSumm}} руб.</b></li>
                    <li><span>Скидка</span> <b>{{$discountValue}} руб.</b></li>
                    <li><span>Доставка:</span> <b>{{$order->delivery_price}} руб.</b></li>
                    <li><span>К оплате</span> <b>{{$totalSumm - $discountValue + $order->delivery_price}} руб.</b></li>
                </ul>
                @if($order->sname != 'Новый')
                @if(($order->pmname == 'Картой'))
                @if($order->paid == 1)
                {!! Form::open(['action' => 'Views\OrderController@pay', 'method' => 'post']) !!}
                <input type="hidden" name="slug" value="{{$order->slug}}" />
                <p><button class="btn btn1" onclick="yaCounter48634619.reachGoal('order_pay'); return true;">ОПЛАТИТЬ ЗАКАЗ ОНЛАЙН</button></p>
                {!! Form::close() !!}
                @endif
                @endif
                @endif
            </div>
            <div class="ordering-faq">
                <h3><span>Важно знать</span></h3>
                <ul>
                    <li>— Вам следует самостоятельно проверить полученный товар. Все претензии к качеству и комплектации товара Вы можете предъявить при получении</li>
                    @if(($order->pmname == 'Картой'))
                    <li>— При оплате заказа картой необходимо предоставить паспорт или другой документ с фотографией, удостоверяющий личность.</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">window.onload = function() { yaCounter48634619.reachGoal("order_status"); }</script>
@endsection
