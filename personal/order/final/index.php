<?
if(!$_GET["ORDER_ID"])
{
	header("Location: /personal/cart/");
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");

?>
<style>
	.contacts_page_wrap .ps_logo{display:none}

	footer{margin-top:0;padding:15px 0 0 0}
	.the_content_section table{margin-bottom:0}
	.the_content_section{margin-bottom:0}
	br{display:none}
	input[type='submit']{margin-top:15px;text-shadow:none;line-height: 54px;max-width:100%;background:#feee35;display:block;font-size:16px;color:#111;text-transform:none;text-align:center;text-decoration:none;border: solid 2px #feee35;-moz-border-radius: 0px;-webkit-border-radius: 0px;border-radius: 0px;-moz-transition:all 0.6s;-webkit-transition:all 0.6s;transition:all 0.6s;}
	input[type='submit']:hover{background:#fffb7e}
	@media(min-width:1000px){.contacts_page_wrap .sale_order_full_table td{width:100% !important}}
</style>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	"order", 
	array(
		"PAY_FROM_ACCOUNT" => "Y",
		"COUNT_DELIVERY_TAX" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"ALLOW_AUTO_REGISTER" => "N",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "Y",
		"TEMPLATE_LOCATION" => "popup",
		"PROP_1" => "",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_PERSONAL" => "/personal/order/",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_NUMBER" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"COMPATIBLE_MODE" => "N",
		"BASKET_POSITION" => "before",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"SERVICES_IMAGES_SCALING" => "adaptive",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "Y",
		"COMPONENT_TEMPLATE" => "order",
		"ALLOW_APPEND_ORDER" => "Y",
		"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"SHOW_VAT_PRICE" => "Y",
		"USE_PREPAYMENT" => "N",
		"USE_PRELOAD" => "Y",
		"ALLOW_USER_PROFILES" => "Y",
		"ALLOW_NEW_PROFILE" => "N",
		"TEMPLATE_THEME" => "site",
		"SHOW_ORDER_BUTTON" => "final_step",
		"SHOW_TOTAL_ORDER_BUTTON" => "N",
		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		"SHOW_DELIVERY_LIST_NAMES" => "Y",
		"SHOW_DELIVERY_INFO_NAME" => "Y",
		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "Y",
		"SKIP_USELESS_BLOCK" => "Y",
		"SHOW_BASKET_HEADERS" => "N",
		"DELIVERY_FADE_EXTRA_SERVICES" => "N",
		"SHOW_COUPONS_BASKET" => "N",
		"SHOW_COUPONS_DELIVERY" => "N",
		"SHOW_COUPONS_PAY_SYSTEM" => "N",
		"SHOW_NEAREST_PICKUP" => "N",
		"DELIVERIES_PER_PAGE" => "9",
		"PAY_SYSTEMS_PER_PAGE" => "9",
		"PICKUPS_PER_PAGE" => "5",
		"SHOW_PICKUP_MAP" => "Y",
		"SHOW_MAP_IN_PROPS" => "Y",
		"PROPS_FADE_LIST_1" => "",
		"PROPS_FADE_LIST_2" => "",
		"ACTION_VARIABLE" => "action",
		"PATH_TO_AUTH" => "/login/",
		"DISABLE_BASKET_REDIRECT" => "N",
		"PRODUCT_COLUMNS_VISIBLE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PROPS",
			2 => "PROPERTY_OLD_PRICE",
			3 => "PROPERTY_OLD_PRICE_VAL",
		),
		"ADDITIONAL_PICT_PROP_15" => "-",
		"PRODUCT_COLUMNS_HIDDEN" => array(
		),
		"USE_YM_GOALS" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_CUSTOM_MAIN_MESSAGES" => "Y",
		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		"USE_CUSTOM_ERROR_MESSAGES" => "N",
		"SPOT_LOCATION_BY_GEOIP" => "Y",
		"ADDITIONAL_PICT_PROP_8" => "-",
		"PICKUP_MAP_TYPE" => "yandex",
		"MESS_AUTH_BLOCK_NAME" => "Авторизация",
		"MESS_REG_BLOCK_NAME" => "Регистрация",
		"MESS_BASKET_BLOCK_NAME" => "Товары в заказе",
		"MESS_REGION_BLOCK_NAME" => "Регион доставки",
		"MESS_PAYMENT_BLOCK_NAME" => "Оплата",
		"MESS_DELIVERY_BLOCK_NAME" => "Доставка",
		"MESS_BUYER_BLOCK_NAME" => "Дополнительно",
		"MESS_BACK" => "Назад",
		"MESS_FURTHER" => "Далее",
		"MESS_EDIT" => "изменить",
		"MESS_ORDER" => "Оформить заказ",
		"MESS_PRICE" => "Стоимость",
		"MESS_PERIOD" => "Срок доставки",
		"MESS_NAV_BACK" => "Назад",
		"MESS_NAV_FORWARD" => "Вперед",
		"SHOW_MAP_FOR_DELIVERIES" => array(
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>