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

	$('select').selectmenu();

	$(function(){
		$('.spinner').spinner({
			min: 1,
			max: 10,
			start: 1
		});
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
		min_rent = 3000,
		max_rent = 28000;

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
		min: 100,
		max: 34900,
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
		var basketHeight = $('.head').outerHeight() + $('.pager').outerHeight() + $('h1').outerHeight() + 50
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

});