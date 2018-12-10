var options;
var prevSetup = Selectize.prototype.setup;

Selectize.prototype.setup = function () {
    prevSetup.call(this);

    // This property is set in native setup
    // Unless the source code changes, it should
    // work with any version
    this.$control_input.prop('readonly', true);
};

$(document).ready(function(){

    //отправка заказа
    $('#store-form').submit(function(e){
        e.preventDefault();

        $('#phone-validation-message').text( '' );
        $('#email-validation-message').text( '' );

        var $form = $(this);

        var action = $form.attr('action');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url: action,
            data: $form.serialize(),
            dataType:"json",
            success:function(data){
                if(!data.validate_error){
                    document.location = data.url;
                } else {
                    console.log(data.validate_phone_message)
                    $('#phone-validation-message').text( data.validate_phone_message );
                    $('#email-validation-message').text( data.validate_email_message );
                }
            }
        });

    });

    //маска номера мобильного
    $('input[name="phone"]').inputmask({
        "mask": "+7 (!##) ###-##-##",
         definitions: {
             '#': { validator: "[0-9]", cardinality: 1},
             '!': { validator: "[1234569]", cardinality: 1},
         },
         onBeforePaste: function (pastedValue, opts) {
             var phone = pastedValue.replace(/[+()-\s]+/g, '');
             if(phone.length == 11){
                 return phone.substr(1,10);
             }
            return phone;
         }
    });

    $('.msg-reply a').on('click', function() {
        $.fancybox.open( $('#add_review_form'), {
            touch: false
        });
    });


    $('.head').on('click', 'nav:not(.current) .search', function(){
        $vav = $(this).closest('nav');
        $vav.find('form input[type="text"]').val('');
        $list = $vav.find('.search-item');
        $list.hide();
        $list.find('.search-list').empty();
    });

    $('.nav-item_sub > a').click(function (e) {
        e.preventDefault();

        if($(this).hasClass('open')) {
            window.location = this.href;
        }
        $(this).addClass('open');

    });



    //сортировка на странице категории
    $('#sort.category-sort').selectmenu({
            change: function(  ) {
                scrto != 1;
                $('input[name="page"]').val(1);
                $('input[name="sort"]').val(this.value);
                var data = $('#filter').serializeArray();
                $contaner = $('.product div.row');
                $contaner.css({'opacity': 0.2});
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:"POST",
                    url:"/ajax/products",
                    data:data,
                    dataType:"json",
                    //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
                    success:function(data){
                        $contaner.html(data.offers);
                        scrto = 1;
                        hookProductClick();
                        showVisible();
                        $contaner.css({'opacity': 1});
                    }
                });
            }
        }
    );

    //сортировка на странице поиска
    $('#sort.search-sort').selectmenu({
            change: function(  ) {
                scrto != 1;
                $('input[name="page"]').val(1);
                $('input[name="sort"]').val(this.value);
                var data = $('#filter').serializeArray();
                $contaner = $('.product div.row');
                $contaner.css({'opacity': 0.2});

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:"POST",
                    url:"/ajax/search/more",
                    data:data,
                    dataType:"json",
                    success:function(data){
                        $contaner.html(data.offers);
                        scrto = 1;
                        hookProductClick();
                        showVisible();
                        $contaner.css({'opacity': 1});
                    }
                });
            }
        }
    );

    //фильтр категорий на странице поиска
    $('.filter').on('click', '#category-search a.category', function (e) {
        e.preventDefault();
        $filter = $(this).closest('.filter');

        //отмечаем текущий
        if($(this).closest('li').hasClass('current')){
            $(this).closest('li').removeClass('current');
            //удаляем id выбранной категории у скрытого input
            $filter.find('form.search-page input[name="category_id"]').val( 0 );
        } else {
            $(this).closest('li').addClass('current');
            //присваеваем id выбранной категории скрытому input
            $filter.find('form.search-page input[name="category_id"]').val( $(this).attr('cid') );
        }

        //снимаем все чекбоксы
        $filter.find('.filter-menu-block.current').not($(this).closest('li')).removeClass('current');

//        scrto != 1;
        $("#filter").find('input[name="page"]').val(1);
        var data = $('#filter').serializeArray();
        $contaner = $('.product div.row');
        $contaner.css({'opacity': 0.2});
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:"/ajax/search/more",
            data:data,
            dataType:"json",
            success:function(data){
                $contaner.html(data.offers);
                if(data.offers.length > 0)
//                scrto = 1;
                hookProductClick();
                showVisible();
                $contaner.css({'opacity': 1});
            }
        });

    });

    //фильтр на странице поиска
    $('form.search-page .filter-complete > button').click(function (e) {
        e.preventDefault();
        scrto != 1;
        $("#filter").find('input[name="page"]').val(1);
        $category_contaner = $(this).closest(".filter").find('#category-search');
        var data = $('#filter').serializeArray();
        $contaner = $('.product div.row');
        $contaner.css({'opacity': 0.2});
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:"/ajax/search/more",
            data:data,
            dataType:"json",
            success:function(data){
                $contaner.html(data.offers);
                $category_contaner.html(data.categories)
                if(data.offers.length > 0)
                scrto = 1;
                hookProductClick();
                showVisible();
                $contaner.css({'opacity': 1});
            }
        });
    });

    //поиск
    $('.search-form form').submit(function(e){
        e.preventDefault();
        window.location.href = "/search/"+$(this).find('input').val()
    });

    //живой поиск
    $('.search-form form input').keyup(function(){

        $parent = $(this).closest('.search-form');
        $contaner = $parent.find('.search-list');
        $contaner.empty();

        var val = $(this).val();

        if(val.length >= 3){

            $parent.find('.search-item').show();

            //отправляем с задержкой
            wait(function(){

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '/ajax/search',
                    data: {'query' : val},
                    dataType: 'json',
                    success: function(data){
                        $contaner.empty();
                        //категории
                        $.each(data.categories, function(id, value){
                            $contaner.append('<li class="search-field"><a rel="nofollow" href="/'+value.slug+'">'+value.name+'</a></li>');
                        });
                        //продукты
                        $.each(data.products, function(id, value){
                            $contaner.append('<li class="search-field"><a rel="nofollow" href="/product/'+value.vid+'">'+value.pname+' '+value.vname+' '+value.seo+'</a></li>');
                        });
                    }
                });

            }, 300 );

        }

    });



    if($('#filter').length > 0)
        scrto = 1;


    //UP image
    window.onscroll = showVisible;
    showVisible();

    //переключение вида
    $('.product-view > ul > li').click(function () {
        if($(this).hasClass('current'))
            return false;
        $('.product-view > ul > li').removeClass('current');
        $(this).addClass('current');
        if($(this).hasClass('inline')) {
            $('div.product').addClass('product_inline');
        } else {
            $('div.product').removeClass('product_inline');
        }
    });

    $(".add_review_open").fancybox({
        afterLoad: function(){
            $('form#add_review').css({display: 'block'});
            $('#add_review_form').find('h3').css({display: 'block'});
            $("#add_review_form").find('#success_message').remove();
        }
    });

    var sels = $('.select-single').selectize({
        valueField: 'id',
        searchField: 'id',
        labelField: 'name',
        placeholder: 'Выберите ваш вариант',
        options: options,
        onChange: function (val) {
            var price = parseInt($('div[data-value="'+val+'"]').find('span.price').data('price'));
            var stock = parseInt($('div[data-value="'+val+'"]').find('span.price').data('stock'));
            var name = $('div[data-value="'+val+'"]').find('span.name').text();
            var pad = "0000000";
            var ex_id = pad.substring(0, pad.length - val.length) + val;
            $('div.price').html(price+" <span class=\"cur\">руб.</span>");
            $("input[name='variant_id']").val(val);
            $("input[name='vid']").val(val);
            $("input[name='variants[id][]']").val(val);
            $("input[name='price']").val(price);
            $('span.price').html("<u>Цена: </u>"+price+" руб/шт");
            $('ul.single-option > li:first-child > span').text(stock + " шт.");
            $('ul.single-option > li:last-child > span').text(ex_id);
            // var pname =  $('h1').text().replace(/[\d].+/, val);
            // console.log(pname);
            $('h1 span').text(name);
            //button
            var active_button = stock > 0 ? false : true;
            var style_onklick = stock > 0 ? "block" : "none";
            var button_text = stock > 0 ? "Добавить в корзину" : "Товар закончился";
            $('.control button.add_to_cart').attr("disabled", active_button);
            $('.control button.add_to_cart').text(button_text);
            $('.single-submit .click').css({"display": style_onklick});

            var cart =  Cookies.getJSON('shopping_cart');
            var amount = 0;
            if(cart instanceof  Array && cart[val])
                amount = cart[val];
            $('input[name="variants[amount][]"]').val(amount);

            var loc = window.location.pathname;
            loc = loc.replace(/product\/[\d]+/, 'product\/'+val);
            setLocation(loc);

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

    if(sels[0]) {
        var sels1 = sels[0].selectize;
        sels1.setValue(variant_id);
    }

    //отправка формы заказа в один клик
    $('form#add_review').on('submit', function(e){
        e.preventDefault();
        $('form#add_review').find('.errors').empty();

        $.ajax({
            type: 'POST',
            url: '/ajax/add_review',
            data: $('form#add_review').serialize(),
            success: function(data){
                if(data.validate_error !== true){
                    $('form#add_review').css({display: 'none'});
                    $('#add_review_form').find('h3').css({display: 'none'});
                    $("form#add_review").trigger('reset');
                    $("#add_review_form").append($('<h1 id="success_message">'+data.message+'</h1>'));
                } else {
                    $.each(data.validate_messages.original, function(key, val){
                        $('form#add_review').find('.errors').append('<div style="color: red">'+val+'</div>')
                    });
                }

            }
        });

    });

    $('.js-rating').awesomeRating({
        valueInitial	: 5,
        values: ["1", "2", "3", "4", "5"],
        targetSelector: "input#rating_value"
    });

    //форма заказа обратного звонка---------------------------------------------
    $('#callbackform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/ajax/callback',
            data: $('#callbackform').serialize(),
            success: function(data){
                if(data.validate_error !== true){
                    $('#tallme .navbar-title').css({display: 'none'});
                    $('#callbackform').css({display: 'none'});
                    $("#callbackform").trigger('reset');
                    $("#tallme").append($('<h1 id="success_message">'+data.message+'</h1>'));
                }

            }
        });
    });


    $('.basket-type a').click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            cache: false,
            url: $(this).data('src'), // preview.php
            data: {
                'product_id' : $(this).data('product_id'),
                'variant_id' : $(this).closest('.basket-item').find('input[name="variants[id][]"]').val(),
            }, // all form fields
            success: function (data) {
                // on success, post (preview) returned data in fancybox
                $.fancybox.open(data, {
                    // // fancybox API options
                    fitToView: true,
                    // width: 905,
                    // height: 505,
                    autoSize: true,
                    closeClick: true,
                    openEffect: 'none',
                    closeEffect: 'none'
                }); // fancybox
            } // success
        }); // ajax
    });


    $(".tallme").fancybox({
        afterLoad: function(){
            $('#tallme .navbar-title').css({display: 'block'});
            $('#callbackform').css({display: 'block'});
            $('#success_message').remove();
            $('input[name=["phone"]').inputmask({
                "mask": "+7 (!##) ###-##-##",
                 definitions: {
                     '#': { validator: "[0-9]", cardinality: 1},
                     '!': { validator: "[1234569]", cardinality: 1},
                 },
                 onBeforePaste: function (pastedValue, opts) {
                     var phone = pastedValue.replace(/[+()-\s]+/g, '');
                     if(phone.length == 11){
                         return phone.substr(1,10);
                     }
                    return phone;
                 }
            });
        }
    });
    //--------------------------------------------------------------------------


    $('.point-up .name').click(function(){
        $(this).parent().toggleClass('current');
        $(this).next('.text').slideToggle();
    });

    $(document).on('click', function(e) {
        var $this = $(e.target);

        if ($('.navbar-item.current').length) {
            if (!$this.hasClass('navbar-button') && !$this.parents('.navbar-item').length) {
                $('.navbar-item.current').removeClass('current');
            }
        }

        if ($('.search-form.current').length) {
            if (!$this.hasClass('search-form label') && !$this.parents('.search-form').length) {
                $('.search-form.current').removeClass('current');
            }
        }

    });

    $('.navbar-mobile').click(function(){
        $(this).parent().toggleClass('current');
        $('html').toggleClass('no-scroll');
    });

    $('.search').click(function(e){
        $(this).parent().toggleClass('current');
    });

    $('.js-location').click(function(e){
        $(this).parent().toggleClass('current');
        $('.nano').nanoScroller({
            preventPageScrolling: true
        });
    });

    $('.search-form label').click(function(){
        $(this).parent().parent().toggleClass('current');
    });

    $('.filter-mobile').click(function(){
        $('.filter').toggleClass('current');
        $('html').toggleClass('no-scroll');
    });

    $('.filter .close').click(function(){
        $('.filter').removeClass('current');
        $('html').removeClass('no-scroll');
    });

    $('.filter button').click(function(){
        $('.filter').removeClass('current');
        $('html').removeClass('no-scroll');
    });

    $('.nano').each(function(){
        $('.nano').nanoScroller({
            preventPageScrolling: true
        });
    });

    $('.filter-collapse-name').click(function(){
        $(this).toggleClass('current');
        $(this).next('div').toggleClass('current');
        $('.nano').nanoScroller();
    });

    $('select.select').selectmenu();

    $('.spinner').spinner({
        min: 1,
        max: $(this).attr('max'),
        start: 1,
        create: function (event, ui) {
            if(parseInt($(this).val())==1) {
                $(this).closest('.basket-spinner').find('.ui-spinner-down').addClass('ui-button-disabled');
            } else {
                $(this).closest('.basket-spinner').find('.ui-spinner-down').removeClass('ui-button-disabled');
            }


            if(parseInt($(this).val()) >= parseInt($(this).attr('max'))) {
                $(this).closest('.basket-spinner').find('.ui-spinner-up').addClass('ui-button-disabled');
                $(this).val($(this).attr('max'));
            } else {
                $(this).closest('.basket-spinner').find('.ui-spinner-up').removeClass('ui-button-disabled');
            }
        },
        stop: function (event, ui) {
            // alert(this.value);

            if(parseInt($(this).val())==1) {
                $(this).closest('.basket-spinner').find('.ui-spinner-down').addClass('ui-button-disabled');
            } else {
                $(this).closest('.basket-spinner').find('.ui-spinner-down').removeClass('ui-button-disabled');
            }


            if(parseInt($(this).val()) >= parseInt($(this).attr('max'))) {
                $(this).closest('.basket-spinner').find('.ui-spinner-up').addClass('ui-button-disabled');
                $(this).val($(this).attr('max'));
            } else {
                $(this).closest('.basket-spinner').find('.ui-spinner-up').removeClass('ui-button-disabled');
            }

            var price = parseInt($(this).closest('.basket-item').find('.basket-price > span.price > span').text());

            $(this).closest('.basket-item').find('.basket-total > span').html(price*$(this).val() + " руб.");
            if($(this).val()>1)
                $(this).closest('.basket-spinner').find('.cost').html("<amount>"+$(this).val()+"</amount> шт. х "+price+" руб.");
            else
                $(this).closest('.basket-spinner').find('.cost').empty();
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

            //пересчет скидки
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

            // $('.basket-slider-complete li:first-child amount').html('('+total+')');
            // $('ul.basket-box  li amount').html(total);

            // $('ul.basket-box  li:first-child b').html(total_price + " руб.");

        }
    });


    function couponeHook() {
        $('.coupone input').focus(function () {
            var coupDiv = $(this).closest('.coupone');
            coupDiv.removeClass(function (index, className) {
                return (className.match (/(^|\s)is-\S+/g) || []).join(' ');
            }).addClass('is-focus');
        });

        $('.coupone input').blur(function () {
            var coupDiv = $(this).closest('.coupone');
            if ($(this).val().length == 0)
                coupDiv.removeClass(function (index, className) {
                    return (className.match (/(^|\s)is-\S+/g) || []).join(' ');
                }).addClass('is-faq');
        });

        $('.coupone input').keyup(function () {
            var coupDiv = $(this).closest('.coupone');

                coupDiv.removeClass(function (index, className) {
                    return (className.match (/(^|\s)is-\S+/g) || []).join(' ');
                }).addClass('is-focus');
        });
    }

    couponeHook();

    //Добавление купона
    $('.coupone button.submit').click(function(e){
        e.preventDefault();
        var $parent = $(this).closest('.coupone');
        var coupone = $parent.find('input.form-control').val();
        $parent.removeClass(function (index, className) {
            return (className.match (/(^|\s)is-\S+/g) || []).join(' ');
        });

        $parent.find('span.error').hide();
        $parent.find('span.success').hide();
        // $parent.find('.form-submit .loader').show();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/ajax/apply_coupone',
            data: {"coupone": coupone},
            dataType:"json",
            success: function(data){

                // $parent.removeClass('is-focus');
                if(data.error == true){

                    $parent.addClass('is-error');
                    $parent.find('span.error').html(data.message).show();
                    $('.basket-sidebar-complete').replaceWith(data.view);
                } else {
                     $parent.addClass('is-success');
                     $parent.find('span.success').html(data.message).show();
                     $('.basket-sidebar-complete').replaceWith(data.view);
                }

            }
        });

    });

    //управление кол-вом товара в окне доб-ния товара---------------------------
    $('.spinner_one').spinner({
        min: 1,
        max: 100,
        start: 1,
        stop: function (event, ui) {

            var cart =  Cookies.getJSON('shopping_cart');
            var total = 0;

            var variant_id = $(this).closest('.basket-item').find('div.basket-price input[name="variants[id][]"]').val();
            var price      = parseInt($(this).closest('.basket-item').find('div.basket-price input[name="price"]').val());
            var count = parseInt($(this).val());
            cart[variant_id] = count;

            $.each(cart, function(key, val){
                total += val;
            });

            Cookies.set('shopping_cart', cart, {
                expires:30,
                path:'/'
            });

            $('div.navbar-item_basket div.navbar-button a').html("в корзине: " + total );
            $('div.navbar-mobile-basket a span').html(total);
            $(this).closest('.basket-item').find('div.basket-total span').text(price * count + " руб.")

        }
    });





    //добавление в корзину------------------------------------------------------
    $("form#add_to_cart button.add_to_cart").fancybox({
        afterLoad : function (){
            $.ajax({
                type: 'POST',
                url: '/ajax/addtocart',
                data: $('form#add_to_cart').serialize(),
                success: function(data){

                    var total = 0;

                    $.each(data.cart, function(index, value){
                        total += value;
                    });

                    $('div.navbar-item_basket div.navbar-button a').html("в корзине: " + total );
                    $('div.navbar-mobile-basket a span').html(total);
                    $('input.spinner_one').val(data.v_count);
                    var price = $('.basket-item').find('div.basket-price input[name="price"]').val();
                    $('.basket-item').find('.basket-total span').text(price * data.v_count + " руб.");

                    var pid = $('input[name="pid"]').val();
                    var added = Cookies.getJSON('added_to_cart');
                    if(added instanceof Array ){
                        if($.inArray(pid, added) == -1)
                            added.push(pid);
                    } else {
                        added = [];
                        added.push(pid);
                    }
                    Cookies.set('added_to_cart', added, {
                        path:'/'
                    });
                }
            });
        }
    });

    //заказ в один клик---------------------------------------------------------
    $(".click button.one_click").fancybox({
        afterLoad : function (){
            $('form#on_click').css({display: 'block'});
            $("#buy_one_click_form").find('#success_message').remove();
        }
    });

    //отправка формы заказа в один клик
    $('form#on_click').on('submit', function(e){
        e.preventDefault();
        $('form#on_click').find('.errors').empty();

        $.ajax({
            type: 'POST',
            url: '/ajax/by_one_click',
            data: $('form#on_click').serialize(),
            success: function(data){
                if(data.validate_error !== true){
                    $('form#on_click').css({display: 'none'});
                    $("form#on_click").trigger('reset');
                    $("#buy_one_click_form").append($('<h1 id="success_message">'+data.message+'</h1>'));
                } else {
                    $.each(data.validate_messages.original, function(key, val){
                        $('form#on_click').find('.errors').append('<div style="color: red">'+val+'</div>')
                    });
                }

            }
        });

    });
    //--------------------------------------------------------------------------

    $(function(){

        $(document).tooltip({
            position: {
				my: 'right bottom',
				at: 'right+20 top-20',
                collision: 'none'
            }
        });
    });

    var $slider = $('.price-slider'),
        $lower = $('#lower_bound'),
        $upper = $('#upper_bound'),
        min_rent = $('#lower_bound').val(),
        max_rent = $('#upper_bound').val();

    $lower.val(min_rent);
    $upper.val(max_rent);

    $('.single-similar').each(function(){
        var similar = new Swiper('.js-similar', {
            loop: true,
            speed: 600,
            slidesPerView: 3,
            spaceBetween: 20,
            simulateTouch: false,
            navigation: {
                prevEl: '.button.prev',
                nextEl: '.button.next'
            },
            breakpoints: {
                1199: {
                    slidesPerView: 4
                },
                1023: {
                    slidesPerView: 3
                },
                767: {
                    slidesPerView: 2
                }
            }
        });
    });

    $('.js-carousel').each(function(){
      var similar = new Swiper('.js-carousel', {
        loop: true,
        speed: 600,
        slidesPerView: 1,
        spaceBetween: 30,
        simulateTouch: false,
        navigation: {
          prevEl: '.carousel-button.prev',
          nextEl: '.carousel-button.next'
        },
        pagination: {
          el: '.carousel-pagination',
          type: 'bullets',
        },
      });
    });

    $('.js-basketBest').each(function(){
        var similar = new Swiper('.js-basketBest', {
            loop: false,
            speed: 600,
            slidesPerView: 1,
            spaceBetween: 0,
            simulateTouch: false,
            breakpoints: {
                1023: {
                    slidesPerView: 3
                },
                767: {
                    slidesPerView: 2
                },
                460: {
                    slidesPerView: 1
                }
            }
        });
    });

    //слайдер цен на странице категории
    $('form#filter.category-page .price-slider').slider({
        orientation: 'horizontal',
        range: true,
        animate: 200,
        min: parseFloat(min_rent),
        max: parseFloat(max_rent),
        step: 1,
        value: 0,
        values: [min_rent, max_rent],
        slide: function(event,ui) {
            $lower.val(ui.values[0]);
            $upper.val(ui.values[1]);
        }
    });

    //слайдер цен на странице поиска
    $('form#filter.search-page .price-slider').slider({
        orientation: 'horizontal',
        range: true,
        animate: 200,
        min: parseFloat(min_rent),
        max: parseFloat(max_rent),
        step: 1,
        value: 0,
        values: [min_rent, max_rent],
        slide: function(event,ui) {
            $lower.val(ui.values[0]);
            $upper.val(ui.values[1]);

        },
        stop: function(event){
            //при изменении цены на странице поиска, id категории в 0
            $('form#filter.search-page').find('input[name="category_id"]').val(0);
            //подгружаем категории
            $("#filter").find('input[name="page"]').val(1);
            $category_contaner = $(this).closest(".filter").find('#category-search');
            var data = $('#filter').serializeArray();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:"/ajax/search/get_categories",
                data:data,
                dataType:"json",
                success:function(data){
                    $category_contaner.html(data.categories)
                }
            });
        }
    });

    $lower.change(function () {
        var low = $lower.val(),
            high = $upper.val();
        low = Math.min(low, high);
        $lower.val(low);
        $slider.slider('values', 0, low);
    });

    $upper.change(function () {
        var low = $lower.val(),
            high = $upper.val();
        high = Math.max(low, high);
        $upper.val(high);
        $slider.slider('values', 1, high);
    });

    $('.fotorama').fotorama({
      width: '100%',
      maxwidth: '100%',
      allowfullscreen: true,
      nav: 'thumbs',
      thumbwidth: 138,
      thumbheight: 92,
      thumbmargin: 10
    });

    $('.single').each(function(){
        var basketHeight = $('.head').outerHeight() + $('.pager').outerHeight() + 30;
        var sidebarHeight = $('.single-content').height();
        $(window).scroll(function() {
            var scrollTop	= $(window).scrollTop();
            var cardOffset = $('.wrap-down').offset().top;
            if (scrollTop > basketHeight) {
                $('.single').addClass('fixed');
                $('.single-fixed').css('top','0');
            } else {
                $('.single').removeClass('fixed');
            }
            if ((cardOffset - scrollTop) > sidebarHeight) {
                $('.single').removeClass('stop');
            } else {
                $('.single').addClass('stop');
                $('.single-fixed').css('top', cardOffset - basketHeight - sidebarHeight + 250);
            }
        });
    });

    $('.js-tabs-a').click(function(){
        $(this).parents('.js-tabs').find('.js-tabs-body').hide();
        $(this).parents('.js-tabs').find('.js-tabs-a.current').removeClass('current');
        $(this).addClass('current').parents('.js-tabs').find('.js-tabs-body[data-id="'+$(this).attr('data-id')+'"]').show();

        return false;
    });

    $('.foot-title').click(function(){
        $(this).toggleClass('current');
        $(this).next('.foot-menu').slideToggle();
    });

    $('.point-up .name').click(function(){
        $(this).parent().toggleClass('current');
        $(this).next('.point-up .text').slideToggle();
    });

    $('.basket-spinner input').change(function () {
        var cart = Cookies.getJSON('shopping_cart');
        if(cart instanceof Object){
            if(cart[variant_id] != null)
                cart[variant_id] += 1;
            else
                cart[variant_id] = 1;
            cart.total += 1;

        } else {
            cart = {};
            cart[variant_id] = 1;
            cart.total = 1;
        }
        Cookies.set('shopping_cart', cart, {
            expires:30,
            path:'/'
        });
    });

    //удаление из корзины позиции
    $('div.basket-remove').click(function () {

        $(this).closest('.basket-item').remove();

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

        //пересчет скидки
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

        Cookies.set('shopping_cart', cart, {
            expires:30,
            path:'/'
        });

        //если позиций не осталось
        if($.isEmptyObject(cart)){
            document.location = '/cart';
        }


        $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + total );
        $('div.navbar-mobile-basket a span').html(total);
        $('h1 amount').html(total + ' ' + plural(total, 'товар','товара','товаров'));

        //
        // $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + total );
        // $('div.navbar-mobile-basket a span').html(total);
        // $('ul.basket-box  li amount').html(total);
        //
        // $('ul.basket-box  li:first-child b').html(total_price + " руб.");
        // $('ul.basket-box  li.total b').html(total_price + " руб.");


        // $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + (parseInt($('div.navbar-mobile-basket a span').html()) - amount) );
        // $('div.navbar-mobile-basket a span').html(parseInt($('div.navbar-mobile-basket a span').html()) - amount);

    });

    $('form.category-page .filter-complete > button').click(function (e) {
        e.preventDefault();
        scrto != 1;
        $("#filter").find('input[name="page"]').val(1);
        var data = $('#filter').serializeArray();
        $contaner = $('.product div.row');
        $contaner.css({'opacity': 0.2});
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:"/ajax/products",
            data:data,
            dataType:"json",
            //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
            success:function(data){
                $contaner.html(data.offers);
                if(data.offers.length > 0)
                    scrto = 1;
                hookProductClick();
                showVisible();
                $contaner.css({'opacity': 1});
            }
        });
    });

    // $('#sort').change(function () {
    //     alert(this.value);
    // });

    var curPage = parseInt($("#filter").find('input[name="page"]').val());

    $(window).scroll(function() {
        var items = $('div.product > div.row > div.post');

        for (var i = 0; i < items.length; i++) {
            var it = items[i];
            var coords = it.getBoundingClientRect();
            // ($(window).scrollTop() + 300 >= $(it).offset().top) && ($(window).scrollTop() + 800 < $(it).offset().top + 430), console.log($(it).data('page'))
            if(coords.top > 0 && coords.top < $(it).height() && coords.bottom < $(window).height() + $(it).height()/2 ) {
                // console.log($(it).data('page'));
                var p = parseInt($(it).data('page'));
                var loc = window.location.pathname;
                var ref_loc = loc;
                if(p != curPage) {
                    if(p > 1) {
                        if(loc.indexOf('page-')>0){
                            loc = loc.replace(/page-[\d]+/, 'page-'+p);
                            setLocation(loc);
                            window.ga('send', 'pageview', location.pathname);
                            window.yaCounter48634619.hit(location.pathname, $('title').html(), ref_loc);
                        } else {
                            loc = loc+'/page-'+p;
                            setLocation(loc);
                            window.ga('send', 'pageview', location.pathname);
                            window.yaCounter48634619.hit(location.pathname, $('title').html(), ref_loc);
                        }
                    } else {
                        if(loc.indexOf('page-')>0){
                            loc = loc.replace(/\/page-[\d]+/, '');
                            setLocation(loc);
                            window.ga('send', 'pageview', location.pathname);
                            window.yaCounter48634619.hit(location.pathname, $('title').html(), ref_loc);
                        }
                    }
                    curPage = p;
                }
            }
        }

        var p_category = parseInt($("#filter.category-page").find('input[name="page"]').val());
        if(p_category){
            $(window).scrollTop() + startUpload >= $('div.product').height() - $(window).height() && scrto && ($('input[name="page"]').val(p_category + 1), scrto = !1, updateProducts())
        }

        var p_search = parseInt($("#filter.search-page").find('input[name="page"]').val());
        if(p_search){
            $(window).scrollTop() + startUpload >= $('div.product').height() - $(window).height() && scrto && ($('input[name="page"]').val(p_search + 1), scrto = !1, updateProductsSearch())
        }

    });

    hookProductClick();
    hookAddToCart();

    $("a[data-similar]").click(function () {
        if(stop == 0) {
            stop = 1;
            var pid = $(this).data("similar");
            var similar = Cookies.getJSON('similar');
            if(similar instanceof Array ){
                if($.inArray(pid, similar) == -1)
                    similar.push(pid);
                else
                    return false;
            } else {
                similar = [];
                similar.push(pid);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/ajax/click",
                data: {"id": pid, "weight" : 0.5},
                dataType: "json",
                success:function () {
                    stop = 0;
                }


            });

            Cookies.set('similar', similar, {
                path:'/'
            });
        }
    });

});


