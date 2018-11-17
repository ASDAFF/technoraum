<?
IncludeModuleLangFile(__FILE__);
global $APPLICATION;
$sModuleId = 'directcredit.frame';

if (!CModule::IncludeModule("sale"))
{
	$APPLICATION->ThrowException(GetMessage("NO_SALE_MODULE"));
	return false;
}
if (!CModule::IncludeModule("catalog"))
{
	$APPLICATION->ThrowException(GetMessage("NO_CATALOG_MODULE"));
	return false;
}

CModule::AddAutoloadClasses(
	$sModuleId,
	array(
		"CDCMain" => "general/dcmain.php",
	)
);
?>