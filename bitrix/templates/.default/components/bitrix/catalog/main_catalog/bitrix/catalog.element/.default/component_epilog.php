<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;

$meta_array = function($arMeta){
    $arMetas = array();
    foreach($arMeta['VALUE'] as $desc => $meta){
        $arMetas[$arMeta['DESCRIPTION'][$desc]] = $meta;
    }
    return $arMetas;
};

$meta_section_array = function($arMeta){
    $arMetas = array();
    foreach($arMeta as $meta){
        $metas = explode(";",$meta);
        $arMetas[$metas[0]] = $metas[1];
    }
    return $arMetas;
};

$arTitle = $meta_array($arResult['META_TITLE']);
if (isset($arTitle[SITE_ID])) {
    $APPLICATION->SetTitle($arTitle[SITE_ID]);
    $APPLICATION->SetPageProperty('title', $arTitle[SITE_ID]);
}else{
    $arTitleSection = $meta_section_array($arResult['META_TITLE_SECTION']);
    if (isset($arTitleSection[SITE_ID])){
        preg_match_all("|{(.*?)}|",$arTitleSection[SITE_ID],$matches);
        foreach($matches[1] as $key => $match){
            if(isset($arResult[$match])){
                $arTitleSection[SITE_ID] = str_replace($matches[0][$key],$arResult[$match],$arTitleSection[SITE_ID]);
            }
        }
        $APPLICATION->SetTitle($arTitleSection[SITE_ID]);
        $APPLICATION->SetPageProperty('title', $arTitleSection[SITE_ID]);
    }else{
        $APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]);
        $APPLICATION->SetPageProperty('title', $arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]);
    }
}

$arKeywords = $meta_array($arResult["META_KEYWORDS"]);
if (isset($arKeywords[SITE_ID])) {
    $APPLICATION->SetPageProperty('keywords', $arKeywords[SITE_ID]);
}else{
    $APPLICATION->SetPageProperty('keywords', $arResult["IPROPERTY_VALUES"]["ELEMENT_META_KEYWORDS"]);
}

$arDescription = $meta_array($arResult["META_DESCRIPTION"]);
if (isset($arDescription[SITE_ID])) {
    $APPLICATION->SetPageProperty('description', $arDescription[SITE_ID]);
}else{
    $arDescriptionSection = $meta_section_array($arResult['META_DESCRIPTION_SECTION']);
    if (isset($arDescriptionSection[SITE_ID])){
        preg_match_all("|{(.*?)}|",$arDescriptionSection[SITE_ID],$matches);
        foreach($matches[1] as $key => $match){
            if(isset($arResult[$match])){
                $arDescriptionSection[SITE_ID] = str_replace($matches[0][$key],$arResult[$match],$arDescriptionSection[SITE_ID]);
            }
        }
        $APPLICATION->SetPageProperty('description', $arDescriptionSection[SITE_ID]);
    }else{
        $APPLICATION->SetPageProperty('description', $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]);
    }

}
