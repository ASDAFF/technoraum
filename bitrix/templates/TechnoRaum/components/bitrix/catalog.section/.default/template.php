<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');
?>
<div class="glav_cat_wrap glav_cat_wrap_mark2">
	<?
		if(count($arResult["ITEMS"]) == 0)
		{
			?><p>Товары в данном разделе отсутствуют</p><?
		}
		foreach($arResult["ITEMS"] as $item)
		{
			$img = CFile::ResizeImageGet($item["PREVIEW_PICTURE"]["ID"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
			?>
			<div class="glav_cat_div">
				<?
					if($item["PROPERTIES"]["GIFT"]["VALUE"])
					{
						$i = 0;
						$summ = 0;
						foreach($item["PROPERTIES"]["GIFT"]["VALUE"] as $gifts)
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
						<div class="gift">
							<div class="l"><img src="<?=SITE_TEMPLATE_PATH?>/img/gift_icon.png" /></div>
							<div class="r">
								<p style="font-weight:500">Подарок<br>на <span style="font-weight:500"><?=$summ?> &#8381;</span></p>
							</div>
						</div>
						<div class="gift_popup">
						<?
							foreach($g_products as $product)
							{
								$file = CFile::ResizeImageGet($product["PICTURE"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
								?>
									<div class="row">
										<div class="img"><img src="<?=$file["src"]?>" /></div>
										<div class="name"><a href="<?=$product["URL"]?>"><?=$product["NAME"]?></a></div>
										<div class="price"><?=number_format($product["PRICE"] , 0 , " " , " ");?> &#8381;</div>
									</div>
								<?
							}
						?>
						</div>
						<?
					}
				?>
				<div class="filter_opt">
					<?
						foreach($item["PROPERTIES"] as $prop)
						{
							?><input type="hidden" class="prop<?=$prop["ID"]?>" value="<?=$prop["VALUE"]?>" /><?
						}
					?>
				</div>
				<div class="img">
					<em></em>
					<img src="<?=$img["src"]?>" alt="" style="max-width: 200px;"/>		
					<a class="ref" href="<?=$item["DETAIL_PAGE_URL"]?>"></a>
					<?
						$stick = $item["PROPERTIES"]["STICKER"]["VALUE_XML_ID"];
						switch($stick)
						{
							case "val1":
								$block = '<span class="hit">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
								break;
							case "val2":
								$block = '<span class="new">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
								break;
							case "val3":
								$block = '<span class="action">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
								break;
							default:
								unset($block);
								break;
						}
						if($block)
							echo '<div class="flag">'.$block.'</div>';
					?>
				</div>
				<div class="text">												 
					<p class="title">
						<a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a>
					</p>

					<div class="i_creditbtn_first direct-credit-section" arrProducts='<?=\Bitrix\Main\Web\Json::encode($item['DIRECT_CREDIT'],null)?>'>
						<p class="getPaymentDcSection<?=$item['ID']?>"></p>
						<a class="i_creditgreen" href="javascript:void(0)">Купить в кредит</a>
					</div>

					<div class="the_price">
						<p class="old_price">
						<? if($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]):?>
							<?=$item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"];?> &#8381;
						<? endif; ?>
						</p>
						<p class="price"
						   sale-procent="<?=$item["PRICES"]["price"]["DISCOUNT_DIFF_PERCENT"]?>"
						   profit="<?=$item["PRICES"]["price"]["PRINT_DISCOUNT_DIFF"]?>"
						   old_price="<?=$item["PRICES"]["price"]["PRINT_VALUE"];?>"
							>
							<?if($item["PRICES"]["price"]["VALUE"]):?>
								<?=$item["PRICES"]["price"]["PRINT_VALUE"]?>
							<?else:?>
								<span style="font-size: 16px;">По запросу</span>
							<?endif;?>
						</p>
					</div>
					<?if($item["PRICES"]["price"]["VALUE"]):?>
						<a class="button to_cart_button" data-href="<?=$item["BUY_URL"]?>">В корзине</a>
					<?endif;?>
				</div>
			</div>
			<?
		}
	?>
</div>
<?
if($arParams["DISPLAY_BOTTOM_PAGER"])
{
	?>
	<div class="clear"></div>
	<div class="pagination"><?=$arResult["NAV_STRING"]?></div>



	<div class="filter-count-pages">
		<div class="title-count">
			Показывать товаров:
		</div>
		<div class="page-count">
			<?
			$arPageCount = array(12,30,50,100,1000);
			?>
			<form action="" method="get">
				<select name="PAGE_ELEMENT_COUNT" onchange="this.form.submit()">
					<? foreach($arPageCount as $count):?>
						<option value="<?=$count?>" <?if($_SESSION["PAGE_ELEMENT_COUNT"] == $count):?> selected <?endif?>><?=($count < 1000) ? $count : "Все"?></option>
					<? endforeach; ?>
				</select>
			</form>
		</div>
	</div>



<?}?>

<div class="clear"></div>
<div><?=$GLOBALS["SECTION_DESCRIPTION"]?></div>
