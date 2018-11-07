<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Доставка до двери",
	"DESCRIPTION" => "",
	"ICON" => "/images/sdek_pickup.png",
	"CACHE_PATH" => "Y",
	"SORT" => 40,
	"PATH" => array(
		"ID" => "e-store",
		"CHILD" => array(
			"ID" => "nbrains",
			"NAME" => "Доставка до двери",
			"SORT" => 30,
		),
	),
);
?>