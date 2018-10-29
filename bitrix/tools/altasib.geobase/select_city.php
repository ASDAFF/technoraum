<?define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$incMod = CModule::IncludeModuleEx("altasib.geobase");
if($incMod == '0')
	return false;
elseif ($incMod == '3')
	return false;
else
{
	if(isset($_POST['show_select']) && $_POST['show_select'] == 'Y')
	{
		echo CAltasibGeoBase::GetSelectCity();
	}
}
?>