<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? array($arParams['COMMON_ADD_TO_BASKET_ACTION']) : array());
}
else
{
	$basketAction = (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION'] : array());
}
?>

<?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
		'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],

		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
		'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
		'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
		'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
		'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
		'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
		'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
		'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
		'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
		'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
		'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
		'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
		'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
		"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
		"DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
		"DETAIL_PICTURE_MODE" => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y')
	),
	$component
);?>

<!--TABS-->
<?
$ar_res = CCatalogProduct::GetByID($ElementID);
?>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Самовывоз из магазина</a></li>
		<li><a href="#tabs-2">Самовывоз из пункта выдачи</a></li>
		<li><a href="#tabs-3">Доставка до двери</a></li>
	</ul>
	<div id="tabs-1">
		<?$APPLICATION->IncludeComponent(
			"nbrains:catalog.store.list",
			".store.list",
			Array(
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"MAP_TYPE" => "0",
				"PATH_TO_ELEMENT" => "store/#store_id#",
				"PHONE" => "Y",
				"SCHEDULE" => "Y",
				"SET_TITLE" => "N",
				"TITLE" => "",
				"PRODUCT_ID" => $ElementID
			)
		);?>
	</div>
	<div id="tabs-2">
		<? $APPLICATION->IncludeComponent("nbrains:ipol.sdekPickup", ".sdekPickup", Array(
			"CITIES" => "",	// Подключаемые города (если не выбрано ни одного - подключаются все)
			"CNT_BASKET" => "N",	// Расчитывать доставку для корзины
			"CNT_DELIV" => "Y",	// Расчитывать доставку при подключении
			"COUNTRIES" => "",	// Подключенные страны
			"FORBIDDEN" => array(
				0 => "courier",
				1 => "inpost",
			),
			"NOMAPS" => "Y",	// Не подключать Яндекс-карты (если их подключает что-то еще на странице)
			"PAYER" => "1",	// Тип плательщика, от лица которого считать доставку
			"PAYSYSTEM" => "",	// Тип платежной системы, с которой будет считатся доставка
			"PRODUCT_ID" => $ElementID,
			"WIDTH" => $ar_res["WIDTH"],
			"HEIGHT" => $ar_res["HEIGHT"],
			"LENGTH" => $ar_res["LENGTH"],
			"WEIGHT" => $ar_res["WEIGHT"]
		),
			false
		);?>
		<p class="small-message" style="text-align: center">Сроки и стоимость доставки рассчитаны на основе данных, предоставленных транспортными компаниями.</p>
	</div>
	<div id="tabs-3">
		<? if($ar_res["WEIGHT"] &&
			$ar_res["WIDTH"] &&
			$ar_res["HEIGHT"] &&
			$ar_res["LENGTH"]){
			$APPLICATION->IncludeComponent(
				"nbrains:sdek.ajax.delivery",
				"",
				Array(
					"WIDTH" => $ar_res["WIDTH"],
					"HEIGHT" => $ar_res["HEIGHT"],
					"LENGTH" => $ar_res["LENGTH"],
					"WEIGHT" => $ar_res["WEIGHT"],
					"PRODUCT_ID" => $ElementID
				)
			);
		}else{
			print "Расчет не выполнен! Неуказанны размеры текущего товара.";
		}
		?>
		<p class="small-message" style="text-align: center">Сроки и стоимость доставки рассчитаны на основе данных, предоставленных транспортными компаниями.</p>
	</div>
</div>
<!--TABS-END-->

	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.recommended.products",
		".recommended.products",
		array(
			"ACTION_VARIABLE" => "action_crp",
			"ADDITIONAL_PICT_PROP_8" => "PHOTO",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"BASKET_URL" => "/personal/cart/",
			"CACHE_TIME" => "86400",
			"CACHE_TYPE" => "A",
			"CART_PROPERTIES_8" => array(
				0 => ",",
			),
			"CODE" => $_REQUEST["PRODUCT_CODE"],
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"ELEMENT_SORT_FIELD" => "SORT",
			"ELEMENT_SORT_FIELD2" => "ID",
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_ORDER2" => "DESC",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"IBLOCK_TYPE" => "catalog",
			"ID" => $ElementID,
			"LABEL_PROP_8" => "-",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Похожие товары",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"OFFERS_PROPERTY_LINK" => "LIKE_PRODUCT",
			"PAGE_ELEMENT_COUNT" => "50",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(
				0 => "price",
			),
			"PRICE_VAT_INCLUDE" => "N",
			"PRODUCT_DISPLAY_MODE" => "N",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE_8" => array(
				0 => ",",
			),
			"PROPERTY_LINK" => "LIKE_PRODUCT",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_IMAGE" => "Y",
			"SHOW_NAME" => "Y",
			"SHOW_OLD_PRICE" => "Y",
			"SHOW_PRICE_COUNT" => "1",
			"SHOW_PRODUCTS_8" => "N",
			"TEMPLATE_THEME" => "blue",
			"USE_PRODUCT_QUANTITY" => "N",
			"COMPONENT_TEMPLATE" => ".recommended.products"
		),
		false
	);?>
	<div style="margin-top: 100px;"></div>

	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.recommended.products", 
	".recommended.products", 
	array(
		"ACTION_VARIABLE" => "action_crp",
		"ADDITIONAL_PICT_PROP_8" => "PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"CART_PROPERTIES_8" => array(
			0 => ",",
		),
		"CODE" => $_REQUEST["PRODUCT_CODE"],
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"ELEMENT_SORT_FIELD" => "SORT",
		"ELEMENT_SORT_FIELD2" => "ID",
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_ORDER2" => "DESC",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"IBLOCK_TYPE" => "catalog",
		"ID" => $ElementID,
		"LABEL_PROP_8" => "-",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Рекомендуемые товары",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"OFFERS_PROPERTY_LINK" => "RECOMMEND",
		"PAGE_ELEMENT_COUNT" => "50",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "N",
		"PRODUCT_DISPLAY_MODE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PROPERTY_CODE_8" => array(
			0 => ",",
		),
		"PROPERTY_LINK" => "RECOMMEND",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_IMAGE" => "Y",
		"SHOW_NAME" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_PRODUCTS_8" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => ".recommended.products"
	),
	false
);?>


