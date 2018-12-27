<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$mid = "altasib.geobase";

$incMod = CModule::IncludeModuleEx($mid);
if ($incMod == '0' || $incMod == '3')
	return false;

if($arParams["CHECK_DATA"] == "Y")
{
	if(isset($_SESSION["ALTASIB_GEOBASE_CODE"]))
	{
		return;
	}
	elseif(COption::GetOptionString($mid, "set_cookie", "Y") == "Y")
	{
		$sData = $APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE");
		$arDataS = CAltasibGeoBase::deCodeJSON($sData);
		if(!empty($arDataS))
			return;
	}
}

$arResult = CAltasibGeoBase::GetDataKladr();
$arResult["auto"] = CAltasibGeoBase::GetAddres();

$arResult["REGION_DISABLE"] = COption::GetOptionString($mid, 'region_disable', 'N');
$arResult["POPUP_BACK"] = COption::GetOptionString($mid, "popup_back", "Y");
$arResult['MODE_LOCATION'] = strtoupper(COption::GetOptionString($mid, "mode_location", "CITIES"));

////Mobile detect////

$checkType = CAltasibGeoBase::DeviceIdentification();

/////////////////////

if ($checkType == 'mobile' || $checkType == 'pda')
{
	$this->IncludeComponentTemplate("mobile");
}
else
	$this->IncludeComponentTemplate();
?>