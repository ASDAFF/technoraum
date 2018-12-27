<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(ADMIN_SECTION !== true)
{
	$jQEn = COption::GetOptionString("altasib.geobase", "enable_jquery", "ON");
	if($jQEn == "ON")
		CJSCore::Init(array('jquery'));
	elseif($jQEn == "2")
		CJSCore::Init(array('jquery2'));
}

$colorScheme = COption::GetOptionString("altasib.geobase", "color_scheme", "BRIGHT");
$colorTheme = COption::GetOptionString("altasib.geobase", "color_theme", "");
$colorOth = COption::GetOptionString("altasib.geobase", "color_other", "#0097f6");

if(!empty($colorOth) || !empty($colorTheme))
{
	$this->Generate($colorOth, $colorTheme, $colorScheme, $this->__template->__folder, GetMessage("AGB_STYLE_GENERATE"));
}
?>