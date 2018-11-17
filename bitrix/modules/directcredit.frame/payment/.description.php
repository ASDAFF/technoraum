<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

$psTitle = GetMessage("DC_TITLE");
$psDescription = GetMessage("DC_DDESCR");

$arPSCorrespondence = array(
	"SHOP_ID" => array(
		"NAME" => GetMessage("SHOP_ID"),
		"DESCR" => GetMessage("SHOP_ID_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"ORDER_ID" => array(
		"NAME" => GetMessage("ORDER_ID"),
		"DESCR" => GetMessage("ORDER_ID_DESCR"),
		"VALUE" => "ID",
		"TYPE" => "ORDER"
	),
	"PHONE_MOBILE" => array(
		"NAME" => GetMessage("PHONE_MOBILE"),
		"DESCR" => GetMessage("PHONE_MOBILE_DESCR"),
		"VALUE" => "PHONE",
		"TYPE" => "PROPERTY"
	),
);
?>