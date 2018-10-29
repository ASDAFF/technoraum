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
?><ul class="slides"><?
foreach($arResult["ITEMS"] as $arItem)
{
	if($arItem["PROPERTIES"]["SLIDE_SHOW"]["VALUE"] == 1)
	{
		$pic = $arItem["PROPERTIES"]["SLIDE_IMAGE"]["VALUE"];
		$img = CFile::GetPath($pic);
		$url = $arItem["PROPERTIES"]["SLIDE_URL"]["VALUE"];
		if(!$url)
			$url = $arItem["DETAIL_PAGE_URL"];
		?>
		<li style="background-image:url(<?=$img?>);background-size: cover;">
			<div class="inner_section">
				<div class="top_mob_img_wrap"><img class="top_mob_img" src="<?=SITE_TEMPLATE_PATH?>/img/top_mob_img1.png" alt="" /></div>
				<div class="top_banner_slider_div">
					<p class="title"><?=$arItem["PROPERTIES"]["SLIDE_NAME"]["VALUE"]?></p>
					<p class="red"><?=$arItem["PROPERTIES"]["SLIDE_DESCRIPTION"]["VALUE"]["TEXT"]?></p>
					<a class="button" href="<?=$url?>">Подробнее</a>
					<a class="button see_all" href="/actions/">Все предложения</a>
				</div>
			</div>
		</li>
		<?
	}
}
?></ul><?