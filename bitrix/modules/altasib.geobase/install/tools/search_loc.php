<?
define("STOP_STATISTICS", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

global $APPLICATION;

$arResult = array();

if(\Bitrix\Main\Loader::includeModule('sale'))
{
	if(!empty($_REQUEST["search"]) && is_string($_REQUEST["search"]))
	{
		$search = $APPLICATION->UnJSEscape($_REQUEST["search"]);
		$search = str_replace('%', '', $search);

		$arParams = array();
		$params = explode(",", $_REQUEST["params"]);
		foreach($params as $param)
		{
			list($key, $val) = explode(":", $param);
			$arParams[$key] = $val;
		}

		$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("city", $arParams["siteId"]);
		$filter["~CITY_NAME"] = $search."%";
		$filter["LID"] = LANGUAGE_ID;

		$rsLocsList = CSaleLocation::GetList(
			array(
				"CITY_NAME_LANG" => "ASC",
				"COUNTRY_NAME_LANG" => "ASC",
				"SORT" => "ASC",
			),
			$filter,
			false,
			array("nTopCount" => 1),
			array(
				"ID", "CODE", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG"
			)
		);

		while($arCity = $rsLocsList->GetNext())
		{
			$arResult[] = array(
				"ID" => $arCity["ID"],
				"NAME" => $arCity["CITY_NAME"],
				"CODE" => $arCity["CODE"],
				"REGION_NAME" => $arCity["REGION_NAME_LANG"],
				"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
			);
		}

		$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("region", $arParams["siteId"]);
		$filter["~REGION_NAME"] = $search."%";
		$filter["LID"] = LANGUAGE_ID;
		$filter["CITY_ID"] = false;
		$rsLocsList = CSaleLocation::GetList(
			array(
				"CITY_NAME_LANG" => "ASC",
				"COUNTRY_NAME_LANG" => "ASC",
				"SORT" => "ASC",
			),
			$filter,
			false,
			array("nTopCount" => 1),
			array(
				"ID", "CODE", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG"
			)
		);
		while($arCity = $rsLocsList->GetNext())
		{
			$arResult[] = array(
				"ID" => $arCity["ID"],
				"CODE" => $arCity["CODE"],
				"NAME" => "",
				"REGION_NAME" => $arCity["REGION_NAME_LANG"],
				"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
			);
		}

		$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("country", $arParams["siteId"]);
		$filter["~COUNTRY_NAME"] = $search."%";
		$filter["LID"] = LANGUAGE_ID;
		$filter["CITY_ID"] = false;
		$filter["REGION_ID"] = false;
		$rsLocsList = CSaleLocation::GetList(
			array(
				"COUNTRY_NAME_LANG" => "ASC",
				"SORT" => "ASC",
			),
			$filter,
			false,
			array("nTopCount" => 1),
			array(
				"ID", "CODE", "COUNTRY_NAME_LANG"
			)
		);
		while($arCity = $rsLocsList->GetNext())
		{
			$arResult[] = array(
				"ID" => $arCity["ID"],
				"CODE" => $arCity["CODE"],
				"NAME" => "",
				"REGION_NAME" => "",
				"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
			);
		}

		if(empty($arResult))
		{
			$arLocs = CAltasibGeoBase::SearchLocation($search, false, false, LANGUAGE_ID);
			if(!empty($arLocs) && is_array($arLocs) && count($arLocs) == 1)
				$arLocs = $arLocs[0];

			$arResult[] = array(
				"ID" => $arLocs["ID"],
				"NAME" => $arLocs["NAME"],
				"CODE" => $arLocs["CODE"],
				"REGION_NAME" => $arLocs["REGION_NAME"],
				"COUNTRY_NAME" => $arLocs["COUNTRY_NAME"],
			);
		}

		if(empty($arResult))
		{
			$arLocs = CAltasibGeoBase::SearchLocation(false, false, $search, LANGUAGE_ID); // region
			if(!empty($arLocs) && is_array($arLocs) && count($arLocs) == 1)
				$arLocs = $arLocs[0];

			$arResult[] = array(
				"ID" => $arLocs["ID"],
				"NAME" => $arLocs["NAME"],
				"CODE" => $arLocs["CODE"],
				"REGION_NAME" => $arLocs["REGION_NAME"],
				"COUNTRY_NAME" => $arLocs["COUNTRY_NAME"],
			);
		}
	}
}

echo CUtil::PhpToJSObject($arResult);

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");
die();

?>