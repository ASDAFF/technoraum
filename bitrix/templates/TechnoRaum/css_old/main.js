function sub(obj)
{
	obj.next(".header_menu_dropdown").find("ul").css({"display" : "block"});
	obj.next(".header_menu_dropdown").slideToggle();
}
function bUpdate()
{
	var all_basket = $(".bx-soa-item-table").html();
	$(".bx-soa-item-table").css({"opacity" : "0"}).html("");
	var click = setInterval(function()
	{
		var html = $(".bx-soa-item-table").html();
		if(html.length > 10)
		{
			$(".bx-soa-item-table").html(all_basket).css({"opacity" : "1"});
			$(".bx-soa-pp-company").attr("onClick" , "bUpdate()");
			clearInterval(click);
		}
	},100);
}
function lUpdate()
{
	$("#bx-soa-delivery .ez-checkbox").css({"opacity" : "0"});
	var click = setInterval(function()
	{
		var obj = $("#bx-soa-delivery .bx-soa-pp-company.col-lg-4.col-sm-4.col-xs-6").filter(":first-child");
		var obj2 = $("#bx-soa-paysystem .bx-soa-pp-company.col-lg-4.col-sm-4.col-xs-6").filter(":first-child");
		if(obj)
		{
			obj.removeClass("bx-selected");
			obj.find(".ez-checkbox").removeClass("ez-checked");
			obj.trigger("click");
			clearInterval(click);
		}
		if(obj2)
		{
			obj2.removeClass("bx-selected");
			obj2.find(".ez-checkbox").removeClass("ez-checked");
			obj2.trigger("click");
			clearInterval(click);
		}
	},100);
}
$(document).ready(function()
{
	
	$(".gift").mouseenter(function(){$(this).next(".gift_popup").fadeIn();});
	$(".gift_popup").mouseleave(function(){$(this).fadeOut();});
	$(".glav_cat_div").mouseleave(function(){$(this).find(".gift_popup").fadeOut();});

	$(".bx-authform-formgroup-container input[type='submit']").click(function()
	{
		var click = setInterval(function()
		{
			var obj = $(".bx-authform");
			if(obj)
			{
				if(obj.html() != "")
				{
					setTimeout(function()
					{

						window.location.href="/personal/order/make/";
					},1500);
					clearInterval(click);
				}
			}
		},100);
	});
	$("#bx-soa-basket .pull-right.btn.btn-default.btn-md").click(function()
	{
		$("#bx-soa-delivery .ez-checkbox").css({"opacity" : "0"});
		var click = setInterval(function()
		{
			var obj = $("#bx-soa-delivery .bx-soa-pp-company.col-lg-4.col-sm-4.col-xs-6").filter(":last-child");
			if(obj)
			{
				obj.trigger("click");
				clearInterval(click);
			}
		},100);
	});
	
	setTimeout(function()
	{
		if($(".compare").children(".ez-checkbox").hasClass("ez-checked") == true)
		{
			$(".compare").children(".ez-checkbox").removeClass("ez-checkbox").addClass("ez-checkbox-noclick").addClass("check");
		}
	},2000);
	
	$(".compare").click(function()
	{
		$(this).children(".ez-checkbox").removeClass("ez-checkbox").addClass("ez-checkbox-noclick").addClass("ez-check");
	});
	
	
	$(".bx-soa-pp-company").attr("onClick" , "bUpdate()");
	$(".bx-soa-editstep").attr("onClick" , "lUpdate()");

	$(".bx-soa-item-table a").attr("target" , "_blank");
	$(".bx-soa-reg-block .btn.btn-default.btn-lg").attr("onClick" , "return false;");
	$("li.has_ul").mouseenter(function()
	{
		$(".header_menu_dropdown").css({"display" : "none"});
		$(this).find(".header_menu_dropdown").css({"display" : "block"});
	});
	
	$(".header_menu_dropdown").mouseleave(function()
	{	
		$(this).css({"display" : "none"});
	});
	
	$(".show_map").click(function(e)
	{
		var map = $(this).parent().find("input[name='map']").val();
		$(".map_section #yamap").html("<div onClick='$(this).css({\"display\" : \"none\"});' class='map_lock'></div>" + map);
	});
	
	$(".map_section").mouseleave(function()
	{
		$(".map_lock").css({"display" : "block"});
	});
	
	
	$("input[name='accept']").attr("checked" , "checked");
	$(document).scroll(function()
	{
		var scroll = $(this).scrollTop();
		var obj = $("header");

		var num = 50;

		if(scroll >= num)
		{
			if(obj.hasClass("fixed") == false)
			{
				if($(".mobile_menu_toggler").hasClass("active") == true)
				{
					obj.addClass("fixed").animate({"top" : "0"},500);
					$(".mobile_menu_toggler").css({"position" : "fixed"});
				}
				else
				{
					obj.addClass("fixed").animate({"top" : "0"},500);
					$(".mobile_menu_toggler").css({"position" : "fixed"});
					if(screen.width < 1024)
						$(".header_menu").addClass("fixed");
				}
			}
		}
		else
		{
			if(obj.hasClass("fixed") == true)
			{
				obj.removeClass("fixed");
				if(screen.width < 1024 && $(".mobile_menu_toggler").hasClass("active") == false)
				{
					var menu = $(".header_menu_wrap").html();
					$(".header_menu_wrap").detach();
					$("body").find("header").after("<div class='header_menu_wrap'>" + menu + "</div>");
				}
				$(".header_user").each(function()
				{
					if($(this).hasClass("open") == true)
					{
						$(this).removeClass("open");
						$(".header_right .header_sub").css({"display" : "none"});
						setTimeout(function()
						{
							$(".header_right").removeClass("open");
							$(".header_user").css({"position" : "relative"}).animate({"left" : "0"},1);
							
						},1);
					}
				});
				if(screen.width > 800)
					var mtop = 89;
				else
					var mtop = 73;
					
				$(".mobile_menu_toggler").css({"top" : mtop + "px" , "position" : "absolute"});
				obj.removeAttr("style");
			}
		}
	});
	
	
	$(".bx-price.all").each(function()
	{
		if($(this).text() == "0 руб.")
			$(this).parent().parent().parent().detach();
	});
	
	$(".header_user").click(function(e)
	{
		if(screen.width < 500)
			window.location.href="/presonal/";
		else
		{
			e.preventDefault();
			if($(this).hasClass("open") == false)
			{
				$(this).addClass("open");
				
				setTimeout(function()
				{
					$(".header_right").addClass("open");
					$(".header_right .header_sub").slideToggle();
				},500);
			}
			else
			{
				$(this).removeClass("open");
				$(".header_right .header_sub").slideToggle(300);
				setTimeout(function()
				{
					$(".header_right").removeClass("open");
					$(".header_user").css({"position" : "relative"}).animate({"left" : "0"},500);
				},300);
			}
		}
	});
	$("input[type='checkbox']").click(function()
	{
		if($(this).hasClass("checked") == true)
			$(this).removeClass("checked");
		else
			$(this).addClass("checked");
	});
	

	$(".button.add_to_cart_button").click(function(e)
	{
		e.preventDefault();
		
		if($(this).hasClass("in_card_add") == false)
		{	
			var url = $(this).attr("href");
			$.post(url);
			$(this).text("Добавлено в корзину").addClass("in_card_add");
			
			if($("header").hasClass("fixed") == true)
				var obj = $("header.fixed .card_count").text() * 1;
			else
				var obj = $("header .card_count").text() * 1;
			if(obj)
			{
				obj++;
				$(".card_count").text(obj);
			}
			else
			{
				$(".header_cart").html("<span class='card_count'>1</span>");
			}
		}
	});
	
	$("input[name='compare']").click(function()
	{
		$(this).attr("readonly" , "readonly");
		$(this).parent().next("span").text("Добавлено в сравнение");
		var id = $("input[name='product_id']").val();
		$.post("/compare/index.php?action=ADD_TO_COMPARE_LIST&id="+id);
		$.post("/system.php" , {action : "add" , id : $(this).attr("vl")});
		
	});
	
	$(".ffclose").click(function(){$("body").find(".fancybox-close").trigger("click");});
	
	$(".quantity .plus").click(function()
	{
		var curr = $(this).parent().find("input[name='quantity']").val() * 1;
		curr++;
		if(curr <= 99)
			$(this).parent().find("input[name='quantity']").val(curr)
	});
	
	$(".quantity .minus").click(function()
	{
		var curr = $(this).parent().find("input[name='quantity']").val() * 1;
		curr--
		if(curr >= 1)
			$(this).parent().find("input[name='quantity']").val(curr)
	});
	
	$(".popup_buy_btn").click(function()
	{
		var url = $(this).attr("data-href");
		var q = $(this).closest(".row").find("input[name='quantity']").val();
		$(this).addClass("in_card_add").text("Добавлено");
		for($i=1;$i<=q;$i++)
			$.post(url);
	});
	
	$(".card_page_specs .add_to_cart_button").click(function()
	{
		if(screen.width > 80 && $(this).hasClass("active") == false)
		{
			var name = $(".the_content_section").find("h1").text();
			var price = $(".the_content_section").find(".the_price").find(".price").filter(":first-child").text();
			
			if(!price)
				price = $(".card_page_wrap").find(".price").children("span").filter(":nth-child(1)").text();
			if(!price)
				price = $("input[name='product_price']").val();
			
			var tprice = price.replace(" " , "");
			tprice = tprice.replace("₽" , "");
			tprice = tprice * 1;
			
			var cart_total = $("input[name='total_cart_summ']").val();
			if(cart_total)
				cart_total = cart_total * 1 + tprice;
			else
				cart_total = tprice;
			
			var cart_count = $(".header_cart .card_count");
			if(cart_count.length)
				cart_count = cart_count.text();
			else
				cart_count = 0;
			
			if(!cart_count)
				cart_count = 0;
			else
				cart_count = cart_count  * 1;
			
			$("#shop_popup .cart_count span").text(cart_count);
			$("#shop_popup .cart_summ").text("На сумму "+cart_total +" руб.");
			
			
			var img = $(".the_content_section").find(".big_img").find("img").attr("src");
			var gift = $(".card_page_specs").find(".items");
			if(gift)
				gift = gift.html();
			else
				gift = $(".card_page_specs").find(".items").html();
			
			var gift_t = $(".card_page_specs").find(".icon").html();
			
			
			$("#shop_popup").find(".quantity.gifts").find(".items").html(gift);
			$("#shop_popup").find(".quantity.gifts").find(".icon").html(gift_t);
			$("#shop_popup").find(".main_img").attr("src" , img);
			$("#shop_popup").find(".main_name").text(name);
			$("#shop_popup").find(".main_price").text(price);
			$(".open_shop").trigger("click");
		}
	});
	
	
	$(".button.to_cart_button").click(function(e)
	{
		e.preventDefault();
		
		if(screen.width > 80 && $(this).hasClass("active") == false)
		{
			var name = $(this).closest(".glav_cat_div").find(".title").text();
			var price = $(this).prev(".the_price").find(".price").text();
			
			var tprice = price.replace(" " , "");
			tprice = tprice.replace("₽" , "");
			tprice = tprice * 1;
			var cart_total = $("input[name='total_cart_summ']").val() * 1 + tprice;
			var cart_count = $(".header_cart .card_count").text() * 1 + 1;
			$("#shop_popup .cart_count span").text(cart_count);
			$("#shop_popup .cart_summ").text("На сумму "+cart_total +" руб.");
			
			var img = $(this).closest(".glav_cat_div").find(".filter_opt").next(".img").children("img").attr("src");
			if(!img)
			{
				img = $(this).closest(".glav_cat_div").find(".img").children("img").attr("src");
			}
			var gift = $(this).closest(".glav_cat_div").find(".gift").find(".r").text();
			

			
			
			gift = gift.replace("Подарокна " , "");
			$("#shop_popup .icon").html("");
			$("#shop_popup .items.gg").html("");
			
			var g_items = $(this).closest(".glav_cat_div").find(".gift_popup").html();
			
			if(gift)
			{
				$("#shop_popup .gifts .icon").html('<img src="/bitrix/templates/TechnoRaum/img/gift_icon.png"><span>Подарки на '+gift+'</span>');
				$("#shop_popup .items.gg").append(g_items);
			}
			
			$("#shop_popup").find(".main_img").attr("src" , img);
			$("#shop_popup").find(".main_name").text(name);
			$("#shop_popup").find(".main_price").text(price);
			
			
			$(".open_shop").trigger("click");
		}
		
		if($(this).hasClass("active") == false)
		{
			var url = $(this).attr("href");
			$.post(url);
			
			if($("header").hasClass("fixed") == true)
				var obj = $("header.fixed .card_count").text() * 1;
			else
				var obj = $("header .card_count").text() * 1;
			
			if(obj)
			{
				obj += 1;
				$(".card_count").text(obj);
			}
			else
			{
				$(".header_cart").html("<span class='card_count'>1</span>");
			}
		}
	});
	
	//check if mobile
		var isMobile = false; //initiate as false
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
			|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;
	//-
	if (isMobile) {$('body').addClass('mobile');}
	

	
	
	$('.fancy').fancybox();
	
	$('input[name="tel"]').mask("+7 (999) 999-99-99");
	$(".indexfield").mask("999-999");
	
	
	
	//toggling text height
		var text_toggling_min_height = 189;
		var tech_text_toggling_min_height = text_toggling_min_height;
		
		$('a.read_more_toggler').each(function() {
			if (isNaN($(this).siblings('.text_toggling_div').data('start-height'))) {tech_text_toggling_min_height = text_toggling_min_height;}
			else {tech_text_toggling_min_height = $(this).siblings('.text_toggling_div').data('start-height');}
			
			
			$(this).siblings('.text_toggling_div').data('height',$(this).siblings('.text_toggling_div').height());
			$(this).siblings('.text_toggling_div').css('height',tech_text_toggling_min_height  + "px");
		});
		
		$('a.read_more_toggler').click(function(e) {
			e.preventDefault();
			
			if (isNaN($(this).siblings('.text_toggling_div').data('start-height'))) {tech_text_toggling_min_height = text_toggling_min_height;}
			else {tech_text_toggling_min_height = $(this).siblings('.text_toggling_div').data('start-height');}
			
			$(this).toggleClass('active');
			$(this).siblings('.text_toggling_div').toggleClass('active');
			
			if ($(this).siblings('.text_toggling_div').hasClass('active')) {
				$(this).siblings('.text_toggling_div').css('height',$(this).siblings('.text_toggling_div').data('height')  + "px");
				$(this).children('span').html('Скрыть текст');
			} else {
				$(this).siblings('.text_toggling_div').css('height',tech_text_toggling_min_height  + "px");
				$(this).children('span').html('Раскрыть текст');
			}
		});
	//--
	
	
	//to cart button click 
	$('.glav_cat_div .text a.button').click(function(e) {
		e.preventDefault();
		$(this).addClass('active');		
	});
	//--
	
	if ($(".mini_img_scroll").length) {
		$('.mini_img ul.slides').css('width',(parseInt($('.mini_img ul.slides li').length) * parseInt($('.mini_img ul.slides li').width())) + "px");
		
		$('.mini_img_scroll').customScrollbar({
			 axis:"x"
		});
	}
	
	
	
	//.header_menu_dropdown .inner_section > ul > li:first-child .header_menu_dropdown_level2
	$('.header_menu_dropdown .inner_section > ul > li').not('.header_menu_dropdown .inner_section > ul > li:first-child').hover(function() {
		$('.header_menu_dropdown .inner_section > ul > li:first-child .header_menu_dropdown_level2').css('opacity','0');
	},
	function() {
		$('.header_menu_dropdown .inner_section > ul > li:first-child .header_menu_dropdown_level2').css('opacity','1');
	});
	//--
	
	
	
	
	//mark has_ul 
	$('.header_menu ul > li').each(function() {
		if ($(this).find('ul').length) {$(this).addClass('has_ul');}
	});
	//--
	
	
	
	
	if ($(".top_banner_slider").length) {
		$(".top_banner_slider").flexslider({
        //itemWidth: 300,
        minItems: 1,
        maxItems: 1,
        directionNav: true,
        controlNav: true,
		pauseOnHover:true,
        animation: "slide",
        slideshow:true,		
		move: 1
		});
	
	}
	
	
	
	if ($(".glav_news_slider").length) {
		$(".glav_news_slider").flexslider({
        itemWidth: 400,
        minItems: 1,
        maxItems: 8,
        directionNav: true,
        controlNav: false,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,
		controlsContainer: $(".glav_news_slider_dir"),
		manualControls: $(".glav_news_slider_dir a"),		 
		move: 1
		});
	
	}
	
	if ($(".glav_news_block_slider").length) {
		$(".glav_news_block_slider").flexslider({
        itemWidth: 370,
        minItems: 1,
        maxItems: 3,
        directionNav: true,
        controlNav: false,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,			 
		move: 1
		});
	
	}
	
	
	
	
	
	if(screen.width > 1200)
		var slidec1 = 4
	else if(screen.width > 600)
		var slidec1 = 3;
	else
		var slidec1 = 1;
		
	
	
	
	if ($(".glav_cat_slider").not('.glav_cat_slider_comapare').length) {
		$(".glav_cat_slider").flexslider({
        itemWidth: 293,
        minItems: slidec1,
        maxItems: slidec1,
        directionNav: true,
        controlNav: false,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,		
		move: 1
		});
	
	}
	
	
	
	if ($(".glav_cat_slider.glav_cat_slider_comapare").length) {
		$(".glav_cat_slider_comapare").flexslider({
        //itemWidth: 293,
        minItems: 1,
        maxItems: 1,
        directionNav: true,
        controlNav: false,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,		
		move: 2,
		sync: "#compare_table_slider"
		});
	
	}
	
	
	if ($(".compare_table_slider").length) {
		$(".compare_table_slider").flexslider({
        //itemWidth: 293,
        minItems: 1,
        maxItems: 1,
        directionNav: false,
        controlNav: false,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,		
		move: 1
		
		});
	
	}
	
	
	
	if ($(".right_reviews_slider").length) {
		$(".right_reviews_slider").flexslider({
        itemWidth: 290,
        minItems: 1,
        maxItems: 1,
        directionNav: false,
        controlNav: true,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,		
		move: 1
		});
	
	}
	
	/*
	if ($(".mini_img_slider").length) {
		$(".mini_img_slider").flexslider({
        itemWidth: 82,
        minItems: 3,
        maxItems: 7,
        directionNav: false,
        controlNav: true,
		pauseOnHover:true,
        animation: "slide",
        slideshow:false,		
		move: 1
		});
	
	}
	*/
	
	
	
	//mini_img_slider controls width
	if ($('.mini_img_slider').length) {
		$('.mini_img_slider .flex-control-paging li').css('width',(100 / $('.mini_img_slider .flex-control-paging li').length) + "%");	
		$('.mini_img_slider .flex-control-paging li a').css('width','100%');		
	}	
	//--
	
	
	
		
	//window scroll
	
	var fromTop = 0;
	
	$(window).scroll(function() {
		
		fromTop=$(window).scrollTop();
		/*
		if (fromTop > 50) {$('header').addClass('active');}
		if (fromTop < 50) {$('header').removeClass('active');}
		
		
		if (fromTop > 1000) {$('a.to_top').addClass('active');}
		if (fromTop < 1000) {$('a.to_top').removeClass('active');}
		*/
		
	});
	
	//-
	
	
	
	if ($('input[type="radio"]').length) {
		$('input[type="radio"]').ezMark();
	}
	
	if ($('input[type="checkbox"]').length) {
		$('input[type="checkbox"]').ezMark();
	}
	
	
	
	if ($('select').length) {
		$('select').selectBox({'keepInViewport':false});
	}
	
	
	
	
	//scroll to
		$(".to_top a").add('.header_menu ul li a').add('.scroll_to_button').click(function() {
			$("html, body").animate({
            scrollTop: ($($(this).attr("href")).offset().top) - 0 + "px"
			}, {
				duration: 1100
			});
			return false;
		});		
	//-
	
	
	
	//range no ui slider
	if ($('#filter_range1').length) {
	
		var nouislider1 = document.getElementById('filter_range1');

		noUiSlider.create(nouislider1, {
			start: [0, 50000],
			connect: true,
			animate: false,			
			range: {
				'min': 0,
				'max': 50000
			}
		});
		
		nouislider1.noUiSlider.on('update', function ( values, handle ) {
		$($('input[name="range_min"]')).val(parseInt(nouislider1.noUiSlider.get()[0]) + " P");
		$($('input[name="range_max"]')).val(parseInt(nouislider1.noUiSlider.get()[1]) + " P");
		
		
	});
		
	}
	//--
	
	
	//left_filter_block_div toggling
		$('.cat_filter_block_div .cat_filter_block_div_inner').not('.cat_filter_block_div.active .cat_filter_block_div_inner').slideUp(0);
	
		$('.cat_filter_block_div p.title').click(function() {
			
			$(this).closest('.cat_filter_block_div').toggleClass('active');
			$(this).toggleClass('active');
			$(this).siblings('.cat_filter_block_div_inner').slideToggle();
			
		});
	//--
	
	
	//--search panel toggling
	/*
	$('a.search_toggler').click(function(e) {
		e.preventDefault();
		$('.search_panel_block input[type="text"]').focus();
		$('.search_panel_block').toggleClass('active');
		$(this).toggleClass('active');
	});
	
	$('.search_panel_block a.close_a').click(function(e) {
		e.preventDefault();
		$('.search_panel_block').toggleClass('active');
		$(this).toggleClass('active');
	});
	*/
	//--
	
	
	
	//first screen adaptive height
	/*
	if ($('.top_banner_section').hasClass('top_banner_section_on_inner_page') == false) {//check if not inner page
	
		if ($(window).width() > 980) {		
			var win_height = $(window).height();
			var win_slider_height = win_height - parseInt($('header').height());
			//var screen_to_mockup_ratio = 1080 / win_height;
			var screen_to_mockup_ratio = 584 / win_slider_height;
			
			$('.top_banner_slider ul.slides > li').css('height',win_slider_height + "px");
			 
		}
		
	}
	*/
	//--
	
	
	
	
	
	
	//the tabs
	$('.the_tabs_div').not('.the_tabs_div.active').slideUp(0);
	
	$('.the_tabs_head a').click(function(e) {
		e.preventDefault();
		$('.the_tabs_div').removeClass('active').slideUp();				
		$('.the_tabs_div:nth-child('+(parseInt($(this).index('.the_tabs_head a'))+1)+')').addClass('active').slideDown();
		
		$('.the_tabs_head a').removeClass('active');
		$(this).addClass('active');
	});
	//-
	
	
	
	
	
	
	if(screen.width < 800)
	{
		var menu = $(".header_menu_wrap").html();
		$(".header_menu_wrap").detach();
		$("body").find("header").after("<div class='header_menu_wrap'>" + menu + "</div>");
		
		$(".header_top_menu ul li a").css({"font-size" : "14px" , "font-weight" : "500"});
		var sub = $(".header_top_menu ul").html();
		$(".header_menu_wrap ul.clearfix").append(sub);
	}
	
	//mob menu toggler
	$('.mobile_menu_toggler').click(function(e) 
	{
		e.preventDefault();
		$(this).toggleClass('active');
		//$('.container').toggleClass('menu_is_open');
		
		if(screen.width < 600)
		{
			if($(this).hasClass("active") == true)
				$("body").css({"overlow" : "hidden"});
			else
				$("body").css({"overlow" : "visible"});
		}
		$('.header_menu_wrap').slideToggle(500);
		$('.header_menu_wrap ul').slideToggle(500);
		$(".header_menu.mobile").children("ul").children(".has_ul").children("a").removeAttr("href").attr("onClick" , "sub($(this));");
	});
	//-
	
	
	if(screen.width < 800)
	{
		$(".header_menu").removeClass("desktop").addClass("mobile");
		$(".header_menu ul li a").click(function(e)
		{
			if($(this).parent().parent().parent().hasClass("inner_section") == false && $(this).closest("li").hasClass("has_ul"))
				e.preventDefault();
		});
	}
	
	
	
	//forms
	$(".frm1").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm11',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  
	  $(".frm2").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm22',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  
	  $(".frm3").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm33',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  $(".frm01").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm011',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  
	  $(".frm02").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm022',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  
	    
	  $(".frm03").validate({  //проверка форм
				
	  rules: {
	   
	  		 name: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   
		   mail: {
	   required:true,
	   email: true
	   	   }, 
		   
		   company: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   job: {
	   required:true,
	   minlength:2
	   	   }, 
		   
		   surname: {
	   required:true,
	   minlength:2
	   	   }, 
		     
		   tel: {
	   required:true,
	   minlength:2
	   	   }
	   
	  
	   },
	     onkeyup: false,
		 highlight: function(element, errorClass) {
    $(element).fadeOut(function() {
      $(element).fadeIn(function() {
      $(element).fadeOut(function() {
      $(element).fadeIn();
    });
    });
    });
  
  
  },
  
		 submitHandler: function(form) {
		//if ($('.the_form_div_accept input[type="checkbox"]').is(':checked')) {
			
			 //$(form).find('input[type="submit"]').addClass('done').attr('disabled','disabled');
			  $.fancybox({href:"#thanks_popup"});
			 //$('p.thanks_p').addClass('active');
			
			 //отправка файла на сервер
			$$f({
					formid:'frm033',//id формы
					url:'sender.php'//адрес на серверный скрипт
			});
    
		//}
	
  },
 
	   messages: {
	   		
		name:"Необходимо заполнить это поле",
		mail:"Необходимо заполнить это поле",
		comment:"Необходимо заполнить это поле",
		surname:"Необходимо заполнить это поле",
		job:"Необходимо заполнить это поле",
		company:"Необходимо заполнить это поле",
		tel:"Необходимо заполнить это поле"	
		
	   }
	  });
	  
	  
	  
	  
	  

});