<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$incMod = CModule::IncludeModuleEx("altasib.geobase");
if ($incMod == '0' || $incMod == '3')
	return false;

$arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
if(empty($arSelCity) && $arParams["FIND_NEAREST"] != "N")
{
	if(empty($arParams["NEAREST_MODE"]))
		$arParams["NEAREST_MODE"] = "all";

	$arParams["NEAREST_USER_CHOICE"] = ($arParams["NEAREST_USER_CHOICE"] == "N" ? false : true);

	$arSelCity = CAltasibGeoBaseSelected::GetNearestCityFromSelected($arParams["NEAREST_MODE"], $arParams["NEAREST_USER_CHOICE"]);
}

if(!empty($arSelCity) && count($arSelCity) > 0)
{
	$arUFields = CAltasibGeoBaseSelected::GetFieldsCity($arSelCity["ID"],
		($arParams["NO_NAME"] == "Y" ? false : LANGUAGE_ID));
}
else
{
	$arUFields = CAltasibGeoBaseSelected::GetFieldsCity(0,
		($arParams["NO_NAME"] == "Y" ? false : LANGUAGE_ID));
}

if(!empty($arSelCity) && count($arSelCity) > 0)
{
	if(!empty($arSelCity["C_NAME"]) && strlen($arSelCity["C_NAME"]) > 0)
		$arResult["SELECT_CITY"] = $arSelCity["C_NAME"];
	elseif(!empty($arSelCity["C_NAME_EN"]) && strlen($arSelCity["C_NAME_EN"]) > 0)
		$arResult["SELECT_CITY"] = $arSelCity["C_NAME_EN"];

	if(!empty($arSelCity["R_FNAME"]) && strlen($arSelCity["R_FNAME"]) > 0)
		$arResult["SELECT_REGION"] = $arSelCity["R_FNAME"];

	if(!empty($arSelCity["CTR_NAME_RU"]) && strlen($arSelCity["CTR_NAME_RU"]) > 0)
		$arResult["SELECT_COUNTRY"] = $arSelCity["CTR_NAME_RU"];
	else
		$arResult["SELECT_COUNTRY"] = GetMessage("ALTASIB_SHOW_RUSSIA");

	if(!empty($arSelCity["CTR_CODE"]) && strlen($arSelCity["CTR_CODE"]) > 0)
		$arResult["SELECT_COUNTRY_CODE"] = $arSelCity["CTR_CODE"];
	else
		$arResult["SELECT_COUNTRY_CODE"] = GetMessage("ALTASIB_SHOW_RU");
	
	if(($arParams["SHOW_COORDINATES"] == "Y" || $arParams["SHOW_COUNTY"] == "Y")
		&& (empty($arSelCity["BREADTH_CITY"]) || empty($arSelCity["COUNTY_NAME"]))
	)
	{
		$arSC = array();
		$arSC["CITY"] = $arSelCity["C_NAME"];
		$arSC["REGION"] = $arSelCity["R_NAME"];
		$arSC["STRICT"] = false;

		if(!empty($arSelCity["CTR_CODE"]) && $arSelCity["CTR_CODE"] != "RU")
		{
			$arSC["MM"] = true;
			$arSC["CTR_CODE"] = $arSelCity["CTR_CODE"];
		}
		$arRAc = CAltasibGeoBaseSelected::GetArReplace();
		if(isset($arRAc[$arSelCity["R_NAME"]]))
		{
			$arSC["REGION"] = $arRAc[$arSelCity["R_NAME"]];
			$arSC["STRICT"] = true;
		}

		$rsCt = CAltasibGeoBase::GetCitiesIPGB($arSC);
		if($arCts = $rsCt->Fetch())
		{
			$arSelCity["COUNTY_NAME"] = $arCts["COUNTY_NAME"];
			$arSelCity["BREADTH_CITY"] = $arCts["BREADTH_CITY"];
			$arSelCity["LONGITUDE_CITY"] = $arCts["LONGITUDE_CITY"];
		}
	}

	if(!empty($arSelCity["COUNTY_NAME"]) && strlen($arSelCity["COUNTY_NAME"]) > 0)
		$arResult["SELECT_COUNTY"] = $arSelCity["COUNTY_NAME"];

	if(!empty($arSelCity["BREADTH_CITY"]) && strlen($arSelCity["BREADTH_CITY"]) > 0)
		$arResult["SELECT_LATITUDE"] = $arSelCity["BREADTH_CITY"];

	if(!empty($arSelCity["LONGITUDE_CITY"]) && strlen($arSelCity["LONGITUDE_CITY"]) > 0)
		$arResult["SELECT_LONGITUDE"] = $arSelCity["LONGITUDE_CITY"];
}

if(empty($arParams["USERFIELD_LIST"]))
	$arParams["USERFIELD_LIST"] = array();

if(!empty($arUFields) && !empty($arParams["USERFIELD_LIST"]))
{
	foreach($arUFields as $code=>$arFld)
	{
		if(in_array($arFld["FIELD_NAME"], $arParams["USERFIELD_LIST"]))
		{
			if($arFld["USER_TYPE"]["USER_TYPE_ID"] == "iblock_element")
			{
				$arElements = CAltasibGeoBaseSelected::GetElementList($arFld["SETTINGS"]["IBLOCK_ID"], $arFld["VALUE"]);
				$arFld["DISPLAY_VALUE"] = $arElements;
			}
			elseif($arFld["USER_TYPE"]["USER_TYPE_ID"] == "iblock_section")
			{
				$arSections = CAltasibGeoBaseSelected::GetSectionList($arFld["SETTINGS"]["IBLOCK_ID"], $arFld["VALUE"]);
				$arFld["DISPLAY_VALUE"] = $arSections;
			}
			elseif($arFld["USER_TYPE"]["USER_TYPE_ID"] == "enumeration")
			{
				$arEnums = CAltasibGeoBaseSelected::GetEnumList($arFld["ID"], $arFld["VALUE"]);
				$arFld["DISPLAY_VALUE"] = $arEnums;
			}
			$arResult["UF"][] = $arFld;
		}
	}
}

$this->IncludeComponentTemplate();
?>