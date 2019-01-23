<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

foreach($arResult['BASKET_ITEM_RENDER_DATA'] as &$cols){
    foreach($cols['COLUMN_LIST'] as &$col){
        if($col['CODE'] != "PROPERTY_GIFT_VALUE")
            continue;

        $ids = explode(",",$col['VALUE']);
        $arLink = array();
        foreach($ids as $last => $id){
            $res = CIBlockElement::GetByID($id);
            if($ar_res = $res->GetNext()){
                $arLink[$last]['LINK'] = "<a href='$ar_res[DETAIL_PAGE_URL]'>$ar_res[NAME]</a>";
                $arLink[$last]['IS_LAST'] = (count($ids) - 1 == $last) ? true : false;
            }
            $col['VALUE'] = $arLink;
            $col['IS_LINK'] = true;
            unset($col['IS_TEXT']);
            unset($col['IS_HTML']);
        }
    }
}

foreach($arResult["GRID"]["ROWS"] as &$item){
    $price = round($item[PRICE]);
    $id_order = (time() + $item["PRODUCT_ID"]);

    $res = CIBlockElement::GetByID($item[PRODUCT_ID]);
    if($ar_res = $res->GetNext()){
        $arFilter = array('IBLOCK_ID' => $ar_res['IBLOCK_ID'], "ID" => $ar_res['IBLOCK_SECTION_ID']); // выберет потомков без учета активности
        $rsSect = CIBlockSection::GetList(false,$arFilter);
        if($arSect = $rsSect->GetNext())
        {
            $arResult['DIRECT_CREDIT'][] = array(
                'id' => "$item[PRODUCT_ID]",
                'price' => "$price",
                'count' => "$item[QUANTITY]",
                'type' => $arSect["NAME"],
                'name' => $item["NAME"],
                'id_order' => "$id_order",
            );
        }
    }
}