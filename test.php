<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog.store",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"MAP_TYPE" => "0",
		"PHONE" => "Y",
		"SCHEDULE" => "N",
		"SEF_MODE" => "N",
		"SET_TITLE" => "Y",
		"TITLE" => "Список складов с подробной информацией"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>