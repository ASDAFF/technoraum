<?if(!check_bitrix_sessid()) return;?>

<?
include(GetLangFileName(dirname(__FILE__)."/", "/step.php"));

if (!CModule::IncludeModule("directcredit.frame"))
{
    $APPLICATION->ThrowException(GetMessage("NO_DC_MODULE"));
    return false;
}

if (!CModule::IncludeModule("main"))
{
    $APPLICATION->ThrowException(GetMessage("NO_MAIN_MODULE"));
    return false;
}

if (!CModule::IncludeModule("sale"))
{
    $APPLICATION->ThrowException(GetMessage("NO_SALE_MODULE"));
    return false;
}

#1

$ID_pay = CSalePaySystem::Add(array('CURRENCY' => "", 'NAME' => GetMessage("NAME_PAY_SYSTEM"), "ACTIVE" => "Y", "SORT" => 500, "DESCRIPTION" => GetMessage("DESCRIPTION_PAY_SYSTEM")));

#2

$arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/directcredit.frame/images/icon.jpg");

#3

$int = CSalePaySystemAction::Add(
	array(
		"PAY_SYSTEM_ID" => $ID_pay, 
		"PERSON_TYPE_ID" => 1, 
		"ACTION_FILE" => "/bitrix/php_interface/include/sale_payment/directcredit.frame",
		"RESULT_FILE" => "",
		"NAME" => GetMessage("TYPE_NAME_PAY_SYSTEM"), 
		"NEW_WINDOW" => "N", 
		"PARAMS" => 'a:3:{s:7:"SHOP_ID";a:2:{s:4:"TYPE";s:0:"";s:5:"VALUE";s:0:"";}s:8:"ORDER_ID";a:2:{s:4:"TYPE";s:5:"ORDER";s:5:"VALUE";s:2:"ID";}s:12:"PHONE_MOBILE";a:2:{s:4:"TYPE";s:8:"PROPERTY";s:5:"VALUE";s:5:"PHONE";}}',
		"ENCODING" => "utf-8", 
		"LOGOTIP" => $arFile,
		"TARIF" => "",
		"HAVE_PAYMENT" => "N",
		"HAVE_ACTION" => "N",
		"HAVE_RESULT" => "N",
		"HAVE_PREPAY" => "N",
		"HAVE_RESULT_RECEIVE" => "N"
	)
);

echo CAdminMessage::ShowNote(GetMessage("INSTALL"));
?>