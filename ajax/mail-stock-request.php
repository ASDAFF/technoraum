<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);
CModule::IncludeModule( 'catalog' );

if(!$_REQUEST['email'])
    die;

$result = \Bitrix\Main\Text\Encoding::convertEncoding($_REQUEST,"UTF-8", "windows-1251");

$arFields = array(
    "EMAIL" => $result['email'],
    "TABLE" => '<table style="text-align: center" cellspacing="0" border="1" cellpadding="0" width="100%">'.str_replace("?","�",$result['table']).'</table>'
);

if(CEvent::Send("MAIL_STOCK_LIST", SITE_ID, $arFields)):
?>
    <div id="in-stock-request">
        <div class="stock-msg-send">
            <h1>������� �� ���������!</h1>
            <p>��������� ������� ����������.</p>
            <p>� ��������� ����� ��� ���������� ������� ���.</p>
        </div>
    </div>
<?endif;?>
