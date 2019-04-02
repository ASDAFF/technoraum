<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
// hack
if (!is_array($arResult['SECTION']))
{
	$dbRes = CIBlock::GetByID($arResult['IBLOCK_ID']);
	if ($arIBlock = $dbRes->GetNext())
	{
		$arIBlock["~LIST_PAGE_URL"] = str_replace(
			array("#SERVER_NAME#", "#SITE_DIR#", "#IBLOCK_TYPE_ID#", "#IBLOCK_ID#", "#IBLOCK_CODE#", "#IBLOCK_EXTERNAL_ID#", "#CODE#"),
			array(SITE_SERVER_NAME, SITE_DIR, $arIBlock["IBLOCK_TYPE_ID"], $arIBlock["ID"], $arIBlock["CODE"], $arIBlock["EXTERNAL_ID"], $arIBlock["CODE"]),
			strlen($arParams["IBLOCK_URL"])? trim($arParams["~IBLOCK_URL"]): $arIBlock["~LIST_PAGE_URL"]
		);
		$arIBlock["~LIST_PAGE_URL"] = preg_replace("'/+'s", "/", $arIBlock["~LIST_PAGE_URL"]);
		$arIBlock["LIST_PAGE_URL"] = htmlspecialcharsbx($arIBlock["~LIST_PAGE_URL"]);
		
		$arResult['IBLOCK'] = $arIBlock;
	}
}

$arResult['PRICES']['PRICE']['PRINT_VALUE'] = number_format($arResult['PROPERTIES']['PRICE']['VALUE'], 0, '.', ' ');
$arResult['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arResult['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

$arResult['DIRECT_CREDIT'] = array(
	'id' => $arResult["ID"],
	'price' => round($arResult["PRICES"]["price"]["DISCOUNT_VALUE"]),
	'count' => '1',
	'type' => $arResult["SECTION"]["NAME"],
	'name' => $arResult["NAME"],
	'id_order' => (time() + $arResult["ID"]),
);

if($arResult['CATALOG_WIDTH'] &&
	$arResult['CATALOG_HEIGHT'] &&
	$arResult['CATALOG_LENGTH'] &&
	$arResult['CATALOG_WEIGHT']){
	$arResult['CHECK_DELIVERY_TO_DOOR'] = "Y";
}

foreach($arResult["PROPERTIES"]["GIFT"]["VALUE"] as $key => $gifts)
{
	$res = CIBlockElement::GetByID($gifts);
	if($ar_res = $res->GetNext())
	{
		$price = CPrice::GetBasePrice($gifts);

		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["ID"] = $ar_res["ID"];
		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["NAME"] = $ar_res["NAME"];
		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["DESC"] = $arResult["PROPERTIES"]["GIFT"]["DESCRIPTION"][$key];
		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["PICTURE"] = CFile::ResizeImageGet($ar_res["PREVIEW_PICTURE"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["PRICE"] = $price["PRICE"];
		$arResult["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["URL"] = $ar_res["DETAIL_PAGE_URL"];

		$count = preg_replace("/[^0-9]/", '', $arResult["PROPERTIES"]["GIFT"]["DESCRIPTION"][$key]);
		if(is_numeric($count)){
			(float)$price["PRICE"] *= (int)$count;
		}
		$arResult["GIFT_SUM"] += (float)$price["PRICE"];
	}
}



global $APPLICATION;
$cp = $this->__component; // объект компонента

if (is_object($cp))
{
	$cp->arResult['META_TITLE'] = $arResult['PROPERTIES']['META_TITLE'];
	$cp->arResult['META_KEYWORDS'] = $arResult['PROPERTIES']['META_KEYWORDS'];
	$cp->arResult['META_DESCRIPTION'] = $arResult['PROPERTIES']['META_DESCRIPTION'];

	$arFilter = Array('IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'], 'ID' => $arResult['SECTION']['ID'], 'ACTIVE'=>'Y');
	$db_list = CIBlockSection::GetList(Array(), $arFilter, true, array("NAME","UF_*"));
	if($ar_result = $db_list->GetNext())
	{
		$cp->arResult['META_TITLE_SECTION'] = $ar_result['UF_META_TITLE_EL'];
		$cp->arResult['META_DESCRIPTION_SECTION'] = $ar_result['UF_META_DESC_SE'];
	}

	$cp->SetResultCacheKeys(
		array(
			'META_TITLE',
			'META_TITLE_SECTION',
			'META_KEYWORDS',
			'META_DESCRIPTION',
			'META_DESCRIPTION_SECTION'
		)
	);
	// сохраним их в копии arResult, с которой работает шаблон
	$arResult['META_TITLE'] = $cp->arResult['META_TITLE'];
	$arResult['META_TITLE_SECTION'] = $cp->arResult['META_TITLE_SECTION'];
	$arResult['META_KEYWORDS'] = $cp->arResult['META_KEYWORDS'];
	$arResult['META_DESCRIPTION'] = $cp->arResult['META_DESCRIPTION'];
	$arResult['META_DESCRIPTION_SECTION'] = $cp->arResult['META_DESCRIPTION_SECTION'];
}

$arDesc  = array();
foreach($arResult['PROPERTIES']['DESCRIPTION']['~VALUE'] as $desc => $meta){
	$arDesc[$arResult['PROPERTIES']['DESCRIPTION']['DESCRIPTION'][$desc]] = $meta;
}
if (isset($arDesc[SITE_ID])) {
	$arResult["DETAIL_TEXT"] = $arDesc[SITE_ID];
}

?>