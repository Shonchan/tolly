@extends('layouts.layout')

@section('title'){{ "Оформление заказа" }}@endsection
@section('description')@endsection
@section('canonical'){{ url('order') }}/@endsection
@section('ogtitle'){{'Tolly'}}@endsection
@section('ogdescription')@endsection

@section('pager')
    <ul class="breadcrumbs">


        <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb" id="breadcrumb-1" itemref="breadcrumb-2">
            <span itemprop="title">Оформление заказа</span>
        </li>
    </ul>
@endsection

@section('content')
    {!! Form::open(['action' => 'Views\OrderController@store', 'method' => 'post', 'id' => 'store-form']) !!}
    <input type="hidden" name="date_delivery" id="dateDelivery" value="{{ $dateDelivery }}">
    <input type="hidden" name="total_sum" value="{{ $total_sum }}">
    <input type="hidden" name="discount_value" value="{{ $discountValue }}">
    <input type="hidden" name="delivery_price" value="@if($total_sum < $freeDelivery){{ 150 }}@else{{ 0 }}@endif">
    @foreach($variants as $p)
        <input type="hidden" name="purchases[id][]" value="{{ $p->id }}">
        <input type="hidden" name="purchases[amount][]" value="{{ $p->amount }}">
        <input type="hidden" name="purchases[price][]" value="{{ $p->price }}">

    @endforeach

    <div class="order">
        <h1>Оформление заказа</h1>
        <div class="order-row">
            <div class="order-content">
                <div class="order-delivery">
                    <h2>1. Способ получения заказа в г.&nbsp;<a href="javascript:;" class="text-red">Москва</a></h2>
                    <div class="tabs">
                        <input type="radio" id="delivery1" value="2" name="delivery">
                        <div class="label">
                            <label for="delivery1" class="label-tab first">
                                <b>Доставка курьером</b>
                                <span>{{$dateDelivery}} и позже, @if($total_sum < $freeDelivery){{"от 300 руб."}}@else<price class="text-green">бесплатно</price>@endif</span>
                            </label></div>
                        <input type="radio" id="delivery2" data-price="@if($total_sum < $freeDelivery){{ 150 }}@else{{ 0 }}@endif" value="3" name="delivery">
                        <div class="label">
                            <label for="delivery2" class="label-tab last">
                                <b>Самовывоз</b>
                                <span>{{$dateDelivery}} и позже, @if($total_sum < $freeDelivery){{"от 150 руб."}}@else<price class="text-green">бесплатно</price>@endif</span>
                            </label>
                        </div>

                        <div id="field1">
                            <ul class="delivery-area">
                                <li>
                                    <div>
                                        <input type="radio" name="area" value="@if($total_sum < $freeDelivery){{ 300 }}@else{{ 0 }}@endif" checked>
                                        <span class="area-item">Доставка по Москве внутри МКАД, <span class="text-bold">@if($total_sum < $freeDelivery){{ "300 руб" }}@else<span class="text-green">бесплатно</span>@endif</span></span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <input type="radio" name="area" value="@if($total_sum < $freeDelivery){{ 500 }}@else{{ 200 }}@endif">
                                        <span class="area-item">Доставка за МКАД до 20км, <span class="text-bold">@if($total_sum < $freeDelivery){{ "500 руб" }}@else{{ "200 руб"}}@endif</span></span>
                                    </div>
                                </li>
                            </ul>
                            <h3>Укажите адрес доставки</h3>
                            <div class="form">
                                <div class="row">
                                    <div class="form-group form-line">
                                        <label for="">Улица <span>*</span></label>
                                        <input type="text" class="form-control" name="address[street]">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Номер дома <span>*</span></label>
                                        <input type="text" class="form-control" name="address[house]">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Корп./Стр.</label>
                                        <input type="text" class="form-control" name="address[building]">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Подъезд</label>
                                        <input type="text" class="form-control" name="address[entrance]">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Этаж</label>
                                        <input type="text" class="form-control" name="address[floor]">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Квартира/Офис</label>
                                        <input type="text" class="form-control" name="address[apart]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="field2">
                            <div class="options">

                                <span class="field2h">Пункты выдачи:</span>
                                <input class="field2h" type="radio" name="optionview" id="option1">
                                <label for="option1" class="label-view field2h">Списком</label>
                                <input class="field2h" type="radio" name="optionview" id="option2" checked>
                                <label for="option2" class="label-view field2h">На карте</label>

                                <div id="option1">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Поиск по адресу">
                                    </div>
                                    <div class="form-content">

                                    </div>
                                </div>
                                <div id="option2">
                                    <div class="form-group">
                                        {{--<input type="text" class="form-control" placeholder="Поиск по адресу">--}}
                                    </div>
                                    <div class="iframe" id="map">
                                    </div>
                                </div>
                            </div>
                            <div class="complete" style="display: none">
                                <div class="point">
                                    <div class="point-left">
                                        <div class="point-line">
                                            <b></b>
                                            <span></span>
                                        </div>
                                        <div class="point-time">
                                            Поступление заказа <span></span><br>В момент доставки заказа в пункт выдачи, вам поступит смс.
                                        </div>
                                    </div>
                                    <div class="point-right">
                                        <div class="point-cash">
                                            Стоимость <span class="text-bold"></span>
                                        </div>
                                        <div class="point-payment">
                                            Способ оплаты: Наличными
                                        </div>
                                    </div>
                                </div>
                                <div class="title">Как добраться</div>
                                <p class="how-to-go">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae incidunt hic magni laborum, sed ducimus delectus recusandae dolore sit et laboriosam est, quae rem sunt tenetur voluptas quidem. Animi, vitae!</p>
                                <div class="title">Время работы</div>
                                <p class="time">пн-вс: 11:00 – 21:00</p>
                                <div class="remove">
                                    <button type="button" class="btn1">Выбрать другой</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-user">
                    <h2>2. Получатель заказа</h2>
                    <div class="order-user-row">
                        <div class="form-group">
                            <label for="">Имя <span>*</span></label>
                            <input name="name" type="text" class="form-control" required>
                            <div class="error">Имя должно состоять минимум из двух букв</div>
                        </div>
                        <div class="form-group">
                            <label for="">Фамилия <span>*</span></label>
                            <input name="lastname" type="text" class="form-control" required>
                            <div class="error">Фамилия должна состоять минимум из двух букв</div>
                        </div>
                        <div class="form-group">
                            <label for="">Телефон <span>*</span></label>
                            <input name="phone" type="text" placeholder="+7 (___) ___-__-__" class="form-control">
                            <span class="form-clear"></span>
                            <div class="error">Укажите полный номер телефона</div>
                        </div>
                        <div class="form-group">
                            <label for="">Электронная почта</label>
                            <input name="email" type="text" class="form-control">
                            <span class="form-clear"></span>
                            <div class="error">Введите корректный E-mail</div>
                        </div>
                    </div>
                    <div class="form-faq"><span>*</span> - Поля, обязательные для заполнения</div>
                </div>

                <div class="order-payment">
                    <h2>3. Способ оплаты</h2>
                    <div class="tabs">
                        <input type="radio" id="payment1" value="2" name="payment">
                        <label for="payment1" class="label-tab">
                            <b>Наличными при получении</b>
                            <span>Оплата заказа наличными курьеру при доставке или сотруднику при самовывозе</span>
                        </label>
                        <input type="radio" id="payment2" value="3" name="payment">
                        <label for="payment2" class="label-tab">
                            <b>Онлайн картой на сайте</b>
                            <span>Безопасная онлайн оплата заказа банковскими картами Visa, MasterCard и МИР. Форма оплаты появится сразу после оформления заказа.</span>
                        </label>
                    </div>
                </div>
                <div class="order-comment">
                    <h2>4. Комментарий к заказу</h2>
                    <div class="form-group">
                        <textarea rows="6" class="form-control" name="comment" placeholder="На что нам обратить внимание при обработке вашего заказа?"></textarea>
                    </div>
                </div>
            </div>
            <div class="order-sidebar">
                <div class="order-sidebar-content">
                    <div class="order-sidebar-product">
                        <div class="pfield-head">
                            <div class="title">Состав заказа</div>
                            <div class="edit"><a href="{{ url('cart') }}" class="text-red">Изменить</a></div>
                            <div class="show"><span>показать</span></div>
                        </div>
                        @foreach($variants as $p)
                            <div class="pfield">
                                <div class="pfield-image">
                                    <img src="{{ $p->image }}" alt="">
                                </div>
                                <div class="pfield-content">
                                    <a href="{{ url('product', $p->id) }}">{{ $p->product->name }} {{$p->name}}</a>
                                    <i>{{ $p->amount }} шт. x {{ $p->price }} руб.</i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{--<div class="order-sidebar-list">--}}
                        {{--<ul>--}}
                            {{--<li>Выбранный способ доставки <i>Самовывоз</i></li>--}}
                            {{--<li>--}}
                                {{--Способ оплаты--}}
                                {{--<i>Картой или наличными  при получении</i>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                    <div class="order-sidebar-complete">
                        <ul>
                            <li>Товары <amount>({{$total_amount}})</amount> <span>{{$total_sum}} руб.</span></li>
                            <li id="delivery-price">Доставка @if($total_sum < $freeDelivery)<span>{{ "150 руб." }}</span>@else<span class="text-green">бесплатно</span>@endif</li>
                            @if($discountValue)
                            <li>Скидка по промокоду <span class="text-bold text-red">-{{$discountValue}} руб.</span></li>
                            @endif
                            <li class="text-bold">Итого <span>@if($total_sum < $freeDelivery){{ $total_sum - $discountValue + 150 }}@else{{ $total_sum - $discountValue }}@endif руб.</span></li>
                        </ul>
                    </div>
                </div>
                <div class="order-sidebar-button">
                    <button type="submit" class="btn btn1 block" disabled>ПОДТВЕРДИТЬ ЗАКАЗ</button>
                    <div class="msg">Нажав &laquo;Подтвердить заказ&raquo;, вы&nbsp;соглашаетесь с&nbsp;условиями <a href="{{ url('publichnaya-ofyerta') }}" target="_blank" class="text-red">оферты</a>.</div>
                </div>
            </div>
        </div>

    </div>



{!! Form::close() !!}

    <script type="text/javascript">window.onload = function() { yaCounter48634619.reachGoal("cart_order"); }</script>

@endsection
