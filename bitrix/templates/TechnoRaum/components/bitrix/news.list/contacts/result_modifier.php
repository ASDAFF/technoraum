<?php

$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arResult['ID'], "CODE" => "CITY"));
while($enum_fields = $property_enums->GetNext())
{
    $arResult['CITYS'][] = $enum_fields;
}

if($_SESSION["ALTASIB_GEOBASE_CODE"]["CODE"]){
    $arResult["CURRENT_CITY_CODE"] = $_SESSION["ALTASIB_GEOBASE_CODE"]["CODE"];
}

if(count($arResult["ITEMS"]) > 0)
$arResult["ITEMS_JS"] = htmlspecialchars(\Bitrix\Main\Web\Json::encode($arResult["ITEMS"]), ENT_QUOTES);


