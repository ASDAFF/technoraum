<?
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$incMod = CModule::IncludeModuleEx("altasib.geobase");
if ($incMod == '0')
	return false;
elseif ($incMod == '3')
	return false;
else
	echo CAltasibGeoBase::GetCitySuggest();

if(isset($_REQUEST['save']) && $_REQUEST['save'] == 'Y')
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>