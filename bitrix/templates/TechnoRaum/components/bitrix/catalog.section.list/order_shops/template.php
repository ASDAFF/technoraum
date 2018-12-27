<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

echo "XXXX";
$i = 0;
foreach($arResult["SECTIONS"] as $section)
{
	$order_shops_sections[$i] = array("id" => $section["ID"] , "name" => $section["NAME"]);
	$i++;
}

$_SESSION["order_shops_sections"] = $order_shops_sections;

?>