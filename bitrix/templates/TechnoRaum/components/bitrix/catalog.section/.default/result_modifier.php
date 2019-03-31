<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

foreach($arResult["ITEMS"] as &$item){
    $price = round($item[PRICES][price][DISCOUNT_VALUE]);
    $id_order = (time() + $item["ID"]);

    $item['DIRECT_CREDIT'] = array(
        'id' => "$item[ID]",
        'price' => "$price",
        'count' => '1',
        'type' => $arResult["NAME"],
        'name' => $item["NAME"],
        'id_order' => "$id_order",
    );


    foreach($item["PROPERTIES"]["GIFT"]["VALUE"] as $key => $gifts)
    {
        $res = CIBlockElement::GetByID($gifts);
        if($ar_res = $res->GetNext())
        {
            $price = CPrice::GetBasePrice($gifts);

            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["ID"] = $ar_res["ID"];
            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["NAME"] = $ar_res["NAME"];
            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["DESC"] = $item["PROPERTIES"]["GIFT"]["DESCRIPTION"][$key];
            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["PICTURE"] = CFile::ResizeImageGet($ar_res["PREVIEW_PICTURE"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["PRICE"] = $price["PRICE"];
            $item["PROPERTIES"]["GIFT"]["ITEM"][$ar_res['ID']]["URL"] = $ar_res["DETAIL_PAGE_URL"];

            $count = preg_replace("/[^0-9]/", '', $item["PROPERTIES"]["GIFT"]["DESCRIPTION"][$key]);
            if(is_numeric($count)){
                (float)$price["PRICE"] *= (int)$count;
            }
            $item["GIFT_SUM"] += (float)$price["PRICE"];
        }
    }
}


global $APPLICATION;
$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['META_TITLE'] = $arResult["UF_META_TITLE"];
    $cp->arResult['META_KEYWORDS'] = $arResult["UF_META_KEYWORDS"];
    $cp->arResult['META_DESCRIPTION'] = $arResult["UF_META_DESCRIPTION"];

    $cp->SetResultCacheKeys(array('META_TITLE','META_KEYWORDS','META_DESCRIPTION'));
    // сохраним их в копии arResult, с которой работает шаблон
    $arResult['META_TITLE'] = $cp->arResult['META_TITLE'];
    $arResult['META_KEYWORDS'] = $cp->arResult['META_KEYWORDS'];
    $arResult['META_DESCRIPTION'] = $cp->arResult['META_DESCRIPTION'];

}

