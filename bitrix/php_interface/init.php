<?php
if(CModule::IncludeModule("altasib.geobase")) {
    $arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
    if($arSelCity['C_NAME']){
        session_start();
        $_SESSION['IPOLSDEK_city'] = $arSelCity['C_NAME'];
    }else
        return false;
}