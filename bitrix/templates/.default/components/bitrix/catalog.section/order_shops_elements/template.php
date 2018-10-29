<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

unset($_SESSION["order_shops_items"]);
$i = 0;
foreach($arResult["ITEMS"] as $item)
{
	$_SESSION["order_shops_items"][$i] = array
	(
		"name" => $item["NAME"], 
		"phone" => $item["PROPERTIES"]["PHONE"]["VALUE"],
		"time" => $item["PROPERTIES"]["TIME"]["VALUE"],
		"day" => $item["PROPERTIES"]["DAY"]["VALUE"],
		"map" => $item["PROPERTIES"]["MAP_CORDS"]["VALUE"]
	);
	$i++;
}
?>