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
<section class="section glav_services_section">
	<div class="inner_section clearfix">
		<div class="glav_services_wrap">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="glav_services_div main_section">
				<div class="img">
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
				</div>
				<div class="text">
					<div class="text_inner">
						<p class="title"><?=$arItem["NAME"]?></p>
						<p class="desc"><?=$arItem["PREVIEW_TEXT"]?></p>
					</div>
					<a class="button" href="<?=$arItem["CODE"]?>">Перейти</a>
				</div>
				<a href="<?=$arItem["CODE"]?>" class="ref"></a>
			</div>
		<?endforeach;?>
		</div>
	</div>
</section>

