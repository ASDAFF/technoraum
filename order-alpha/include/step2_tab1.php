<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<input type="hidden" name="del_method" value="1" />
<script src="/personal/order/make/js/step2_tab.js" type="text/javascript"></script>
<div class="left_block">
	<div class="info">
		<div class="l"><span>розничные магазины</span></div>
		<div class="r"><span class="time">когда можно забрать</span></div>
	</div>
	<div style="clear:both"></div>
	<ul class="st11_city">
	<?
	$APPLICATION->IncludeComponent
	(
		"bitrix:catalog.section.list", 
		"osn", 
		array
		(
			"ADD_SECTIONS_CHAIN" => "Y",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COUNT_ELEMENTS" => "Y",
			"IBLOCK_ID" => "6",
			"IBLOCK_TYPE" => "materials",
			"SECTION_CODE" => "",
			"SECTION_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "2",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "order_shops"
		),
		false
	);		
	
	$order_shops_sections = $_SESSION["order_shops_sections"]; ?>
	<?
			$i = 0;
			foreach($order_shops_sections as $section)
			{
				$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"order_shops_elements", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => array(
		),
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "18",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "TIME",
			1 => "DAY",
			2 => "MAP",
			3 => "PHONE",
			4 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
			0 => "TIME",
			1 => "DAY",
			2 => "MAP",
			3 => "PHONE",
		),
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => $order_shops_sections[$i]["id"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "order_shops_elements"
	),
	false
);
				$order_shops_items[$i] = $_SESSION["order_shops_items"];
				$i++;
			}
			

					$m = 0;
					$has_city = -1;
					foreach($order_shops_sections as $section)
					{
						?>
							<li <? if($_SESSION["city"] == $section["name"]) { $has_city = $m; echo "class='active'";}?>>
								<a href="#" class="city_title"><?=$section["name"]?></a>
								<ul>
									<?
										unset($point_name);
										unset($point_phone);
										unset($point_time);
										unset($point_day);
										unset($point_map);
										unset($cord_x);
										unset($cord_y);
										
										$k = 0;
										foreach($order_shops_items[$m] as $item)
										{
											$point_name 	.= $item["name"] 	. "&&&";
											for($i=0;$i<=count($item["phone"])-1;$i++)
												$point_phone 	.= $item["phone"][$i] 	. "|";
											$point_phone .= "&&&";
											for($i=0;$i<=count($item["time"])-1;$i++)
												$point_time 	.= $item["time"][$i] 	. "|";
											$point_phone .= "&&&";	
											$point_day	 	.= $item["day"] 	. "&&&";
											$point_map	 	.= $item["map"] 	. "&&&";
											$map = explode(" ", $item["map"]);
											$cord_x[$k] = $map[0];
											$cord_y[$k] = $map[1];
											$k++;
											?>
												<li style="display:flex">
													<div class="radio-btn btn1" data-id="">
														<div></div>
													</div>
													<div class="name" style="display:flex;padding-left:15px">
														<a><?=$item["name"]?></a>
													</div>
												</li>
											<?
										}
									?>
								</ul>
								<input type="hidden" name="item_point_name" value="<?=$point_name?>" />
								<input type="hidden" name="item_point_phone" value="<?=$point_phone?>" />
								<input type="hidden" name="item_point_time" value="<?=$point_time?>" />
							</li>
						<?
						$m++;
					}
					if($has_city != -1)
					{
						unset($point_name);
						unset($point_phone);
						unset($point_time);
						unset($point_day);
						unset($point_map);
						unset($cord_x);
						unset($cord_y);
						
						foreach($order_shops_items[$has_city] as $item)
						{
							$point_name 	.= $item["name"] 	. "&&&";
							
							for($i=0;$i<=count($item["phone"])-1;$i++)
								$point_phone 	.= $item["phone"][$i] 	. "|";
							$point_phone .= "&&&";
							
							for($i=0;$i<=count($item["time"])-1;$i++)
								$point_time 	.= $item["time"][$i] 	. "|";
							$point_phone .= "&&&";	
							
							$point_day	 	.= $item["day"] 	. "&&&";
						}
						
						?>
							<input type="hidden" name="point_name" value="<?=$point_name?>" />
							<input type="hidden" name="point_phone" value="<?=$point_phone?>" />
							<input type="hidden" name="point_time" value="<?=$point_time?>" />
							<input type="hidden" name="city" value="<?=$_SESSION["city"]?>" />
							<input type="hidden" name="temp1" />
							<input type="hidden" name="temp2" value="1"/>
						<?
					}
					else
					{
						unset($point_name);
						unset($point_phone);
						unset($point_time);
						unset($point_day);
						unset($point_map);
						unset($cord_x);
						unset($cord_y);
						
						for($i=0;$i<=count($order_shops_items)-1;$i++)
						{
							foreach($order_shops_items[$i] as $item)
							{
								$point_name 	.= $order_shops_sections[$i]["name"].", ".$item["name"] 	. "&&&";
								
								for($j=0;$j<=count($item["phone"])-1;$j++)
									$point_phone 	.= $item["phone"][$j] 	. "|";
								$point_phone .= "&&&";
								
								for($j=0;$j<=count($item["time"])-1;$j++)
									$point_time 	.= $item["time"][$j] 	. "|";
								$point_phone .= "&&&";	
								
								$point_day	 	.= $item["day"] 	. "&&&";
							}
						}
					}
				?>
				</ul>
			</div>
			<div class="right_block" id="map"></div>
			<div style="clear:both;"></div>
			
<script>
$(document).ready(function()
{
	$(".nstep input[name='change_dmethod']").val('1');
	$(".radio-btn").click(function()
	{
		var city = $(".nstep input[name='city']").val();
		var iclass = $(this).attr("class");
		var text = $(this).next(".name").text();
		text = text.replace(" " , "");
		text = text.replace("." , ". ");
		text = text.replace("," , ", ");
		
		iclass = iclass.replace("radio-btn " , "");
		iclass = iclass.replace("check" , "");
		iclass = iclass.replace(" ", "");
		
		$(".radio-btn."+iclass).removeClass("check");
		$(this).addClass("check");
		$(".nstep input[name='dil_address']").val(city + ", " + text);
	});
});
</script>