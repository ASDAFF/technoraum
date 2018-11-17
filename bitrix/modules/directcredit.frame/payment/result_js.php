<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");

include(GetLangFileName(dirname(__FILE__).'/', '/payment.php'));

if (!CModule::IncludeModule("directcredit.frame"))
{
	$APPLICATION->ThrowException(GetMessage("NO_DC_MODULE"));
	exit;
}

if (!CModule::IncludeModule("sale"))
{
	$APPLICATION->ThrowException(GetMessage("NO_SALE_MODULE"));
	exit;
}

$oModule = new CDCMain();

if( !isset($_GET['order_id']) || !isset($_GET['dc_status']) || !check_bitrix_sessid('bitrix_sessid') ) 
{
	$APPLICATION->ThrowException(GetMessage("BAD_API_REQUEST"));
	exit;
}	

$sOutput = 'var dc_paystatus="OK";';
	
if ($_GET['dc_status'] == CDCMain::STATUS_SIGN)
	{
		if (!CSaleOrder::PayOrder(IntVal($_GET['order_id']), 'Y')) $sOutput = 'var dc_paystatus="PAYORDER_ERROR";';
	}
else if ($_GET['dc_status'] == CDCMain::STATUS_CANCEL)
	{
		if (!CSaleOrder::CancelOrder(IntVal($_GET['order_id']), 'Y')) $sOutput = 'var dc_paystatus="CANCELORDER_ERROR";';
	}


$APPLICATION->RestartBuffer();
header("Content-Type: text/plain");
header("Pragma: no-cache");
exit($sOutput);
?>