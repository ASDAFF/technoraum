<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	CModule::IncludeModule("sale");
	$arID = array();
	$arBasketItems = array();

	$dbBasketItems = CSaleBasket::GetList(
    array(
		"NAME" => "ASC",
		"ID" => "ASC"
		),
		array(
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL"
		),
		false,
		false,
		array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "PRODUCT_PROVIDER_CLASS")
		);
while ($arItems = $dbBasketItems->Fetch())
{
	if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"])
	{
	CSaleBasket::UpdatePrice($arItems["ID"],
	$arItems["CALLBACK_FUNC"],
	$arItems["MODULE"],
	$arItems["PRODUCT_ID"],
	$arItems["QUANTITY"],
	"N",
	$arItems["PRODUCT_PROVIDER_CLASS"]
	);
	$arID[] = $arItems["ID"];
	}
}

	$dbBasketItems = CSaleBasket::GetList(
	array(
	"NAME" => "ASC",
	"ID" => "ASC"
	),
	array(
	"ID" => $arID,
        "ORDER_ID" => "NULL"
	),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE",
	"PRODUCT_ID", "QUANTITY", "DELAY",
	"CAN_BUY", "PRICE", "WEIGHT", "PRODUCT_PROVIDER_CLASS", "NAME")
	);
while ($arItems = $dbBasketItems->Fetch())
{
    $arBasketItems[] = $arItems;
}

	global $curr_weight;
	global $curr_width;
	global $curr_legnth;
	global $curr_height;

	$curr_weight = $arResult["CATALOG_WEIGHT"];
	$curr_width = $arResult["CATALOG_WIDTH"];
	$curr_length = $arResult["CATALOG_LENGTH"];
	$curr_height = $arResult["CATALOG_HEIGHT"];

?> 
<style>
	.card_page_specs span.in_store{background: url(/bitrix/templates/TechnoRaum/img/green_check.png) no-repeat 0 4px}
