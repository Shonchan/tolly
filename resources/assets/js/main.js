$(document).ready(function(){
    ///
    $(".mobile_button").on("click",function(){
        $("header .content").toggleClass("active"),$(".mobile_button").toggleClass("active"),$("body").toggleClass("lock");
    })

    ///
    $('.search').click(function(e){
        if ($(this).parent().parent().hasClass('active') == false) {

            $('#websearch').focus();
            $(this).parent().parent().addClass('active');
        } else {
            $(this).parent().parent().removeClass('active');
            $('#websearch').blur();
        }
    });
    $('#websearch').blur(function(e) {
        if (e.relatedTarget == null || e.relatedTarget.outerHTML != '<a class="search" href="#"></a>') {
            $(this).parent().parent().parent().removeClass('active');
        }
    });

    ///
    $(".mobile-filter").on("click",function(){
        $(".filter").toggleClass("active"),$("body").toggleClass("lock");
    })
    $(".filter h2").on("click",function(){
        $(".filter").toggleClass("active"),$("body").toggleClass("lock");
    })

    ///
    $(function() {
        jQuery(".title").click( function() {
            jQuery(this).parent().toggleClass("close");
        });
    });

    ///
    $(function() {
        $( "#price" ).slider({
            range: true,
            min: 0,
            max: 10000,
            values: [0, 8750],
            slide: function( event, ui ) {
                //Поле минимального значения
                $("#min").val(ui.values[ 0 ]);
                //Поле максимального значения
                $("#max").val(ui.values[1]);
            }
        });
        //Записываем значения ползунков в момент загрузки страницы
        //То есть значения по умолчанию
        $("#min").val($("#price").slider("values", 0));
        $("#max").val($("#price").slider("values", 1));
    });


    ///
    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');
        $('ul.tabs li').removeClass('current');
        $('.tab').removeClass('current');
        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    })

    ///
    $('.banner ul').bxSlider({
        adaptiveHeight: true,

        nextText: '&#xf105;',
        prevText: '&#xf104;',
        auto: false,
        captions: true
    });
    ///
    $('.big-foto ul').bxSlider({
        adaptiveHeight: true,
        pagerCustom: '#foto-pager',
        nextText: '&#xf105;',
        prevText: '&#xf104;',
        auto: false,
        captions: false
    });


    $('a.add-to-cart').click(function (e) {
        e.preventDefault();
        var variant_id = $(this).data('variant');
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

        $('#cart span.t2').html("В корзине: " + cart.total);
    });

});