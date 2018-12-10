<div class="blank-color">
    <div class="blank-body">
        <div class="blank-title">{{ $variant->product_name }} <span>{{ $variant->name }}</span></div>
        <div class="blank-row">
            <div class="blank-gallery">
                <img src="{{ $variant->imageUrl }}" alt="{{ $variant->product_name }}">
            </div>
            <div class="blank-text">
                <ul class="single-option">
                    <li><b>В наличии:</b>  <span> {{ $variant->stock }} шт.</span></li>
                    <li><b>Код товара:</b>  <span> {{ $variant->external_id }}</span></li>
                </ul>
                <div class="single-setting">
                    <b>Вариант</b>
                    <select class="select-single"></select>
                </div>
                <ul class="single-price">
{{--                    <li>Цена <span class="old">{{ $variants[] }} руб.</span> <span class="discount">-34%</span></li>--}}
                    <li>
                        <div class="price" itemprop="price">{{$variant->price}} <span class="cur">руб.</span></div>
                        <meta itemprop="priceCurrency" content="RUB">
                        <link itemprop="availability" href="http://schema.org/InStock">
                    </li>
                </ul>
                <div class="submit">
                    <div class="control"><button type="submit" class="btn btn1 block">Изменить</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var vid = {{$variant->id}};
    var newid, price, stock, name, ex_id;
    options = {!! json_encode($variants) !!};
    $('.select-single').selectize({
        valueField: 'id',
        labelField: 'name',
        placeholder: 'Выберите ваш вариант',
        options: options,
        onChange: function (val) {
            price = parseInt($('div[data-value="'+val+'"]').find('span.price').data('price'));
            stock = parseInt($('div[data-value="'+val+'"]').find('span.price').data('stock'));
            name = $('div[data-value="'+val+'"]').find('span.name').text();
            var pad = "0000000";
            ex_id = pad.substring(0, pad.length - val.length) + val;
            newid = val;
            $('div.price').html(price+" <span class=\"cur\">руб.</span>");
//            $("input[name='variant_id']").val(val);
//            $("input[name='vid']").val(val);

//            $("input[name='price']").val(price);
//            $('span.price').html("<u>Цена: </u>"+price+" руб/шт");
            $('ul.single-option > li:first-child > span').text(' '+stock + " шт.");
            $('ul.single-option > li:last-child > span').text(' '+ex_id);
            // var pname =  $('h1').text().replace(/[\d].+/, val);
            // console.log(pname);
            $('.blank-title span').text(name);
//            //button
//            var active_button = stock > 0 ? false : true;
//            var style_onklick = stock > 0 ? "block" : "none";
//            var button_text = stock > 0 ? "Добавить в корзину" : "Товар закончился";
//            $('.control button.add_to_cart').attr("disabled", active_button);
//            $('.control button.add_to_cart').text(button_text);
//            $('.single-submit .click').css({"display": style_onklick});
//
//            var cart =  Cookies.getJSON('shopping_cart');
//            var amount = 0;
//            if(cart instanceof  Array && cart[val])
//                amount = cart[val];
//            $('input[name="variants[amount][]"]').val(amount);
//
//            var loc = window.location.pathname;
//            loc = loc.replace(/product\/[\d]+/, 'product\/'+val);
//            setLocation(loc);

        },
        render: {
            option: function (item, escape) {

                var price = item.stock > 0 ? escape(item.price) : 'Товар закончился';

                return '<div class="option">' +
                    '<div class="image">' +
                    '<img src="' + item.imageUrl + '" />' +
                    '</div>' +
                    '<div class="text">' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '<span class="price" data-stock="'+item.stock+'" data-price="'+item.price+'">' + price + '</span>' +
                    '</div>' +
                    '</div>';
            }
        }
    });

    $('.blank-color div.submit button').click(function () {
        var vinput = $("input[name='variants[id][]'][value="+vid+"]").val(newid);
        var amount = vinput.closest('.basket-item').find("input[name='variants[amount][]']").val();
        if(amount > stock)
           amount = stock;
        vinput.closest('.basket-item').find("input[name='variants[amount][]']").val(amount);
        vinput.closest('.basket-item').find("input[name='variants[amount][]']").attr('max', stock);
        vinput.closest('.basket-item').find('.basket-type a').text(name);
        vinput.closest('.basket-item').find('span.price > span').html(price+" руб.");
        vinput.closest('.basket-item').find('.basket-type a').html(name);
        vinput.closest('.basket-item').find('.basket-total span').text(amount*price + " руб.");

        if(amount>1)
            vinput.closest('.basket-item').find('.basket-spinner').find('.cost').html("<amount>"+amount+"</amount> шт. х "+price+" руб.");
        else
            vinput.closest('.basket-item').find('.basket-spinner').find('.cost').empty();

        var total = 0;
        var total_price = 0;
        var cart = {};

        $('.spinner').each(function ()
        {
            var variant_id = $(this).closest('.basket-item').find('div.basket-price input').val();
            cart[variant_id]= parseInt($(this).val());
            total += parseInt($(this).val());
            total_price += parseInt($(this).closest('.basket-item').find('.basket-total > span').html());
        });

        Cookies.set('shopping_cart', cart, {
            expires:30,
            path:'/'
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/ajax/recalculate_discount',
            dataType:"json",
            success: function(data){
                // discount = data.discount;
                // total_price = total_price - discount;
                // $('ul.basket-box  li.discount b').html("-"+ discount + " руб.");
                // $('ul.basket-box  li.total b').html(total_price + " руб.");
                $('.basket-sidebar-content').replaceWith(data);
                couponeHook();
            }
        });

        $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + total );
        $('div.navbar-mobile-basket a span').html(total);
        $('h1 amount').html(total + ' ' + plural(total, 'товар','товара','товаров'));

//        $("a[data-variant_id="+vid+"]").attr('data-variant_id', newid);
//        vid = newid;
        $.fancybox.close();
    });

</script>