<?
$res = CIBlockElement::GetByID($ElementID);
if($ar_res = $res->GetNext()){
	$arPrice = CCatalogProduct::GetOptimalPrice($ar_res["ID"], 1, $USER->GetUserGroupArray(), "N");
	$price = CurrencyFormat($arPrice['RESULT_PRICE']['DISCOUNT_PRICE'],$arPrice['RESULT_PRICE']['CURRENCY']);
	$element_name = $ar_res['NAME'];
}else{
	$element_name = "Название неопределенно";
}
?>
<div class="popup callback_popup" id="callback2_popup">
	<form method="post" class="mform">
		<div class="the_form">
			<input type="hidden" name="form_id" value="4" />
			<input type="hidden" name="name_page" value="<?=$element_name?>" />
			<p class="form_title">Уточнить у менеджера</p>
			<div class="the_form_div">
				<input required type="text" name="name" placeholder="Ваше имя">
			</div>
			<div class="the_form_div">
				<input required type="text" name="tel" placeholder="+7 (9ХХ) ХХХ-ХХ-ХХ">
			</div>
			<div class="the_form_div the_form_div_accept">
				<label><input required type="checkbox" name="check" checked="checked"><span>Я согласен с <a href="/soglasie-na-obrabotku-personalnykh-dannykh/" target=_blank>условиями использования</a> моих персональных данных.</span></label>
			</div>
			<div class="the_form_div the_form_div_submit clearfix">
				<input type="submit" name="submit1" onclick="yaCounter51314392.reachGoal('MANAGER',function(){console.log('goal MANAGER');});" value="Отправить">
			</div>
		</div>
	</form>
</div>

<div class="popup click_one_buy" id="click_one_buy">
	<form method="post" class="form_one_buy">
		<input type="hidden" name="link" value="<?=$ar_res['DETAIL_PAGE_URL'];?>">
		<input type="hidden" name="product_name" value="<?=$ar_res['NAME'];?>">
		<input type="hidden" name="price" value="<?=$arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];?>">
		<div class="the_form">
			<p class="form_title">Заявка на покупку товара</p>

			<ul class="media-list">
				<li class="media">
					<a class="pull-left" href="<?=$ar_res['DETAIL_PAGE_URL'];?>">
						<img class="media-object img-thumbnail" width="80" height="80" src="<?=CFile::GetPath($ar_res['PREVIEW_PICTURE'])?>" alt="<?=$ar_res['NAME'];?>">
					</a>
					<div class="media-body">
						<h4 class="media-heading"><?=$ar_res['NAME'];?></h4>
						<p>Ваша цена: <?=$price?></p>
						<div class="input-group">
							<span class="input-group-addon">Количество: </span>
							<input type="number" name="count" min="1" max="100" value="1" placeholder="1" size="4" class="form-control">
							<span class="input-group-addon">шт.</span>
						</div>
					</div>
				</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-body">
					Заполните форму быстрого заказа, наши менеджеры скоро свяжутся с вами.
				</div>
			</div>
			<div class="the_form_div">
				<input required type="text" name="name" placeholder="Ваше имя">
			</div>

			<div class="the_form_div">
				<input required type="text" name="tel" placeholder="+7 (9ХХ) ХХХ-ХХ-ХХ">
			</div>
			<div class="the_form_div the_form_div_accept">
				<label><input required type="checkbox" name="check" checked="checked"><span>Я согласен с <a href="/soglasie-na-obrabotku-personalnykh-dannykh/" target=_blank>условиями использования</a> моих персональных данных.</span></label>
			</div>
			<div class="the_form_div the_form_div_submit clearfix">
				<input type="submit" name="submit1" onclick="yaCounter51314392.reachGoal('ODIN-CLICK',function(){console.log('goal ODIN-CLICK');});" value="Отправить">
			</div>
		</div>
	</form>
</div>
<!--/callback_popup-->

