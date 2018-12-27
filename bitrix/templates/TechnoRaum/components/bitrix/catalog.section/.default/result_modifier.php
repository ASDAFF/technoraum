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
}