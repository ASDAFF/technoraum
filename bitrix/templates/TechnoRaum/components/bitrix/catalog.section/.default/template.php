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



						<div class="gift">
							<?if($item["GIFT_SUM"]):?>
							<div class="line">
								<div class="l"><img src="<?=SITE_TEMPLATE_PATH?>/img/gift_icon.png" /></div>
								<div class="r">
									<p>Подарок<br>на <?=$item["GIFT_SUM"]?> &#8381;</p>
								</div>
							</div>
							<? endif; ?>

							<? if($item["PROPERTIES"]["STICKER_WARRANTY"]["VALUE"]):?>
							<div class="line">
								<img src="<?=CFile::ResizeImageGet($item["PROPERTIES"]["STICKER_WARRANTY"]["VALUE"], array('width'=>75, 'height'=> 75), BX_RESIZE_IMAGE_PROPORTIONAL, true)['src']?>" alt="<?=$item['NAME']?>">
							</div>
							<? endif; ?>
						</div>

						<? if(count($item["PROPERTIES"]["GIFT"]["ITEM"]) > 0): ?>
							<div class="list-group-gift">
								<a href="#" class="list-group-item">
									<div class="thumbnail list-group-img">
										<img alt="<?=$item["NAME"]?>" style="height: 50px; width: 50px; display: block;" src="<?=SITE_TEMPLATE_PATH?>/img/gift_icon.png">
									</div>
									<div class="list-group-desc">
										<div class="list-group-item-text">Подарки</div>
										<div class="list-group-item-text">на сумму</div>
										<div class="list-group-item-text"><?=number_format($item["GIFT_SUM"] , 0 , " " , " ")?> &#8381;</div>
									</div>
								</a>
								<? foreach($item["PROPERTIES"]["GIFT"]["ITEM"] as $product):?>
								<a href="<?=$product["URL"]?>" class="list-group-item">
									<div class="thumbnail list-group-img">
										<img alt="<?=$product["NAME"]?>" style="height: 50px; width: 50px; display: block;" src="<?=$product["PICTURE"]["src"]?>">
									</div>
									<div class="list-group-desc">
										<div class="list-group-item-text"><?=$product["NAME"]?></div>
										<div class="list-group-item-text"><?=number_format($product["PRICE"] , 0 , " " , " ");?> &#8381;</div>
										<div class="list-group-item-text"><?=$product["DESC"]?></div>
									</div>
								</a>
								<? endforeach; ?>
							</div>
						<? endif; ?>

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
					<?else:?>
						<a class="fancy request-a-price" data-name="<?=$item['NAME']?>" href="#request-a-price">Запросить</a>
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
