<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

if($_REQUEST['check']){

    $name = trim(strip_tags(iconv("UTF-8" , "Windows-1251" , $_REQUEST['name'])));
    $product_name = trim(strip_tags(iconv("UTF-8" , "Windows-1251" , $_REQUEST['product_name'])));
    $link = trim(strip_tags($_REQUEST['link']));
    $count = trim(strip_tags($_REQUEST['count']));
    $phone = trim(strip_tags($_REQUEST['tel']));
    $price = $_REQUEST['price'];

    $el = new CIBlockElement;

    $PROP = array();
    $PROP[PRODUCT] = $product_name;
    $PROP[PRICE] = $price;
    $PROP[LINK] = $link;
    $PROP[COUNT] = $count;
    $PROP[NAME] = $name;
    $PROP[PHONE] = $phone;

    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => 11,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $name,
        "ACTIVE"         => "N",
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
        $PROP[ID] = $PRODUCT_ID;
        CEvent::Send("ORDER_BUY_ONE_CLICK", SITE_ID, $PROP);
        echo $PRODUCT_ID;
    }
    else
        echo false;

}
