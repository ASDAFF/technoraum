<?php
foreach($arResult["ITEMS"] as $key => $arItem){
    if($arItem['PROPERTIES']['SITE_ID']['VALUE']){
        if (!in_array(SITE_ID, $arItem['PROPERTIES']['SITE_ID']['VALUE_XML_ID'])) {
            unset($arResult["ITEMS"][$key]);
        }
    }
}