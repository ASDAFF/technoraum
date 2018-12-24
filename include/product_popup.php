<style>
    #shop_popup.popup{width:800px !important}
    #shop_popup .the_form_div{width:100%;display:flex}
    #shop_popup .the_form_div .img{width:150px}
    #shop_popup .the_form_div .img img{width:100%}
    #shop_popup .the_form_div .description{width:50%}
    #shop_popup .the_form_div .description .name{font-size:16px;font-weight:bold}
    #shop_popup .the_form_div .description .price{padding-top: 10px;font-weight: bold}
    #shop_popup .the_form_div .cart_count{font-weight:100}
    #shop_popup .the_form_div .cart_count span{font-weight:bold}
    #shop_popup .the_form_div .cart_summ{font-weight:100}
    #shop_popup .the_form_div .cart_summ span{font-weight:bold}
    #shop_popup .the_form_div .quantity{margin-top:15px;display:flex;flex-direction:column}
    #shop_popup .the_form_div .l{width:50%;text-align:left;padding-top:30px}
    #shop_popup .the_form_div .r{width:50%;text-align:right}
    #shop_popup .the_form_div .r button{background: #feee35;padding:15px 20px;font-weight:bold;font-size:16px;color:#000;border:none}
    #shop_popup .the_form_div .form_title.m{font-size:18px}

    #shop_popup .row{display:flex}
    #shop_popup .row .img{width:10%}
    #shop_popup .row .img img{width:100%}
    #shop_popup .row .name{width:30%;color:#337ab7}
    #shop_popup .row .price{width:15%;text-align:center}
    #shop_popup .row .quantity{width:20%;display:flex}
    #shop_popup .row .quantity .minus{width:30px;height:30px;background:none;border: 1px solid #ededed;margin:0 auto}
    #shop_popup .row .quantity .plus{width:30px;height:30px;background:none;border: 1px solid #ededed;border-left:none;margin:0 auto}
    #shop_popup .row .quantity .count input{width:50px;height:30px;padding:0 5px;text-align:center;background:none;border: 1px solid #ededed;border-left:none;margin:0 auto}
    #shop_popup .row .btn{width:20%;padding:0;margin:0}
    #shop_popup .row .btn button{width:170px;background: #feee35;padding:10px 20px;font-weight:bold;font-size:12px;color:#000;border:none;position:relative;top:-5px}

    #shop_popup .gifts .row{width:100%;padding:0;margin:0}
    #shop_popup .gifts .row .name{
        font-size: 12px;
        padding-left: 26px;
        white-space: nowrap;
        font-weight: normal;
        color: #35a2e8;
        text-decoration: none;
    }
</style>



<a class="fancy open_shop" href="#shop_popup"></a>
<div class="popup callback_popup" id="shop_popup">
    <div class="the_form">
        <p class="form_title">Товар добавлен в корзину</p>
        <div class="the_form_div">
            <div class="img"><img class="main_img"/></div>
            <div class="description">
                <div class="name main_name">Мойка высокого давления K7 Premium</div>
                <div class="price main_price"></div>
                <div class="price main_sale"></div>
                <div class="price main_profit"></div>
                <div class="quantity gifts">
                    <div class="icon">

                    </div>
                    <div class="items gg">

                    </div>
                </div>
            </div>
            <div class="info">
                <div class="cart_count">В корзине <span>4</span> товара</div>
                <div class="cart_summ">на сумму 17 078 руб.</div>
            </div>
        </div>
        <div class="the_form_div" style="display:flex">
            <div class="l"><a href="#" class="ffclose">Продолжить покупки</a></div>
            <div class="r"><a href="/personal/cart/"><button>Перейти к корзину</button></a></div>
        </div>
        <p class="form_title m">Вам так же могут понравится</p>
        <? $APPLICATION->IncludeComponent("bitrix:catalog.top", "recom_popup", Array(
            "ACTION_VARIABLE" => "action",
            "ADD_PICT_PROP" => "-",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "BASKET_URL" => "/personal/cart/",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
            "COMPATIBLE_MODE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
            "DETAIL_URL" => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
            "DISPLAY_COMPARE" => "N",
            "ELEMENT_COUNT" => "9",
            "ELEMENT_SORT_FIELD" => "timestamp_x",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_ORDER2" => "desc",
            "ENLARGE_PRODUCT" => "STRICT",
            "FILTER_NAME" => "",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "IBLOCK_ID" => "8",
            "IBLOCK_TYPE" => "catalog",
            "LABEL_PROP" => "",
            "LINE_ELEMENT_COUNT" => "3",
            "MESS_BTN_ADD_TO_BASKET" => "",
            "MESS_BTN_BUY" => "",
            "MESS_BTN_COMPARE" => "",
            "MESS_BTN_DETAIL" => "",
            "MESS_NOT_AVAILABLE" => "",
            "OFFERS_LIMIT" => "0",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => array(
                0 => "price",
            ),
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => "",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "PRODUCT_SUBSCRIPTION" => "Y",
            "PROPERTY_CODE" => array(
                0 => "ARTICLE",
                1 => "BRAND",
                2 => "DETAIL_P7",
                3 => "DETAIL_P8",
                4 => "PRESSURE",
                5 => "DETAIL_P1",
                6 => "OLD_PRICE",
                7 => "DETAIL_P3",
                8 => "POWER",
                9 => "TENSION",
                10 => "DETAIL_P4",
                11 => "DETAIL_P5",
                12 => "PERFOMANCE",
                13 => "DETAIL_P2",
                14 => "DETAIL_P6",
                15 => "OLD_PRICE_VAL",
                16 => "STICKER",
                17 => "",
            ),
            "PROPERTY_CODE_MOBILE" => "",
            "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
            "SEF_MODE" => "Y",
            "SHOW_CLOSE_POPUP" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "SHOW_SLIDER" => "Y",
            "SLIDER_INTERVAL" => "3000",
            "SLIDER_PROGRESS" => "N",
            "TEMPLATE_THEME" => "blue",
            "USE_ENHANCED_ECOMMERCE" => "N",
            "USE_PRICE_COUNT" => "N",
            "USE_PRODUCT_QUANTITY" => "N",
            "VIEW_MODE" => "SECTION",
            "COMPONENT_TEMPLATE" => "main_top",
            "SEF_RULE" => " /catalog/#SECTION_CODE#/",
        ),
            false
        );?>
    </div>
</div>
<!--/callback_popup-->