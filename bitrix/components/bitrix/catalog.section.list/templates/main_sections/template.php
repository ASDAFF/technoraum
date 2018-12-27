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
<div class="glav_services_wrap">
<?
	foreach($arResult["SECTIONS"] as $item)
	{
		if(!$item["IBLOCK_SECTION_ID"])
		{
			$ar_result = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$item["IBLOCK_ID"], "ID"=> $item["ID"]),false, Array("UF_DESC")); 
			$res = $ar_result->GetNext();
			?>
				<div class="glav_services_div main_section">
					<div class="img">
						<img src="<?=$item["PICTURE"]["SRC"]?>" alt="" />
					</div>
					<div class="text">
						<div class="text_inner">
							<p class="title"><?=$item["NAME"]?></p>
							<p class="desc"><?=$res["UF_DESC"]?></p>
						</div>
						<a class="button" href="<?=$item["SECTION_PAGE_URL"]?>">Перейти</a>
					</div>
					<a href="<?=$item["SECTION_PAGE_URL"]?>" class="ref"></a>
				</div>
			<?
		}
	}
?>
</div>
<div><? echo CIBlock::GetArrayByID(8, "DESCRIPTION");?></div>