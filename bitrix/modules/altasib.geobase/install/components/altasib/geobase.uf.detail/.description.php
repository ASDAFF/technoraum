<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2015 ALTASIB
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
	"NAME" => GetMessage("ALTASIB_GEOBASE_NAME"),
	"DESCRIPTION" => GetMessage("ALTASIB_GEOBASE_DESC"),
	"ICON" => "/images/icon.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "IS-MARKET.RU",
		"NAME" => GetMessage("ALTASIB_DESC_SECTION_NAME"),
		"CHILD" => array(
			"ID" => "altasib_serv",
			"NAME" => GetMessage("ALTASIB_GEOBASE_SERVICE"),
			"SORT" => 30,
		),
	),
);
?>