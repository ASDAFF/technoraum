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
?>
<div class="icons_wrap">
	<?foreach($arResult["ITEMS"] as $arItem):?>
	<div class="icons_div">
		<div class="img">
			<img src="<?echo $arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?echo $arItem["NAME"]?>">
		</div>
		<div class="text">
			<?echo $arItem["DETAIL_TEXT"]?>
		</div>
	</div>
	<?endforeach;?>
</div>