var scrto = 0;
var stop = 0;
var startUpload = 1000;

function setLocation(curLoc){
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}

function  hookAddToCart() {

    $('.pc-payment a').unbind("click");

    $('.pc-payment a').click(function () {

        var img = $(this).closest('div.pc').find('.pc-image img').attr('src');
        var url = $(this).closest('div.pc').find('.pc-image a').attr('href');
        var result = url.match(/product\/([\d]+)/i);
        var vid= result[1];
        var pid = $(this).closest('div.pc').find('.pc-name a').data('id');
        var name = $(this).closest('div.pc').find('.pc-name a span').text();

        var modal = $('#order_content_cat');

        var price = parseInt($(this).closest('div.pc').find('div.pc-price span').text());
        modal.find('div.price').html(price+" <span class=\"cur\">руб.</span>");
        modal.find('div.basket-title a').text(name);
        modal.find("input[name='variant_id']").val(vid);
        modal.find("input[name='variants[id][]']").val(vid);
        modal.find("input[name='price']").val(price);
        modal.find('span.price').html("<u>Цена: </u>"+price+" руб/шт");

        var cart =  Cookies.getJSON('shopping_cart');
        var amount = 0;
        if(cart instanceof Object && cart[vid])
            amount = cart[vid];
        modal.find('input[name="variants[amount][]"]').val(amount);

        modal.find('.basket-image a').css('background-image', 'url("'+img+'")');
        modal.find('.basket-image a').attr('href',url);
        modal.find('.basket-item').find('.basket-total span').text(price * amount + " руб.");

        $.fancybox.open({
            src  : '#order_content_cat',
            type : 'inline',
            opts : {
                afterLoad : function (){
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/addtocart',
                        data: {
                            'variant_id':vid,
                            '_token': $('input[name="_token"]').val()
                        },
                        success: function(data){

                            var total = 0;

                            $.each(data.cart, function(index, value){
                                total += value;
                            });

                            $('div.navbar-item_basket div.navbar-button a').html("в корзине: " + total );
                            $('div.navbar-mobile-basket a span').html(total);
                            modal.find('input.spinner_one').val(data.v_count);
                            // var price = modal.find('.basket-item').find('div.basket-price input[name="price"]').val();
                            //console.log(modal.find('.basket-item').find('.basket-price .basket-total span').text());
                            modal.find('.basket-item').find('.basket-total span').text(price * data.v_count + " руб.");

                            // var pid = $('input[name="pid"]').val();
                            var added = Cookies.getJSON('added_to_cart');
                            if(added instanceof Array ){
                                if($.inArray(pid, added) == -1)
                                    added.push(pid);
                            } else {
                                added = [];
                                added.push(pid);
                            }
                            Cookies.set('added_to_cart', added, {
                                path:'/'
                            });
                        }
                    });
                }
            }
        });
    });
}

