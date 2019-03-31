<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if($arTitles = $arResult["SECTION"]["UF_META_TITLE"]){
    $arTitle = array();
    foreach($arTitles as $title){
        $title = explode(";",$title);
        $arTitle[$title[0]] = $title[1];
    }
    if (isset($arTitle[SITE_ID])) {
        $APPLICATION->SetTitle($arTitle[SITE_ID]);
        $APPLICATION->SetPageProperty('title', $arTitle[SITE_ID]);
    }
}