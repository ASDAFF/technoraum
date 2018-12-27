<?
// Templates of components
$arCTpl = CComponentUtil::GetTemplatesList('altasib:geobase.your.city');
$sTplYC = ".default,";
foreach($arCTpl as $tpl)
{
	if($tpl["NAME"] == ".default")
		$sTplYC = $tpl["NAME"].','.$tpl["TEMPLATE"];
}

$arCTpl = CComponentUtil::GetTemplatesList('altasib:geobase.your.city');
$sTplSC = ".default,";
foreach($arCTpl as $tpl)
{
	if($tpl["NAME"] == ".default")
		$sTplSC = $tpl["NAME"].','.$tpl["TEMPLATE"];
}
// array site templates
$rsData = CSiteTemplate::GetList(array($by => $order), array(), array("ID", "NAME"));
while($arTpRes = $rsData->Fetch())
{
	$sTpSite .= $arTpRes["ID"].",";
}
// sites array
$sites = CSite::GetList($by="sort", $order="desc", Array("ACTIVE" => "Y"));
while($site= $sites->Fetch())
{
	$siteDef .= $site["ID"].",";
}
// default location
$rusID = "";
if(CModule::IncludeModule("sale"))
{
	$rus1 = GetMessage("ALTASIB_GEOBASE_RUSSIA");
	$rus2 = GetMessage("ALTASIB_GEOBASE_RF");
	$dbCl = CSaleLocation::GetCountryList(Array("NAME_LANG"=>"ASC"), Array(), LANG);
	while($arList = $dbCl->Fetch())
	{
		if(in_array($rus1, $arList) || in_array($rus2, $arList))
		{
			$rusID = $arList["ID"]; break;
		}
	}
}

$altasib_geobase_default_option = array(
	"your_city_templates" => $sTplYC,
	"select_city_templates" => $sTplSC,
	"template" => $sTpSite,
	"sites" => $siteDef,
	"def_location" => $rusID,
);
