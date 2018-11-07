<?php
$goods = array(
    array(
        "QUANTITY" => 1,
        "DIMENSIONS" => array(
            "WIDTH" => $arParams['WIDTH'],
            "HEIGHT" => $arParams['HEIGHT'],
            "LENGTH" => $arParams['LENGTH'],
        ),
        "WEIGHT" => $arParams['WEIGHT'],
    )
);

$arResult['GOODS'] = json_encode($goods);

