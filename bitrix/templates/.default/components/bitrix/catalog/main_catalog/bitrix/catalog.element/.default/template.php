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
			<? if($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"]):?>
				<p class="old_price"><?=$arResult["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"];?> &#8381;</p>
			<? endif; ?>
			<p class="price"
			   sale-procent="<?=$arResult["PRICES"]["price"]["DISCOUNT_DIFF_PERCENT"]?>"
			   profit="<?=$arResult["PRICES"]["price"]["PRINT_DISCOUNT_DIFF"]?>"
			   old_price="<?=$arResult["PRICES"]["price"]["PRINT_VALUE"];?>"
				>
				<span>
					<?if($arResult["PRICES"]["price"]["VALUE"]):?>
						<?=$arResult["PRICES"]["price"]["PRINT_VALUE"]?>
					<?else:?>
						По запросу
					<?endif;?>
				</span>
				<? if($arResult["PRODUCT"]["QUANTITY"]):?>
					<span style="float:right;position:relative;top:20px" class="in_store">На складе</span>
				<?else:?>
					<span class="in_store" style="background:none;float:right;position:relative;top:20px"><font color='red'>X</font> Отстуствует на складе</span>
				<? endif; ?>
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
						?>
						<?if($arResult["PRICES"]["price"]["VALUE"]):?>
							<a class="button add_to_cart_button" href="<?=$url?>">Добавить в корзину</a>
						<?else:?>
							<a class="fancy button request-a-price" data-name="<?=$arResult['NAME']?>" href="#request-a-price">Запросить цену</a>
						<?endif;?>
						<?
					}
					else
					{
						?><a class="button">Товар в корзине</a><?
					}

					?><a class="fancy button one_click_button" href="#click_one_buy">Купить в один клик</a><?

				?>
			</div>
			<?
		}
		else
		{
			?>
			<div class="button_wrap">
				<a class="fancy button call_me" href="#call_me">Сообщить о наличии</a>
				<a class="fancy button one_click_button" href="#click_one_buy">Купить в один клик</a>
			</div>
			<?
		}
		?>
		<script type="text/javascript">
			arrProducts[0] = {
				id : '<?=$arResult['DIRECT_CREDIT']['id']?>',
				price: '<?=$arResult['DIRECT_CREDIT']['price']?>',
				count: '<?=$arResult['DIRECT_CREDIT']['count']?>',
				type: '<?=$arResult['DIRECT_CREDIT']['type']?>',
				name: '<?=$arResult['DIRECT_CREDIT']['name']?>',
				id_order: '<?=$arResult['DIRECT_CREDIT']['id_order']?>'
			};
		</script>

		<div class="i_creditbtn_first" id="getCredit">
			<p id="getPaymentDc"></p>
			<a class="i_creditgreen" href="javascript:void(0)">Купить в кредит</a>
		</div>

		<?
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
				<div>
					<a style="text-decoration:none" class="card-scroll" data-id="0" href="#tabs"><span>Самовывоз из магазина</span></a>
				</div>
				<div>Бесплатно</div>
				<div>|</div>
				<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
			</div>
			<div class="row">
				<div>
					<a style="text-decoration:none" class="card-scroll" data-id="1" href="#tabs"><span>Самовывоз из пункта выдачи</span></a>
				</div>
				<div>Бесплатно</div>
				<div>|</div>
				<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
			</div>

			<div class="row">
				<div><a style="text-decoration:none" class="card-scroll" data-id="2" href="#tabs"><span>Доставка до двери</span></a></div>
				<div></div>
				<div>|</div>
				<div><a class="fancy" href="#callback2_popup">Уточнить у менеджера</a></div>
			</div>
		</div>

	</div>
</div>


<div class="card_page_description clearfix">

	<div class="card_page_properties">
		<p class="title">Характеристики</p>

		<? foreach($arResult["DISPLAY_PROPERTIES"] as $display_prop):?>
			<? switch ($display_prop["PROPERTY_TYPE"]):

				 case "S":
					 if($display_prop["VALUE"]):
						 foreach($display_prop["VALUE"] as $desc => $value): ?>
							<p>
								<b><?=$display_prop["DESCRIPTION"][$desc];?></b>
								<i><a><?=($value == "Y")? '<img src="'.SITE_TEMPLATE_PATH.'/img/green_check.png" alt="">' : $value;?></a></i>
							</p>
						<? endforeach;
					 endif;
				break;

				case "N":
				case "L":
					if($display_prop["VALUE"]):?>
							<p>
								<b><?=$display_prop["NAME"];?></b>
								<i><a><?=(is_array($display_prop["VALUE"])) ? implode("<br> ",$display_prop["VALUE"]) : $display_prop["VALUE"]?></a></i>
							</p>
					<?endif;
				break;

			 endswitch; ?>
		<? endforeach; ?>
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
			?>


		<div class="">
			<input vl="<?=$arResult["ID"]?>" type="checkbox" class="compare-checkbox" name="compare"
				   <?=($er) ? "checked" : ""?>
				   data-tt-type="square_v"
				   data-tt-label-uncheck="Добавить в сравнение"
				   data-tt-label-check="Добавлено в сравнение"
			/>
		</div>


		<div class="card_consult_text">
			<br>
			<p>
				Если вам требуется помощь в выборе или консультация – звоните<br/>
				на горячую линию <?= tplvar('phone');?> или <a class="fancy" style="text-decoration:none;font-weight: normal;color: #35a2e8;border-bottom: 1px dashed #35a2e8;" href="#callback_popup">закажите обратный звонок</a>
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
						?><li><a href="<?=$arFile["SRC"]?>" target=_blank><?=$arFile["ORIGINAL_NAME"]?></a></li><?
					}
				?>
			</ul>
		<?
	}

	$GLOBALS["recom_filter"] = array("SECTION_ID" => $arResult["IBLOCK_SECTION_ID"]);
?>