</style>
<div class="card_page_wrap clearfix">
	<input type="hidden" name="product_id" value="<?=$arResult["ID"]?>" />
	<input type="hidden" name="product_name" value="<?=$arResult["NAME"]?>" />
	<input type="hidden" name="product_price" value="<?=$arResult["PRICES"]["price"]["VALUE"]?>" />
	<div class="card_page_img">
		<?
		if($arResult["PROPERTIES"]["ARTICLE"]["VALUE"])
		{
			?><p class="card_article">Артикул: <span><?=$arResult["PROPERTIES"]["ARTICLE"]["VALUE"]?></span></p><?
		}
		?>
		<div class="big_img">
			<?
				if($arResult["DETAIL_PICTURE"]["SRC"])
					$img = $arResult["DETAIL_PICTURE"]["SRC"];
				else
					$img = $arResult["PREVIEW_PICTURE"]["SRC"];
			?>
			<a href="<?=$img?>" class="fancy" rel="card_gal">
				<em></em><img src="<?=$img?>" alt="" />
			</a>
			<?
				$stick = $arResult["PROPERTIES"]["STICKER"]["VALUE_XML_ID"];
				switch($stick)
				{
					case "val1":
						$block = '<span class="hit">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					case "val2":
						$block = '<span class="new">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					case "val3":
						$block = '<span class="action">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					default:
						unset($block);
						break;
				}
				if($block)
					echo '<div class="flag">'.$block.'</div>';
			?>
		</div>
		<?
			if($arResult["PROPERTIES"]["PHOTO"]["VALUE"])
			{
				?>
				<div class="card_img_mini_wrap">
					<div class="mini_img mini_img_slider mini_img_scroll modern-skin">
						<ul class="slides clearfix">
						<?
							foreach($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $img)
							{
								$big_img = CFile::GetPath($img);
								$sm_img = CFile::ResizeImageGet($img, array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
								?>
								<li>
									<a href="<?=$big_img?>" class="fancy" rel="card_gal">
										<em></em><img src="<?=$sm_img["src"]?>" alt="" />
									</a>
								</li>
								<?
							}
						?>
						</ul>
					</div>
				</div>
				<?
			}
		?>
	</div>
	<div class="card_page_specs">
		<div class="the_price">
			<p class="old_price">
				<?
				if($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $arResult["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"])
				{
					echo $arResult["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"]." &#8381;";
				} 
				?>
			</p>
			<input type="hidden" name="product_price" value="<?=$arResult["PRICES"]["price"]["VALUE"]?>" />
			<p class="price">
				<span><?=$arResult["PRICES"]["price"]["VALUE"]?> &#8381;</span>

				<?
					if($arResult["PRODUCT"]["QUANTITY"])
					{
						?>
							<span style="float:right;position:relative;top:20px" class="in_store">На складе</span>
						<?
					}
					else
					{
						?>
				<span class="in_store" style="background:none;float:right;position:relative;top:20px"><font color='red'>X</font> Отстуствует на складе</span>
						<?
					}
				?>
			</p>
		</div>
		<?
		if($arResult["PRODUCT"]["QUANTITY"])
		{
			$url = $_SERVER["REQUEST_URI"];
			$url = explode("/" , $url);
			for($i=0;$i<=count($url)-3;$i++)
				$new_url .= $url[$i]."/";
			$url = $new_url;
			$url .= "?action=BUY&id=".$arResult["ID"];

			?>
			<input type="hidden" name="to-cart-action" value="<?=$url?>" />
			<div class="button_wrap">
				<?
					$h = 0;
					foreach($arBasketItems as $item)
					{
						if($item["PRODUCT_ID"] == $arResult["ID"])
						{
							$h++;
							break;
						}
					}
					if($h == 0)
					{
						?><a class="button add_to_cart_button" href="<?=$url?>">Добавить в корзину</a><?
					}
					else
					{
						?><a class="button" style="display:inline-block;padding-left:20px;padding-right:20px;">Товар в корзине</a><?
					}

					if($USER->IsAuthorized())
					{
						?><a class="button one_click_button user_buy">Купить в один клик</a><?
					}
					else
					{
						?><a class="fancy button one_click_button" href="#one_click_popup">Купить в один клик</a><?
					}
				?>
			</div>
			<?
		}
		else
		{
			?>
			<div class="button_wrap">
				<a class="fancy button call_me" href="#call_me">Сообщить о наличии</a>
				<? 
					if($USER->IsAuthorized())
					{
						?><a class="button one_click_button user_buy">Купить в один клик</a><?
					}
					else
					{
						?><a class="fancy button one_click_button" href="#one_click_popup">Купить в один клик</a><?
					}
				?>
			</div>
			<?
		}

		if($arResult["PROPERTIES"]["GIFT"]["VALUE"])
		{
			$i = 0;
			$summ = 0;
			foreach($arResult["PROPERTIES"]["GIFT"]["VALUE"] as $gifts)
			{
				$res = CIBlockElement::GetByID($gifts);
				if($ar_res = $res->GetNext())
				{
					$price = CPrice::GetBasePrice($gifts);

					$g_products[$i]["ID"] = $ar_res["ID"];
					$g_products[$i]["NAME"] = $ar_res["NAME"];
					$g_products[$i]["PICTURE"] = $ar_res["PREVIEW_PICTURE"];
					$g_products[$i]["PRICE"] = $price["PRICE"];
					$g_products[$i]["URL"] = $ar_res["DETAIL_PAGE_URL"];
					$summ += $price["PRICE"];
					$i++;
				}
			}
			$summ = number_format($summ , 0 , " " , " ");
			?>
				<div class="icon">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/gift_icon.png" />
					<span>Подарки на <?=$summ?> &#8381;</span>
				</div>
				<div class="items">
				<?
					foreach($g_products as $product)
					{
						$file = CFile::ResizeImageGet($product["PICTURE"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
						?>
							<div class="row">
								<div class="img"><img src="<?=$file["src"]?>" /></div>
								<a style="text-decoration:none;color:#000;" href="<?=$product["URL"]?>"><div class="name"><?=$product["NAME"]?> - <?=number_format($product["PRICE"] , 0 , " " , " ");?> &#8381;</div></a>
							</div>
						<?
					}
				?>
				</div>
			<?
		}
		?>
		<p class="title">Как получить товар</p>
		<div class="card_dil_list">
			<div class="row">
				<div></div>
				<div>Стоимость</div>
				<div></div>
				<div>Когда можно забрать</div>
			</div>
			<div class="row">
				<div><a style="text-decoration:none" class="card-scroll" data-id="1" href="#ordc"><span>Самовывоз из магазина</span></a></div>
				<div>Бесплатно</div>
				<div>|</div>
				<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
			</div>
			<div class="row">
				<div><a style="text-decoration:none" class="card-scroll" data-id="2" href="#ordc"><span>Самовывоз из пункта выдачи</span></a></div>
				<div>Бесплатно</div>
				<div>|</div>
				<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
			</div>
			<?
				if(!empty($curr_weight) && !empty($curr_width) && !empty($curr_length) && !empty($curr_height))
				{
					?>
						<div class="row">
							<div><a style="text-decoration:none" class="card-scroll" data-id="3" href="#ordc"><span>Доставка до двери</span></a></div>
							<div></div>
							<div>|</div>
							<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
						</div>
					<?
				}
			?>
		</div>

	</div>
</div>
<div class="card_page_description clearfix">
	<div class="card_page_properties">
		<p class="title">Характеристики</p>
		<?
		$i=1;
		while(true)
		{
			if($arResult["PROPERTIES"]["DETAIL_P".$i])
			{
				if(!empty($arResult["PROPERTIES"]["DETAIL_P".$i]["VALUE"]))
				{
					if($arResult["PROPERTIES"]["DETAIL_P".$i]["MULTIPLE"] == "N")
					{
						?>
							<p>
								<b><?=$arResult["PROPERTIES"]["DETAIL_P".$i]["NAME"]?></b>
								<i>
									<?
										if($arResult["PROPERTIES"]["DETAIL_P".$i]["VALUE"] == "yes")
											echo '<img src="'.SITE_TEMPLATE_PATH.'/img/green_check.png" alt="" />';
										else
											echo "<a>".$arResult["PROPERTIES"]["DETAIL_P".$i]["VALUE"]."</a>";
									?>
								</i>
							</p>
						<?
					}
					else
					{
						?><p><b style="font-weight:600"><?=$arResult["PROPERTIES"]["DETAIL_P".$i]["NAME"]?>:</b></p><?
						foreach($arResult["PROPERTIES"]["DETAIL_P".$i]["VALUE"] as $propv)
						{
							?>
								<p>
									<b> - <?=$propv?></b>
									<i>
										<img src="<?=SITE_TEMPLATE_PATH?>/img/green_check.png" alt="" />
									</i>
								</p>
							<?
							$p++;
						}
					}
				}
			}
			else
				break;
			$i++;
		}
		$i=1;
		while(true)
		{
			if($arResult["PROPERTIES"]["COMP_P".$i])
			{
				if(!empty($arResult["PROPERTIES"]["COMP_P".$i]["VALUE"]))
				{
					?>
						<p>
							<b><?=$arResult["PROPERTIES"]["COMP_P".$i]["NAME"]?></b>
							<i>
								<?
									if($arResult["PROPERTIES"]["COMP_P".$i]["VALUE"] == 1)
										echo '<img src="'.SITE_TEMPLATE_PATH.'/img/green_check.png" alt="" />';
									else
									{
										echo "<a>".$arResult["PROPERTIES"]["COMP_P".$i]["VALUE"]."</a>";
									}
								?>
							</i>
						</p>
					<?
				}
			}
			else
				break;
			$i++;
		}
		?>
	</div>

	<?

			?>
			<div class="card_page_descr">
			<?
			$er = 0;
			$list = $_SESSION["comp"];
			$list = explode("&" , $list);
			foreach($list as $item)
			{
				if($item == $arResult["ID"])
				{
					$er++;
					break;
				}
			}
			if ($er == 0)
			{
				?><label class="compare"><input vl="<?=$arResult["ID"]?>" type="checkbox" name="compare"/><span>Добавить в сравнение</span></label><?
			}
			else
			{
				?><label class="compare"><input vl="<?=$arResult["ID"]?>" checked="checked" type="checkbox" name="compare"/><span>Добавлено в сравнение</span></label><?
			}
		?>
		<div class="card_consult_text">
			<br>
			<p>
				Если вам требуется помощь в выборе или консультация – звоните<br/>
				на горячую линию 8 800 250 13 08 или <a class="fancy" style="text-decoration:none;font-weight: normal;color: #35a2e8;border-bottom: 1px dashed #35a2e8;" href="#callback_popup">закажите обратный звонок</a>
			</p>
		</div>
<?
		if($arResult["DETAIL_TEXT"])
		{
?>
				<p class="title">Описание</p>
				<div class="text_toggling_div desktop" data-start-height="100">
					<p><?=$arResult["DETAIL_TEXT"]?></p>
				</div>
				<a href="#" class="read_more_toggler"><span>Раскрыть текст</span></a>
			</div>
			<?
		}
	?>
</div>
<div style="clear:both"></div>

<!--TABS-->


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
					"PRODUCT_ID" => $arResult['ID']
				)
			);?>
		</div>
		<div id="tabs-2">
			<?$APPLICATION->IncludeComponent("ipol:ipol.sdekPickup", ".sdekPickup", Array(
				"CITIES" => "",	// Подключаемые города (если не выбрано ни одного - подключаются все)
				"CNT_BASKET" => "N",	// Расчитывать доставку для корзины
				"CNT_DELIV" => "Y",	// Расчитывать доставку при подключении
				"COUNTRIES" => "",	// Подключенные страны
				"FORBIDDEN" => "",	// Отключить расчет для профилей
				"NOMAPS" => "Y",	// Не подключать Яндекс-карты (если их подключает что-то еще на странице)
				"PAYER" => "1",	// Тип плательщика, от лица которого считать доставку
				"PAYSYSTEM" => "",	// Тип платежной системы, с которой будет считатся доставка
				"PRODUCT_ID" => $arResult['ID']
			),
				false
			);?>
		</div>
		<div id="tabs-3">
			<?
			$APPLICATION->IncludeComponent(
				"nbrains:sdek.ajax.delivery",
				"",
				Array(
					"WIDTH" => $arResult['CATALOG_WIDTH'],
					"HEIGHT" => $arResult['CATALOG_HEIGHT'],
					"LENGTH" => $arResult['CATALOG_LENGTH'],
					"WEIGHT" => $arResult['CATALOG_WEIGHT'],
					"PRODUCT_ID" => $arResult['ID']
				)
			);?>
		</div>
	</div>




<!--TABS-END-->



<?
	if($arResult["PROPERTIES"]["DN_FILES"]["VALUE"])
	{
		?>
			<style>
				ul.download_files{padding-bottom:30px}
				ul.download_files li{padding:5px 0;list-style:none;padding-left:15px}
				ul.download_files li a{color:#35a2e8;text-decoration:none}
				ul.download_files li a:hover{border-bottom:2px dotted #35a2e8}
			</style>
			<p class="main_title m">Документы для скачивания:</p>
<ul class="download_files" style="display:flex;flex-wrap:wrap">
				<?
					foreach($arResult["PROPERTIES"]["DN_FILES"]["VALUE"] as $file)
					{
						$arFile = CFile::GetFileArray($file);
						?><li><a href="<?=$arFile["SRC"]?>" target=_blank><?=$arFile["ORIGINAL_NAME"]?></li><?
					}
				?>
			</ul>
		<?
	}
?>
<style>

@media(max-width:1100px )
{
	.left_block .info .r span.time:before{left:22px}
	.st11_city li .name{width:55%}
	
}
@media(max-width:800px)
{
	.steps{flex-direction:column}
	.steps .step{width:70%;text-align:left}
	.steps .step .content{margin:0}
	input{max-width:100%}
	input[type='submit']{font-size:12px !important}
	.steps .step{border-radius:10px 0 0 10px;border-left:1px solid #ededed;}
	.steps .step:nth-child(4):after{display:block}
	.step_form .tabs_content{width: calc(100% - 24px);padding: 40px 10px;}
	.step_form .tabs_content .left_block{width:100%;padding:0}
	.step_form .tabs_content .right_block{width:100%}
	.step_form .tabs .tab{border:1px solid #ededed}
	.step_form .tabs .tab:nth-child(2){border-top:none}
	.step_form .tabs .tab:nth-child(3){border-top:none}
	.step_form .tabs .tab.active{background:#ededed}
	.step_form .tabs_content{width: calc(100% - 24px)}
	.order_container{width:100%;overflow:hidden}
	.left_block .info .l{font-size:11px}
	.left_block .info .r{font-size:11px}
	.left_block .info .r span.time:before{display:none}
	.step_form .tabs .tab{width:100%}
	.pay_methods{display:block}
	.basket_items .row{flex-wrap:wrap;border-bottom:2px solid #ededed}
	.basket_items .row .name{width:75%}
	.basket_items .row .discount{width:60px;margin:20px 0 0 0}
	.basket_items .row .quantity{width:150px;margin:20px 0 0 0}
	.basket_items .row .price{margin:10px 0 0 0}
	textarea.comm{width: calc(100% - 44px)}
	.client_info .row{flex-direction:column}
	.client_info input[type='text'],.client_info input[type='email'],.client_info input[type='number']{width: calc(100% - 24px);}
	.select_dil .row:not(:first-child){flex-wrap:wrap}
	.select_dil .row .col1{width:100%}
	.select_dil .row .col2{width:50%}
	.select_dil .row .col3{width:50%}
	.order_info .row{flex-direction:column}
	#step1_form input[type='submit']{padding: 15px 10px}
	#step1_form_user input[type='submit']{padding: 15px 10px}
	.step_form .tabs_content{padding:10px}
}
@media(max-width:350px)
{
	input{max-width:90%}
}
</style>
<div class="the_section_head">
		<p class="section_title">
 			<a href="#">Похожие товары</a>
		</p>
	</div>
	<?
	$GLOBALS["recom_filter"] = array("SECTION_ID" => $arResult["IBLOCK_SECTION_ID"]);
	$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"recom", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:8:18\",\"DATA\":{\"logic\":\"Equal\",\"value\":12}}]}",
		"DETAIL_URL" => "/catalog/element/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "recom_filter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "catalog",
		"LABEL_PROP" => array(
		),
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"OFFERS_LIMIT" => "0",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "DETAIL_P1",
			1 => "ARTICLE",
			2 => "DETAIL_P7",
			3 => "DETAIL_P8",
			4 => "OLD_PRICE",
			5 => "DETAIL_P3",
			6 => "DETAIL_P5",
			7 => "DETAIL_P4",
			8 => "DETAIL_P2",
			9 => "DETAIL_P6",
			10 => "OLD_PRICE_VAL",
			11 => "STICKER",
			12 => "BRAND",
			13 => "PRESSURE",
			14 => "POWER",
			15 => "TENSION",
			16 => "PERFOMANCE",
			17 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"SECTION_URL" => "/catalog/#SECTION_CODE#/",
		"SEF_MODE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"VIEW_MODE" => "SECTION",
		"COMPONENT_TEMPLATE" => "recom",
		"SEF_RULE" => " /catalog/#SECTION_CODE#/"
	),
	false
);?>
<div style="margin-top: 100px;"></div>
<div class="the_section_head">
		<p class="section_title">
 			<a href="#">Рекомендуемые товары</a>
		</p>
	</div>
	<?
	$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"main_top", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "/catalog/element/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "timestamp_x",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "catalog",
		"LABEL_PROP" => array(
		),
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"OFFERS_LIMIT" => "0",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "DETAIL_P1",
			1 => "ARTICLE",
			2 => "DETAIL_P7",
			3 => "DETAIL_P8",
			4 => "OLD_PRICE",
			5 => "DETAIL_P3",
			6 => "DETAIL_P5",
			7 => "DETAIL_P4",
			8 => "DETAIL_P2",
			9 => "DETAIL_P6",
			10 => "OLD_PRICE_VAL",
			11 => "STICKER",
			12 => "BRAND",
			13 => "PRESSURE",
			14 => "POWER",
			15 => "TENSION",
			16 => "PERFOMANCE",
			17 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"SECTION_URL" => "/catalog/#SECTION_CODE#/",
		"SEF_MODE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"VIEW_MODE" => "SECTION",
		"COMPONENT_TEMPLATE" => "main_top",
		"SEF_RULE" => " /catalog/#SECTION_CODE#/"
	),
	false
);?>