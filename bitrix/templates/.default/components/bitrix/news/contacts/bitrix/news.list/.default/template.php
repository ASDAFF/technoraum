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
<div class="contacts_page_block">
	<p class="title"><?=$GLOBALS['IBLOCK_SECTION_NAME']?></p>
	<?
	foreach($arResult["ITEMS"] as $arItem)
	{
		if($arItem["IBLOCK_SECTION_ID"] == $GLOBALS["IBLOCK_SECTION_ID"])
		{
			?>
				<div class="contacts_page_div">
					<input type="hidden" name="map" value="<?=$arItem["PROPERTIES"]["MAP"]["VALUE"]["TEXT"]?>" />
					<a style="text-decoration:none;" href="#yamap" class="show_map"><p class="adress"><?=$arItem["NAME"]?></p></a>
					<p class="phone">
					<?
						for($i=0;$i<=count($arItem["PROPERTIES"]["PHONE"]["VALUE"])-1;$i++)
						{
							$a_phone = $arItem["PROPERTIES"]["PHONE"]["VALUE"][$i];
							$a_phone = str_replace("+7" , "8", $a_phone);
							$a_phone = str_replace(array("-" , " "), "", $a_phone);
							echo "<a style='color:#000;text-decoration:none;' href='tel:".$a_phone."'>".$arItem["PROPERTIES"]["PHONE"]["VALUE"][$i]."</a><br>";
						}
					?>
					</p>
					<p class="time">
					<?
						for($i=0;$i<=count($arItem["PROPERTIES"]["TIME"]["VALUE"])-1;$i++)
							echo $arItem["PROPERTIES"]["TIME"]["VALUE"][$i]."<br>";
					?>
					</p>
					<p class="note"><b><?=$arItem["PROPERTIES"]["DAY"]["VALUE"]?></b></p>
				</div>
			<?
		}
	}
?>
</div>