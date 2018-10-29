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
					<div class="the_price">
						<p class="old_price">
						<?
							if($item["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"])
							{
								echo number_format($item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"] , 0 , " " , " ")." &#8381;";
							}
							$price = number_format($item["PRICES"]["price"]["VALUE"], 0, ',', ' ');
						?>
						</p>
						<p class="price"><span><?=$price?></span><span> &#8381;</span></p>
					</div>
					<a class="button to_cart_button" data-href="<?=$item["BUY_URL"]?>">В корзине</a>
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
	<?
}
?>
<div class="clear"></div>
<div><?=$GLOBALS["SECTION_DESCRIPTION"]?></div>

<style>
			#shop_popup.popup{width:800px !important}
			#shop_popup .the_form_div{width:100%;display:flex}
			#shop_popup .the_form_div .img{width:150px}
			#shop_popup .the_form_div .img img{width:100%}
			#shop_popup .the_form_div .description{width:50%}
			#shop_popup .the_form_div .description .name{font-size:16px;font-weight:bold}
			#shop_popup .the_form_div .description .price{padding-top: 10px;font-weight: bold}
			#shop_popup .the_form_div .cart_count{font-weight:100}
			#shop_popup .the_form_div .cart_count span{font-weight:bold}		
			#shop_popup .the_form_div .cart_summ{font-weight:100}
			#shop_popup .the_form_div .cart_summ span{font-weight:bold}
			#shop_popup .the_form_div .quantity{margin-top:15px;display:flex;flex-direction:column}
			#shop_popup .the_form_div .l{width:50%;text-align:left;padding-top:30px}
			#shop_popup .the_form_div .r{width:50%;text-align:right}
			#shop_popup .the_form_div .r button{background: #feee35;padding:15px 20px;font-weight:bold;font-size:16px;color:#000;border:none}
			#shop_popup .the_form_div .form_title.m{font-size:18px}
			
			#shop_popup .row{display:flex}
			#shop_popup .row .img{width:10%}
			#shop_popup .row .img img{width:100%}
			#shop_popup .row .name{width:30%;color:#337ab7}
			#shop_popup .row .price{width:15%;text-align:center}
			#shop_popup .row .quantity{width:20%;display:flex}
			#shop_popup .row .quantity .minus{width:30px;height:30px;background:none;border: 1px solid #ededed;margin:0 auto}
			#shop_popup .row .quantity .plus{width:30px;height:30px;background:none;border: 1px solid #ededed;border-left:none;margin:0 auto}
			#shop_popup .row .quantity .count input{width:50px;height:30px;padding:0 5px;text-align:center;background:none;border: 1px solid #ededed;border-left:none;margin:0 auto}
			#shop_popup .row .btn{width:20%;padding:0;margin:0}
	#shop_popup .row .btn button{width:170px;background: #feee35;padding:10px 20px;font-weight:bold;font-size:12px;color:#000;border:none;position:relative;top:-5px}
			
			#shop_popup .gifts .row{width:100%;padding:0;margin:0}
			#shop_popup .gifts .row .name{width:100%;font-size:12px;padding-left:26px;font-weight:100}
		</style>



		<a class="fancy open_shop" href="#shop_popup"></a>
		<div class="popup callback_popup" id="shop_popup">
			<div class="the_form">										
				<p class="form_title">Товар добавлен в корзину</p>
				<div class="the_form_div">									
					<div class="img"><img class="main_img"/></div>
					<div class="description">
						<div class="name main_name">Мойка высокого давления K7 Premium</div>
						<div class="price main_price"></div>
						<div class="quantity gifts">
							<div class="icon">

							</div>
							<div class="items gg">

							</div>
						</div>
					</div>
					<div class="info">
						<div class="cart_count">В корзине <span>4</span> товара</div>
						<div class="cart_summ">на сумму 17 078 руб.</div>
					</div>
				</div>
				<div class="the_form_div" style="display:flex">									
					<div class="l"><a href="#" class="ffclose">Продолжить покупки</a></div>
					<div class="r"><a href="/personal/cart/"><button>Перейти к корзину</button></a></div>
				</div>
				<p class="form_title m">Вам так же могут понравится</p>
				<?
					$i = 0;
					foreach($arResult["ITEMS"] as $item)
					{
						if($i == 5)
							break;
						?>
							<div class="row">
								<div class="img"><img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" /></div>
								<div class="name"><?=$item["NAME"]?></div>
								<div class="price"><?=number_format($item["PRICES"]["price"]["VALUE"], 0, ',', ' ')?> Р</div>
								<div class="quantity">
									<button class="minus">-</button>
									<div class="count">
										<input type="number" min="1" max="99" name="quantity" value="1"/>
									</div>
									<button class="plus">+</button>
								</div>
								<div class="btn"><button data-href="<?=$item["BUY_URL"]?>" class="popup_buy_btn">Добавить к корзину</button></div>
							</div>		
						<?
						$i++;
					}
				?>		
		</div><!--/callback_popup-->