<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeModuleLangFile(__FILE__);

require(__DIR__ . "/config.php");

$psTitle = GetMessage('RBS_PARTNER_NAME');
//$psDescription = GetMessage('RBS_PAYMENT_PAY_FROM', array('#BANK#' => GetMessage('RBS_PARTNER_NAME'))); //'������ ����� ' . $mess["partner_name"];
$user_name_name = GetMessage('RBS_PAYMENT_LOGIN'); //"�����";
$password_name = GetMessage('RBS_PAYMENT_PASSWORD'); //"������";
$two_stage_name = GetMessage('RBS_PAYMENT_STAGING'); //"����������� �������";
$two_stage_desc = GetMessage('RBS_PAYMENT_STAGING_DESC'); //"���� �������� 'Y', ����� ������������� ������������� ������. ��� ������ �������� ����� ������������� ������������� ������.";
$test_mode_name = GetMessage('RBS_PAYMENT_TEST_MODE'); //"�������� �����";
$test_mode_desc = GetMessage('RBS_PAYMENT_TEST_MODE_DESC'); //"���� �������� 'Y', ������ ����� �������� � �������� ������. ��� ������ �������� ����� ����������� ����� ������.";
$logging_name = GetMessage('RBS_PAYMENT_LOGGING'); //"�����������";
$logging_desc = GetMessage('RBS_PAYMENT_LOGGING_DESC'); //"���� �������� 'Y', ������ ����� ���������� ���� ������ � ����. ��� ������ �������� ����������� ����������� �� �����.";
$order_number_name = GetMessage('RBS_PAYMENT_ACCOUNT_NUMBER'); //"���������� ������������� ������ � ��������";
$amount_name = GetMessage('RBS_PAYMENT_ORDER_SUM'); //"����� ������";
$shipment_name = GetMessage('RBS_PAYMENT_SHIPMENT_NAME'); //"��������� ��������";
$shipment_desc = GetMessage('RBS_PAYMENT_SHIPMENT_DESC'); //"���� �������� 'Y', �� ����� �������� ������ ����� ������������� ��������� �������� ������.";
$shipment_set_payed = GetMessage('RBS_PAYMENT_SET_PAYED'); //"������������� �� � ������ ��������";
$shipment_set_payed_desc = GetMessage('RBS_PAYMENT_SET_PAYED_DESC'); //"������������� �� � ������ ��������";
//$ckeck_name = GetMessage('RBS_PAYMENT_CHECK'); //"������������� �� � ������ ��������";
$check_description = GetMessage('RBS_PAYMENT_CHECK_DESC'); //"������������� �� � ������ ��������";
$auto_open_form = GetMessage('RBS_PAYMENT_AUTO_OPEN_FORM'); // "�������������� �������� ���������� ����� ����� ���������� ������";
$auto_open_form_desc = GetMessage('RBS_PAYMENT_AUTO_OPEN_FORM_DESC'); // "�������������� �������� ���������� ����� ����� ���������� ������";

$arPSCorrespondence = array(

    "PASSWORD" => array(
        "NAME" => $password_name,
        'SORT' => 210,
    ),
    "USER_NAME" => array(
        "NAME" => $user_name_name,
        'SORT' => 200,
    ),
    "TWO_STAGE" => array(
        "NAME" => $two_stage_name,
        "DESCR" => $two_stage_desc,
        'SORT' => 240,
        'INPUT' => array(
            'TYPE' => 'Y/N'
        ),
        'DEFAULT' => array(
            "PROVIDER_VALUE" => "N",
            "PROVIDER_KEY" => "INPUT"
        )

    ),

    "AUTO_OPEN_FORM" => array(
        "NAME" => $auto_open_form,
        "DESCR" => $auto_open_form_desc,
        'SORT' => 275,
        'INPUT' => array(
            'TYPE' => 'Y/N'
        ),
        'DEFAULT' => array(
            "PROVIDER_VALUE" => "N",
            "PROVIDER_KEY" => "INPUT"
        )
    ),
    "TEST_MODE" => array(
        "NAME" => $test_mode_name,
        "DESCR" => $test_mode_desc,
//      "VALUE" => "Y",
        'SORT' => 230,
        'INPUT' => array(
            'TYPE' => 'Y/N'
        ),
        'DEFAULT' => array(
            "PROVIDER_VALUE" => "N",
            "PROVIDER_KEY" => "INPUT"
        )
    ),
    "LOGGING" => array(
        "NAME" => $logging_name,
        "DESCR" => $logging_desc,
        'SORT' => 235,
        'INPUT' => array(
            'TYPE' => 'Y/N'
        ),
        'DEFAULT' => array(
            "PROVIDER_VALUE" => "Y",
            "PROVIDER_KEY" => "INPUT"
        )
    ),
    "ORDER_NUMBER" => array(
        "NAME" => $order_number_name,
//      "VALUE" => "ID",
//      "TYPE" => "ORDER",
        'SORT' => 40,
        'DEFAULT' => array(
            'PROVIDER_KEY' => 'ORDER',
            'PROVIDER_VALUE' => 'ID'
        )
    ),
    "AMOUNT" => array(
        "NAME" => $amount_name,
//      "VALUE" => "SHOULD_PAY",
//      "TYPE" => "ORDER",
        'SORT' => 50,
        'DEFAULT' => array(
            'PROVIDER_KEY' => 'ORDER',
            'PROVIDER_VALUE' => 'SHOULD_PAY'
        )
    ),
    "SHIPMENT_ENABLE" => array(
        "NAME" => $shipment_name,
        "DESCR" => $shipment_desc,
//      "TYPE" => "VALUE",
        'SORT' => 280,
        'INPUT' => array(
            'TYPE' => 'Y/N'
        ),
        'DEFAULT' => array(
            "PROVIDER_VALUE" => "N",
            "PROVIDER_KEY" => "INPUT"
        )
    ),

);