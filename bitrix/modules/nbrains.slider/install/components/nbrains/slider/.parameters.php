<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];

$arSorts = array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
		"ID"=>GetMessage("T_IBLOCK_DESC_FID"),
		"NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
		"ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
		"SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
		"TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP")
	);

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>(isset($arCurrentValues["IBLOCK_ID"])?$arCurrentValues["IBLOCK_ID"]:$arCurrentValues["ID"])));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}
$arArrowsHidden = array(
	'true' => GetMessage("NBRAINS_YES"),
	'false' => GetMessage("NBRAINS_NO")
);
$arBtnSlideControl = array(
	'top' => GetMessage("NBRAINS_TOP"),
	'bottom'=> GetMessage("NBRAINS_BOTTOM")
);
$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
		),
		"WIDTH" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_WIDTH_SLIDER"),
			"TYPE" => "STRING",
			"DEFAULT" => "860",
		),
		"HEIGHT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_HEIGHT_SLIDER"),
			"TYPE" => "STRING",
			"DEFAULT" => "400",
		),
		"TIME_SLIDE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_TIME_SLIDE"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"RIGHT_PX_TEXT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_RIGHT_PX_TEXT"),
			"TYPE" => "STRING",
			"DEFAULT" => "70",
		),
		"TOP_PX_TEXT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_TOP_PX_TEXT"),
			"TYPE" => "STRING",
			"DEFAULT" => "135",
		),
		"HIDDEN_ARROWS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_HIDDEN_ARROWS_DESC"),
			"TYPE" => "LIST",
			"DEFAULT" => "false",
			"VALUES" => $arArrowsHidden,
			"ADDITIONAL_VALUES" => "N",
		),
		"BTN_SLIDE_CONTROL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_PLACE_CONTROL_CHENGE_BTN"),
			"TYPE" => "LIST",
			"DEFAULT" => "bottom",
			"VALUES" => $arBtnSlideControl,
			"ADDITIONAL_VALUES" => "N",
		),
		"PROGRESS_BAR_HEIGHT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_PROGRESS_BAR_HEIGHT"),
			"TYPE" => "STRING",
			"DEFAULT" => "4",
		),
		"PROGRESS_BAR_COLOR" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_PROGRESS_BAR_COLOR"),
			"TYPE" => "STRING",
			"DEFAULT" => "8FBB3F",
		),
		"PROGRESS_BAR_PLACE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NBRAINS_PROGRESS_BAR_PLACE"),
			"TYPE" => "LIST",
			"DEFAULT" => "bottom",
			"VALUES" => $arBtnSlideControl,
			"ADDITIONAL_VALUES" => "N",
		),
		"SORT_BY1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "N",
		),
		"SORT_ORDER1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "N",
		),
		"SORT_BY2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD2"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "N",
		),
		"SORT_ORDER2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "N",
		),
		"CHECK_DATES" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_CHECK_DATES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

