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
$this->setFrameMode(true);
$GLOBALS["SECTION_DESCRIPTION"] = $arResult["SECTION"]["DESCRIPTION"];
?>
<div class="glav_cat_wrap glav_cat_wrap_mark3">
	<?
		foreach($arResult["SECTIONS"] as $sub_item)
		{
			?>
				<div class="glav_cat_div">
					<div class="img">
						<em></em><img src="<?=$sub_item["PICTURE"]["SRC"]?>" alt="" style="max-width: 250px;"/>
						<a class="ref" href="<?=$sub_item["SECTION_PAGE_URL"]?>"></a>
					</div>
					<div class="text">												 
						<p class="title"><a href="<?=$sub_item["SECTION_PAGE_URL"]?>"><?=$sub_item["NAME"]?></a></p>												
					</div>
				</div>
			<?
		}
	?>
</div>