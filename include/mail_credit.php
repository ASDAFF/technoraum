<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$name = iconv("UTF-8","CP1251",trim(strip_tags($_REQUEST["firstName"])));
$phone = iconv("UTF-8","CP1251",trim(strip_tags($_REQUEST["phone"])));
$shop = iconv("UTF-8","CP1251",trim(strip_tags($_REQUEST["shop"])));

$arEventFields = array(
    "NAME" => $name,
    "PHONE" => $phone,
    "SHOP" => $shop
);

CEvent::Send("MAIL_CREDIT", SITE_ID, $arEventFields);