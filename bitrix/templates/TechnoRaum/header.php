<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
IncludeTemplateLangFile(__FILE__);

$url = $_SERVER["REQUEST_URI"];
$url = explode("?" , $url);
$url = $url[0];
$url = explode("#" , $url);
$url = $url[0];
$base_url = $url;
$url = explode("/" , $url);

if($url[1] != "personal" && $url[2] != "order" && $url[3] != "make")
{
	unset($_SESSION["order_step"]);
	unset($_SESSION["DELIVERY"]);
	unset($_SESSION["change_dmethod"]);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
	<head>
		<meta name="yandex-verification" content="b786f5411360f6e5" />
		<?$APPLICATION->ShowHead();?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=cyrillic" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"/>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?
			if($url[1] == "catalog")
			{
				?><link rel="canonical" href="https://<?=$_SERVER["SERVER_NAME"].$base_url?>" /><?
			}
		?>
		<link rel="stylesheet" href="//dcapi.direct-credit.ru/style.css" type="text/css">
		<script src="//dcapi.direct-credit.ru/JsHttpRequest.js" type="text/javascript"></script>
		<script src="//dcapi.direct-credit.ru/dc.js" charset="utf-8" type="text/javascript"></script>
		<script>
			var arrProducts = new Array();
		</script>


	</head>
<body>
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<div class="container" id="top">		
		<div class="mobile_menu_toggler">
			<div></div>
			<div></div>
			<div></div>
		</div>
		<header>
			<div class="header_top">
				<div class="inner_section clearfix">
				<div class="header_search">
					<?$APPLICATION->IncludeComponent("altasib:geobase.select.city", "geobase.select.city", Array(
						"LOADING_AJAX" => "N",	// Подгружать окно "Выбор города" со списком городов ajax-запросом
						"RIGHT_ENABLE" => "Y",	// Выводить вместо правой надписи строки "Выберите город" город, определенный автоматически
						"SMALL_ENABLE" => "Y",	// Показывать компактное окно подтверждения "Это ваш город?"
						"SPAN_LEFT" => "Мой город:",	// Текст левой строки, клик по которой вызывает всплывающее окно
						"SPAN_RIGHT" => "Выберите город",	// Текст правой строки, если город не задан или не определен
					),
						false
					);?>
				</div>
				<?$APPLICATION->IncludeComponent("bitrix:menu", "top-mm", Array(
					"ROOT_MENU_TYPE" => "h",	// Тип меню для первого уровня
						"MAX_LEVEL" => "2",	// Уровень вложенности меню
						"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
						"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
						"MENU_CACHE_TYPE" => "A",	// Тип кеширования
						"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
						"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
						"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
						"COMPONENT_TEMPLATE" => "horizontal_multilevel",
						"DELAY" => "N",	// Откладывать выполнение шаблона меню
						"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
					),
					false,
					array(
					"ACTIVE_COMPONENT" => "Y"
					)
				);
				?>
				<div class="header_top_right clearfix">
					<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/header_phone.php",Array(),Array("MODE"=>"html")); ?>
				</div>
				</div>
			</div>
			<div class="inner_header inner_section clearfix">
				<div style="width:85%;float:left;" class="hl">
					<div class="logo">
						<a href="/">
							<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/company_name.php",Array(),Array("MODE"=>"html")); ?>
						</a>
					</div>
					<div class="header_menu_wrap">
						<?$APPLICATION->IncludeComponent(
						"bitrix:menu", 
						"header_menu", 
						array
						(
						"ROOT_MENU_TYPE" => "top",
						"MAX_LEVEL" => "3",
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => array(),
						"COMPONENT_TEMPLATE" => "header_menu",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N"
						),false,
						array
						(
							"ACTIVE_COMPONENT" => "Y"
						));
						?>
					</div><!--/header_menu_wrap-->	
				</div>
				<div style="float:right;width:15%;position:relative;z-index:999;" class="hr">
					<div class="header_right clearfix">
						<a href="/personal/cart/">
							<div class="header_cart">
							<?
								$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "top", Array(
									"HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
										"PATH_TO_AUTHORIZE" => "",	// Страница авторизации
										"PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
										"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
										"PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
										"PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
										"PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
										"POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
										"SHOW_AUTHOR" => "N",	// Добавить возможность авторизации
										"SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
										"SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
										"SHOW_PERSONAL_LINK" => "Y",	// Отображать персональный раздел
										"SHOW_PRODUCTS" => "N",	// Показывать список товаров
										"SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
									),
									false
								);
							?>
							</div>					
						</a>
						<div class="header_user">
							<a href="/personal/" class="icon"></a>				
						</div>	
						<div class="header_sr">
							<a href="/search/" class="icon"></a>				
						</div>	
						<div class="header_sub">
							<?
								if(!$USER->IsAuthorized())
								{
									?>
									<div><a class="fancy" href="#login">Вход</a></div>
									<div><a class="fancy" href="#reg">Регистрация</a></div>
									<?
								}
								else
								{
									$rsUser = CUser::GetByID($USER->GetID());
									$arUser = $rsUser->Fetch();
									if($arUser["NAME"])
										$name = $arUser["NAME"];
									else
										$name = $arUser["LOGIN"];
									?>
									<div class="text">Вы вошли как: <font color='red'><?=$name;?></font></div>
									<div><a href="/personal/">Личный кабинет</a></div>
									<div><a href="?logout=yes">Выход</a></div>
									<?
								}
							?>
						</div>				
					</div>
				</div>
			</div>		
		</header>
		<section class="main">
			<?
			if(!CSite::InDir('/index.php'))
			{
				?>
				<div class="breadcrumbs inner_section">
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array("START_FROM" => "0","PATH" => "","SITE_ID" => "-"),false,Array('HIDE_ICONS' => 'Y'));?>
				</div>
				<section class="section the_content_section padd_bottom0">	
					<div class="inner_section clearfix">
						<div class="main_title">
							<h1><?$APPLICATION->ShowTitle(false)?></h1>
						</div>
						<div class="contacts_page_wrap">
				<?
			}
			?>