<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogTopComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
?>

<div class="glav_cat_wrap flexslider glav_cat_slider slider1">
	<ul class="slides">
		<?
		foreach($arResult["ITEMS"] as $item)
		{
			?>
			<li>
				<div class="glav_cat_div">
					<div class="img">
						<?
							$file = CFile::ResizeImageGet($item["PREVIEW_PICTURE"]["ID"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
						?>
						<img src="<?=$file["src"]?>" alt=""><a class="ref" href="<?=$item["DETAIL_PAGE_URL"]?>"></a>
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
										echo $item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"]." &#8381;";
									}
								?>
							</p>
							<p class="price"
							   sale-procent="<?=$item["PRICES"]["price"]["DISCOUNT_DIFF_PERCENT"]?>"
							   profit="<?=$item["PRICES"]["price"]["PRINT_DISCOUNT_DIFF"]?>"
							   old_price="<?=$item["PRICES"]["price"]["PRINT_VALUE"];?>"
								><?=$item["PRICES"]["price"]["PRINT_VALUE"];?></p>
						</div>
						<a class="button to_cart_button" data-href="<?=$item["BUY_URL"]?>">В корзине</a>
					</div>
				</div>
			</li>
			<?
		}
		?>
	</ul>
</div>