$(document).ready(function(){

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

    $('#sort').selectmenu({
            change: function(  ) {
                scrto != 1;
                $('input[name="page"]').val(1);
                $('input[name="sort"]').val(this.value);
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
                        $('.product div.row').html(data.offers);
                        scrto = 1;
                    }
                });
            }
        }
    );

    $('.spinner').spinner({
        min: 1,
        max: 10,
        start: 1,
        stop: function (event, ui) {
            // alert(this.value);
            var price = parseInt($(this).closest('.basket-item').find('.basket-price > span.price').text());
            $(this).closest('.basket-item').find('.basket-total').html(price*this.value + " руб.");
            var total = 0;
            var total_price = 0;
            $('.spinner').each(function ()
            {
                total += parseInt($(this).val());
                total_price += parseInt($(this).closest('.basket-item').find('.basket-total').html());
            });
            $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + total );
            $('div.navbar-mobile-basket a span').html(total);
            $('ul.basket-box  li amount').html(total);

            $('ul.basket-box  li:first-child b').html(total_price + " руб.");
            $('ul.basket-box  li.total b').html(total_price + " руб.");

        }
    });

    $(function(){

        $(document).tooltip({
            position: {
                my: 'left top',
                at: 'right+10 top-8',
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

    $('.js-carousel').bxSlider({
        auto: true,
        autoHover: true
    });

    $('.price-slider').slider({
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

    $('.js-single').bxSlider({
        pagerCustom: '.single-thumbnails'
    });

    $('.single').each(function(){
        var basketHeight = $('.head').outerHeight() + $('.pager').outerHeight() + $('h1').outerHeight() + 50;
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

    $('div.basket-remove').click(function () {
        var variant_id = $(this).parent().find('div.basket-price input').val();

        var cart = window.Cookies.getJSON('shopping_cart');

        if(cart instanceof Object){
            // cart.total -= cart[variant_id];
            var amount = cart[variant_id];
            if(cart[variant_id] != null)
                delete cart[variant_id];
            // if(cart.total <= 0)
            //     cart.total = 0;

        }
        Cookies.set('shopping_cart', cart, {
            expires:30,
            path:'/'
        });
        $(this).parent().remove();

        var total = 0;
        var total_price = 0;
        $('.spinner').each(function ()
        {
            total += parseInt($(this).val());
            total_price += parseInt($(this).closest('.basket-item').find('.basket-total').html());
        });
        $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + total );
        $('div.navbar-mobile-basket a span').html(total);
        $('ul.basket-box  li amount').html(total);

        $('ul.basket-box  li:first-child b').html(total_price + " руб.");
        $('ul.basket-box  li.total b').html(total_price + " руб.");


        // $('div.navbar-item_basket div.navbar-button a').html("В корзине: " + (parseInt($('div.navbar-mobile-basket a span').html()) - amount) );
        // $('div.navbar-mobile-basket a span').html(parseInt($('div.navbar-mobile-basket a span').html()) - amount);

    });




    $('.filter-complete > button').click(function (e) {
        e.preventDefault();
        scrto != 1;
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
                $('.product div.row').html(data.offers);
                scrto = 1;
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
                            // window.ga('send', 'pageview', location.pathname);
                            // window.yaCounter48408176.hit(location.pathname, $('title').html(), ref_loc);
                        } else {
                            loc = loc+'/page-'+p;
                            setLocation(loc);
                            // window.ga('send', 'pageview', location.pathname);
                            // window.yaCounter48408176.hit(location.pathname, $('title').html(), ref_loc);
                        }
                    } else {
                        if(loc.indexOf('page-')>0){
                            loc = loc.replace(/\/page-[\d]+/, '');
                            setLocation(loc);
                            // window.ga('send', 'pageview', location.pathname);
                            // window.yaCounter48408176.hit(location.pathname, $('title').html(), ref_loc);
                        }
                    }
                    curPage = p;
                }
            }
        }

        var t = parseInt($("#filter").find('input[name="page"]').val());
        $(window).scrollTop() + startUpload >= $('div.product').height() - $(window).height() && scrto && ($('input[name="page"]').val(t + 1), scrto = !1, updateProducts())
    });

});


var scrto = 1;
var startUpload = 1000;

function setLocation(curLoc){
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
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
        }
    });
}