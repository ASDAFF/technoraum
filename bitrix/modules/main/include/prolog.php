<?
require_once(dirname(__FILE__)."/../bx_root.php");

if (file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/html_pages/.enabled"))
{
	require_once(dirname(__FILE__)."/../lib/composite/responder.php");
	Bitrix\Main\Composite\Responder::respond();
}

require_once(dirname(__FILE__)."/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_after.php");

unset($_SESSION["DISCOUNT"]);
CModule::IncludeModule("sale");
$arFilter = Array("USER_ID" => $USER->GetID() , "PAYED" => "Y");
$db_sales = CSaleOrder::GetList(array(), $arFilter);

$summ = 0;
while ($ar_sales = $db_sales->Fetch())
{
   $summ += $ar_sales["PRICE"];
}

if($summ > 250000)
	$percent = 10;
elseif($summ > 200000)
	$percent = 9;
elseif($summ > 150000)
	$percent = 8;
elseif($summ > 100000)
	$percent = 7;
elseif($summ > 50000)
	$percent = 6;
elseif($summ > 30000)
	$percent = 5;
elseif($summ > 20000)
	$percent = 4;
elseif($summ > 10000)
	$percent = 3;

$_SESSION["DISCOUNT"] = $percent;
unset($summ);
unset($arFilter);
unset($db_sales);
