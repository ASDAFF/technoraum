<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("ipol.sdek");

$delivery_price = CDeliverySDEK::countDelivery(
    array(
        'CITY_TO'    	 => CDeliverySDEK::zajsonit(iconv("UTF-8","Windows-1251",$_POST["CITY"])),
        'CITY_TO_ID' 	 => (array_key_exists('CITY_ID',$arParams)) ? $arParams['CITY_ID'] : false,
        'WEIGHT'     	 => (CDeliverySDEK::$goods['W'])*1000,
        'PRICE'      	 => CDeliverySDEK::$orderPrice,
        'FORBIDDEN'   	 => $arParams['FORBIDDEN'],
        'GOODS'	     	 => $_POST["GOODS"],
        'PERSON_TYPE_ID' => "1",
        'PAY_SYSTEM_ID'  => $arParams['PAYSYSTEM']
    )
);

if($delivery_price['courier'] == "no")
    return print '<div class="row delivery"><div class="delivery-door-table-title">Сервер временно недоступен.</div></div>';
?>
<div class="row delivery">
    <div class="col1 item">
        <div><img src="<?=$_POST['PATH_IMG']?>/img/cdek.jpg"></div>
        <div>СДЭК курьерская доставка</div>
    </div>
    <div class="col2 item"><div><?=$delivery_price['courier']?></div></div>
    <div class="col3 item"><div><?=$delivery_price['c_date']?> дня</div></div>
</div>
