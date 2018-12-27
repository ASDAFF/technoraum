<?php

if(CModule::IncludeModule("altasib.geobase")) {
    session_start();
    if($city = $_SESSION["ALTASIB_GEOBASE_CODE"]["CITY"]["NAME"]){
        $_SESSION['IPOLSDEK_city'] = $city;
    }else
        $_SESSION['IPOLSDEK_city'] = $_SESSION['ALTASIB_GEOBASE']['CITY_NAME'];
}