function hookProductClick() {

        $("a[data-id]").click(function () {
            if(stop == 0) {
                stop = 1;
                var pid = $(this).data("id");
                var clicked = Cookies.getJSON('clicked');
                if(clicked instanceof Array ){
                    if($.inArray(pid, clicked) == -1)
                        clicked.push(pid);
                    else
                        return false;
                } else {
                    clicked = [];
                    clicked.push(pid);
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/ajax/click",
                    data: {"id": pid, "weight":1},
                    dataType: "json",
                    success:function () {
                        stop = 0;
                    }


                });

                Cookies.set('clicked', clicked, {
                    path:'/'
                });
            }
        });
}

function updateProducts() {
    var data = $('#filter').serializeArray();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:"POST",
        url:"/ajax/products",
        data:data,
        dataType:"json",
        //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
        success:function(data){
            $('.product div.row').append(data.offers);
            scrto = 1;
            hookProductClick();
            hookAddToCart();
            showVisible();
        }
    });
}

//подгрузка позиций на странице поиска
function updateProductsSearch() {
    var data = $('#filter').serializeArray();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:"POST",
        url:"/ajax/search/more",
        data:data,
        dataType:"json",
        success:function(data){
            $('.product div.row').append(data.offers);
            scrto = 1;
            hookProductClick();
            hookAddToCart();
            showVisible();
        }
    });
}

function isVisible(elem) {
    var coords = elem.getBoundingClientRect();
    var windowHeight = document.documentElement.clientHeight;
    var extendedTop = -windowHeight;
    var extendedBottom = 2 * windowHeight;
    // верхняя граница elem в пределах видимости ИЛИ нижняя граница видима
    var topVisible = coords.top > extendedTop && coords.top < extendedBottom;
    var bottomVisible = coords.bottom < extendedBottom && coords.bottom > extendedTop;
    return topVisible || bottomVisible;
}
function showVisible() {
    var imgs = document.getElementsByTagName('img');
    for (var i = 0; i < imgs.length; i++) {
        var img = imgs[i];
        var realsrc = img.getAttribute('data-scr');
        if (!realsrc) continue;
        if (isVisible(img)) {
            img.src = realsrc;
            img.setAttribute('data-scr', '');
        }
    }
}

//функция ожидания
var wait = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function plural(number, one, two, five) {
    let n = Math.abs(number);
    n %= 100;
    if (n >= 5 && n <= 20) {
        return five;
    }
    n %= 10;
    if (n === 1) {
        return one;
    }
    if (n >= 2 && n <= 4) {
        return two;
    }
    return five;
}