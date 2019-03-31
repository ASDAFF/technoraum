<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(CModule::IncludeModule("iblock"))
{
    foreach($arResult as $key => $arMenu){

        if($code = str_replace(array("/catalog/","/"),"",$arMenu['LINK'])){

            $arFilter = Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y','CODE' => $code);
            $db_list = CIBlockSection::GetList(Array(), $arFilter, true, array('NAME','UF_SITE_ID'));
            if($ar_result = $db_list->GetNext())
            {
                if($arSites = $ar_result['UF_SITE_ID']){
                    $arSite = array_map(function($site){
                        $arSites = CUserFieldEnum::GetList(array(), array("ID" => $site));
                        if($arSite = $arSites->GetNext())
                            return $arSite['XML_ID'];
                    },$arSites);
                    if (!in_array(SITE_ID, $arSite)) {
                        unset($arResult[$key]);
                    }
                }
            }
        }
    }
}
