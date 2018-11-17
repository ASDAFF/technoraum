<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include(GetLangFileName(dirname(__FILE__).'/', '/payment.php'));

if (!CModule::IncludeModule("directcredit.frame"))
{
	$APPLICATION->ThrowException(GetMessage("NO_DC_MODULE"));
	return false;
}

if (!CModule::IncludeModule("iblock"))
{
	$APPLICATION->ThrowException(GetMessage("NO_IBLOCK_MODULE"));
	return false;
}

$sWidgetURL = COption::GetOptionString("directcredit.frame", "widget_url");
if (!$sWidgetURL) $sWidgetURL = '//api.direct-credit.ru/dc.js';

$sWidgetCharset = COption::GetOptionString("directcredit.frame", "widget_charset");
if (!$sWidgetCharset) $sWidgetCharset = 'utf-8';

$sCategoryLevel = COption::GetOptionString("directcredit.frame", "category_level");
if (!$sCategoryLevel) $sCategoryLevel = 'first';

$ORDER_ID = IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]);
$rsBasket = CSaleBasket::GetList(array(), array("ORDER_ID" => $ORDER_ID));
$arOrder = $arOrderRaw = array();
$arrProducts = "var arrProducts = new Array();";
$item_counter=0;

while ($arItem = $rsBasket->Fetch()) 
{

	$arOrderRaw[] = $arItem;
    
    $arItem['TYPE']="other"; 
    
    if ($arItem["MODULE"]=='catalog')
    {        
        //non-SKU
        $res = CIBlockElement::GetByID($arItem["PRODUCT_ID"]);
        if($ar_res = $res->GetNext())
        {
            $last_section_id=0;

            if ($ar_res["IBLOCK_SECTION_ID"]!='')
            {
                $last_section_id=$ar_res["IBLOCK_SECTION_ID"];                
            }
            else
            {
                //SKU
                $db_props = CIBlockElement::GetProperty($ar_res["IBLOCK_ID"], $arItem["PRODUCT_ID"], array("sort" => "asc"), Array("CODE"=>"CML2_LINK"));
                if($ar_props = $db_props->Fetch())
                {	
                    $res_parent = CIBlockElement::GetByID($ar_props["VALUE"]);
                    if($ar_parent = $res_parent->GetNext())
                    {
                        $last_section_id=$ar_parent["IBLOCK_SECTION_ID"];                    
                    }
                    
                }                                
                                              
            }
            
            if ($last_section_id!=0)
            {
                if ($sCategoryLevel=='first')
                {
                    $nav = CIBlockSection::GetNavChain(false, $last_section_id);
                    if ($ar_section=$nav->GetNext())
                    {
                        $ar_first_section=$ar_section;
                    }
                    
                    $arItem['TYPE']=trim($ar_first_section["NAME"]);                                                
                    
                }
                else 
                {
                    $res_last_section = CIBlockSection::GetByID($last_section_id);
                    if($ar_last_section = $res_last_section->GetNext())
                    {
                        $arItem['TYPE']=trim($ar_last_section["NAME"]);                        
                    }
                }           
            }

        }        
        
    }
        
    $arrProducts.="
    arrProducts[".$item_counter.']={id:"'.$arItem['PRODUCT_ID'].'", name:"'.addslashes($arItem['NAME']).'", type:"'.addslashes($arItem['TYPE']).'", price:"'.$arItem['PRICE'].'", count:"'.IntVal($arItem['QUANTITY']).'"};';
    $item_counter++;
}

?>
<link rel="stylesheet" href="//api.direct-credit.ru/style.css" type="text/css"/></link>
<script src="//api.direct-credit.ru/JsHttpRequest.js" type="text/javascript"></script>
<script src="<?=$sWidgetURL?>" charset="<?=$sWidgetCharset?>" type="text/javascript"></script>
<script>
var partnerID=<?=CSalePaySystemAction::GetParamValue("SHOP_ID")?>;
<?php echo $arrProducts;?>

function phone_number(dirty_phone){
	var numbs = '0123456789';
	var arr = dirty_phone.split('');
	var clear_phone = '';

	for (i = 0; i < arr.length; i++) {
		if (numbs.indexOf(arr[i]) >= 0) {
			clear_phone += arr[i];
		}
	}
	if (clear_phone.length > 10) {
		clear_phone = clear_phone.substr(1,10);
	}
	return clear_phone;
}

var clean_phone=phone_number('<?=CSalePaySystemAction::GetParamValue("PHONE_MOBILE")?>');

DCLoans(partnerID, 'delProduct', false, function(result){
		if (result.status == true) {
			DCLoans(partnerID, 'addProduct', { products : arrProducts }, function(result){
					if (result.status == true) {
						DCLoans(partnerID, 'saveOrder', { order: '<?=$ORDER_ID?>', phone: clean_phone }, function(result){
							if (result.status == false) {

							}
						});
					}
				});
			}
		});

function DCCheckStatus(status) {

	var dc_status;

    if (status == 3 || status == 4) {
    	dc_status='CANCEL';
    }

    if (status == 1 || status == 2 || status == 5) {
    	dc_status='SIGN';
    }
    
	JsHttpRequest.query(
			'/bitrix/php_interface/include/sale_payment/directcredit.frame/result_js.php',
			{ dc_status : dc_status, order_id : '<?=$ORDER_ID?>', bitrix_sessid: '<?=bitrix_sessid();?>'},
			function(result) {
			}, false
		);
    
}
      
</script>
<??>