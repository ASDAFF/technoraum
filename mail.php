<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$to      = "zakaz@technoraum.ru";
$headers = 'From: webmaster@technoraum.ru' . "\r\n" .
			'Reply-To: webmaster@technoraum.ru' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			
if($_POST["form_id"] == 1)
{
	$subject = "Заявка на онлайн консультацию";
	$message = "Поступила заявка с сайта TechnoRaum.ru на онлайн консультацию:\n"."Телефон: ".$_POST["tel"];
}
elseif($_POST["form_id"] == 2)
{
	CModule::IncludeModule('subscribe');
    $arFields = Array(
        "USER_ID" => ($USER->IsAuthorized()? $USER->GetID():false),
        "FORMAT" => ($FORMAT <> "html"? "text":"html"),
        "EMAIL" => $_POST["mail"],
        "ACTIVE" => "Y",
        "RUB_ID" => $RUB_ID
    );
    $subscr = new CSubscription;

    $ID = $subscr->Add($arFields);
    if($ID>0)
        CSubscription::Authorize($ID);
    else
        echo "Error adding subscription: ".$subscr->LAST_ERROR."<br>";
	die();
}
elseif($_POST["form_id"] == 3)
{
	$subject = "Заявка на отправу уведомления о наличии товара";
	$message = "Поступила заявка с сайта TechnoRaum.ru на отправу уведомления о наличии товара:\n"."Имя:".$_POST["name"]."\nТелефон: ".$_POST["tel"]."\nТовар: ".$_POST["product_name"];
}
elseif($_POST["form_id"] == 4 || $_POST["form_id"] == 6)
{
    $page = trim(strip_tags($_POST["name_page"]));
    $name = trim(strip_tags($_POST["name"]));
    $phone = trim(strip_tags($_POST["tel"]));
    $arEventFields = array(
        "PAGE" => $page,
        "NAME" => $name,
        "PHONE" => $phone
    );

    switch($_POST["form_id"]){
        case 4:
            $event = "CHECK_MANAGER";
            break;
        case 6:
            $event = "ORDER_CONSULTANT";
            break;
    }

    CEvent::Send($event, SITE_ID, $arEventFields);
}
elseif($_POST["form_id"] == 5)
{
	$subject = "Заявка на заказ услуги";
	$message = "Поступила заявка с сайта TechnoRaum.ru на заказ услуги:\n"."Имя:".$_POST["name"]."\nТелефон: ".$_POST["tel"]."\nУслуга: ".$_POST["service"];
}

if($subject && $message){
    mail($to, $subject, $message, $headers);
}
?>