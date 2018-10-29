<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$mid = "altasib.geobase";

$incMod = CModule::IncludeModuleEx($mid);
if ($incMod == "0" || $incMod == "3")
	return false;

$arParams["CACHE_TIME"] = 0;

$arResult["POPUP_BACK"] = COption::GetOptionString($mid, "popup_back", "Y");
// Cookies
$arDataC = $APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE");
// selected cities codes
$arSelCodes = array();

$arResult["AUTODETECT_ENABLE"] = COption::GetOptionString($mid, "autodetect_enable", "Y");

if(!empty($_SESSION["ALTASIB_GEOBASE_CODE"]) || !empty($arDataC))
{
	$arResult["USER_CHOICE"] = CAltasibGeoBase::GetDataKladr();
	$arSelCodes[] = $arResult["USER_CHOICE"]["CODE"];
}
else
{
	if($arResult["AUTODETECT_ENABLE"] == "Y" || $arParams["RIGHT_ENABLE"] == "Y")
	{
		// On-line auto detection
		$arDataO = CAltasibGeoBase::GetCodeByAddr();

		if($arResult["AUTODETECT_ENABLE"] == "Y")
		{
			if($arDataO["CITY"]["NAME"] != GetMessage("ALTASIB_GEOBASE_KLADR_CITY_NAME")){
				$arResult["AUTODETECT"] = $arDataO;
				$arSelCodes[] = $arDataO["CODE"];
			}
		}
		else
		{
			if($arDataO["CITY"]["NAME"] != GetMessage("ALTASIB_GEOBASE_KLADR_CITY_NAME")){
				$arResult["AUTODETECT"] = $arDataO;
			}
		}
	}
}

$arCitySel = array();

$arCITY = CAltasibGeoBaseSelected::GetMoreCacheCities();

foreach($arCITY as $arCities)
{
	if(empty($arCities["R_FNAME"]))
	{
		$arRG = CAltasibGeoBase::GetRegionLang($arCities["CTR_CODE"], $arCities["R_ID"]);
		if(!empty($arRG["region_name"]))
		{
			if (LANG_CHARSET == "windows-1251")
				$arRG["region_name"] = iconv("UTF-8", LANG_CHARSET, $arRG["region_name"]);

			$arCities["R_FNAME"] = $arRG["region_name"];
		}
	}

	$bCNotRegCode = true;
	if(isset($arResult["USER_CHOICE"]["REGION"]) && isset($arResult["USER_CHOICE"]["REGION"]["CODE"]))
	{
		$bCNotRegCode = $arResult["USER_CHOICE"]["REGION"]["CODE"] != $arCities["C_CODE"];
	}

	if($arResult["USER_CHOICE"]["CODE"] != $arCities["C_CODE"]
		&& $arResult["USER_CHOICE"]["C_CODE"] != $arCities["C_CODE"]
		&& $arResult["USER_CHOICE"]["CITY_RU"] != $arCities["C_NAME"]
		&& $bCNotRegCode
	)
	{
		if($arResult["AUTODETECT_ENABLE"] == "Y")
		{
			if($arResult["AUTODETECT"]["CODE"] != $arCities["C_CODE"]
				&& $arResult["AUTODETECT"]["REGION"]["CODE"] != $arCities["C_CODE"]
				// && $arResult["AUTODETECT"]["REGION"]["FULL_NAME"] != $arCities["R_FNAME"]
			)
			{
				$arCitySel[] = $arCities;
			}
			elseif(
				$arResult["AUTODETECT"]["CODE"] != $arCities["C_CODE"]
				&& $arCities["R_ID"] == $arCities["C_CODE"]
			)
			{
				$arCitySel[] = $arCities;
			}
		}
		else
		{
			$arCitySel[] = $arCities;
		}
	}

	$arSelCodes[] = $arCities["C_CODE"];
}

$arResult["SELECTED"] = $arCitySel;

$arResult["SEL_CODES"] = $arSelCodes;

$arResult["SOCRS"] = explode(";", GetMessage("ALTASIB_GEOBASE_SOCRS"));

if($arResult["AUTODETECT_ENABLE"] == "Y")
	$arResult["auto"] = CAltasibGeoBase::GetAddres();

$arResult["ONLY_SELECT"] = COption::GetOptionString($mid, "only_select_cities", "N");
$arResult["MODE_LOCATION"] = strtoupper(COption::GetOptionString($mid, "mode_location", "CITIES"));

$arCurr = CAltasibGeoBase::GetCurrency($use_func=true, $reload=false);

if(CAltasibGeoBase::CheckCountry($arCurr["country"]))
	$arResult["RU_ENABLE"] = "Y";
else
	$arResult["RU_ENABLE"] = "N";

$arResult["SHOW_SMALL"] = ((COption::GetOptionString($mid, "your_city_mode", "large")=="small" || $arParams["SMALL_ENABLE"]=="Y")
							&& empty($_SESSION["ALTASIB_GEOBASE_CODE"]) && empty($arDataC) ? "Y" : "N");

///Mobile Detect///

$checkType = CAltasibGeoBase::DeviceIdentification();

///////////////////

/**
 * @var $this CBitrixComponent
 */

if($checkType == "mobile" || $checkType == "pda")
	$this->IncludeComponentTemplate("mobile");
else
	$this->IncludeComponentTemplate();

?>