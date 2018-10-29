<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "����������� ������� ������� ��� ���� Karcher � ������ ��������� ������.");
$APPLICATION->SetPageProperty("keywords", "������� ������� karcher, ������ ������� ������, ������� ������� karcher, ������� ������� ������, ���� ������� ������, ������� karcher, ������� ������, ������� ������ +� ����������");
$APPLICATION->SetPageProperty("title", "������ ������� ������� ������ (Karcher) � ����������. ����������� ���� �� ����� �������� \"���������\".");
$APPLICATION->SetTitle("�������");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main_catalog", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/personal/cart/",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "DETAIL_P7",
			2 => "DETAIL_P8",
			3 => "DETAIL_P1",
			4 => "OLD_PRICE",
			5 => "DETAIL_P3",
			6 => "DETAIL_P4",
			7 => "DETAIL_P5",
			8 => "DETAIL_P2",
			9 => "DETAIL_P6",
			10 => "OLD_PRICE_VAL",
			11 => "STICKER",
			12 => "BRAND",
			13 => "PRESSURE",
			14 => "POWER",
			15 => "TENSION",
			16 => "PERFOMANCE",
			17 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DETAIL_SHOW_PICTURE" => "Y",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"GIFTS_DETAIL_BLOCK_TITLE" => "�������� ���� �� ��������",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "�������",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "�������� ���� �� �������, ����� �������� �������",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MESS_BTN_BUY" => "�������",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "������� � ������� ����� �������",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "�������",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LINE_ELEMENT_COUNT" => "3",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID" => "",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_PROPERTY_SID" => "",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_META_KEYWORDS" => "-",
		"LIST_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "DETAIL_P7",
			2 => "DETAIL_P8",
			3 => "DETAIL_P1",
			4 => "OLD_PRICE",
			5 => "DETAIL_P3",
			6 => "DETAIL_P4",
			7 => "DETAIL_P5",
			8 => "DETAIL_P2",
			9 => "DETAIL_P6",
			10 => "COMP_P7",
			11 => "OLD_PRICE_VAL",
			12 => "STICKER",
			13 => "GIFT",
			14 => "COMP_P1",
			15 => "COMP_P2",
			16 => "COMP_P3",
			17 => "COMP_P4",
			18 => "COMP_P5",
			19 => "COMP_P6",
			20 => "DESC",
			21 => "",
		),
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "������",
		"PAGE_ELEMENT_COUNT" => "12",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
			0 => "OLD_PRICE",
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_SHOW_PARENT_NAME" => "Y",
		"SECTION_TOP_DEPTH" => "1",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_TOP_ELEMENTS" => "Y",
		"TOP_ELEMENT_COUNT" => "1",
		"TOP_ELEMENT_SORT_FIELD" => "shows",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_LINE_ELEMENT_COUNT" => "3",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_ALSO_BUY" => "N",
		"USE_COMPARE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"USE_REVIEW" => "N",
		"USE_STORE" => "N",
		"COMPONENT_TEMPLATE" => "main_catalog",
		"SEF_FOLDER" => "/catalog/",
		"FILE_404" => "",
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "price",
		),
		"TEMPLATE_THEME" => "site",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "������",
		"MESS_BTN_ADD_TO_BASKET" => "� �������",
		"MESS_BTN_COMPARE" => "���������",
		"MESS_BTN_DETAIL" => "���������",
		"MESS_NOT_AVAILABLE" => "��� � �������",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "BUY",
		"SECTION_ADD_TO_BASKET_ACTION" => "BUY",
		"DETAIL_ADD_TO_BASKET_ACTION" => "",
		"TOP_VIEW_MODE" => "BANNER",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "H",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_POSITION" => "top left",
		"TOP_ROTATE_TIMER" => "30",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "element/#ELEMENT_CODE#/",
			"compare" => "/compare/index.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?>


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
			#shop_popup .gifts .row .name{width:100%;font-size:12px;padding-left:26px;font-weight:100}
		</style>



		<a class="fancy open_shop" href="#shop_popup"></a>
		<div class="popup callback_popup" id="shop_popup">
			<div class="the_form">										
				<p class="form_title">����� �������� � �������</p>
				<div class="the_form_div">									
					<div class="img"><img class="main_img"/></div>
					<div class="description">
						<div class="name main_name">����� �������� �������� K7 Premium</div>
						<div class="price main_price"></div>
						<div class="quantity gifts">
							<div class="icon">

							</div>
							<div class="items gg">

							</div>
						</div>
					</div>
					<div class="info">
						<div class="cart_count">� ������� <span>4</span> ������</div>
						<div class="cart_summ">�� ����� 17 078 ���.</div>
					</div>
				</div>
				<div class="the_form_div" style="display:flex">									
					<div class="l"><a href="#" class="ffclose">���������� �������</a></div>
					<div class="r"><a href="/personal/cart/"><button>������� � �������</button></a></div>
				</div>
				<p class="form_title m">��� ��� �� ����� ����������</p>
				<?
					$APPLICATION->IncludeComponent("bitrix:catalog.top", "recom_popup", Array(
					"ACTION_VARIABLE" => "action",	// �������� ����������, � ������� ���������� ��������
						"ADD_PICT_PROP" => "-",	// �������������� �������� ��������� ������
						"ADD_PROPERTIES_TO_BASKET" => "Y",	// ��������� � ������� �������� ������� � �����������
						"ADD_TO_BASKET_ACTION" => "ADD",	// ���������� ������ ���������� � ������� ��� �������
						"BASKET_URL" => "/personal/cart/",	// URL, ������� �� �������� � �������� ����������
						"CACHE_FILTER" => "N",	// ���������� ��� ������������� �������
						"CACHE_GROUPS" => "Y",	// ��������� ����� �������
						"CACHE_TIME" => "36000000",	// ����� ����������� (���.)
						"CACHE_TYPE" => "A",	// ��� �����������
						"COMPARE_NAME" => "CATALOG_COMPARE_LIST",	// ���������� ��� ��� ������ ���������
						"COMPATIBLE_MODE" => "Y",	// �������� ����� �������������
						"CONVERT_CURRENCY" => "N",	// ���������� ���� � ����� ������
						"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",	// ������ �������
						"DETAIL_URL" => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",	// URL, ������� �� �������� � ���������� �������� �������
						"DISPLAY_COMPARE" => "N",	// ��������� ��������� �������
						"ELEMENT_COUNT" => "9",	// ���������� ��������� ���������
						"ELEMENT_SORT_FIELD" => "timestamp_x",	// �� ������ ���� ��������� ��������
						"ELEMENT_SORT_FIELD2" => "id",	// ���� ��� ������ ���������� ���������
						"ELEMENT_SORT_ORDER" => "asc",	// ������� ���������� ���������
						"ELEMENT_SORT_ORDER2" => "desc",	// ������� ������ ���������� ���������
						"ENLARGE_PRODUCT" => "STRICT",	// �������� ������ � ������
						"FILTER_NAME" => "",	// ��� ������� �� ���������� ������� ��� ���������� ���������
						"HIDE_NOT_AVAILABLE" => "N",	// ����������� ������
						"HIDE_NOT_AVAILABLE_OFFERS" => "N",	// ����������� �������� �����������
						"IBLOCK_ID" => "8",	// ��������
						"IBLOCK_TYPE" => "catalog",	// ��� ���������
						"LABEL_PROP" => "",	// �������� ����� ������
						"LINE_ELEMENT_COUNT" => "3",	// ���������� ��������� ��������� � ����� ������ �������
						"MESS_BTN_ADD_TO_BASKET" => "� �������",	// ����� ������ "�������� � �������"
						"MESS_BTN_BUY" => "������",	// ����� ������ "������"
						"MESS_BTN_COMPARE" => "��������",	// ����� ������ "��������"
						"MESS_BTN_DETAIL" => "���������",	// ����� ������ "���������"
						"MESS_NOT_AVAILABLE" => "��� � �������",	// ��������� �� ���������� ������
						"OFFERS_LIMIT" => "0",	// ������������ ���������� ����������� ��� ������ (0 - ���)
						"PARTIAL_PRODUCT_PROPERTIES" => "N",	// ��������� ��������� � ������� ������, � ������� ��������� �� ��� ��������������
						"PRICE_CODE" => array(	// ��� ����
							0 => "price",
						),
						"PRICE_VAT_INCLUDE" => "Y",	// �������� ��� � ����
						"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",	// ������� ����������� ������ ������
						"PRODUCT_ID_VARIABLE" => "id",	// �������� ����������, � ������� ���������� ��� ������ ��� �������
						"PRODUCT_PROPERTIES" => "",	// �������������� ������
						"PRODUCT_PROPS_VARIABLE" => "prop",	// �������� ����������, � ������� ���������� �������������� ������
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// �������� ����������, � ������� ���������� ���������� ������
						"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",	// ������� ����������� �������
						"PRODUCT_SUBSCRIPTION" => "Y",	// ��������� ���������� ��� ������������� �������
						"PROPERTY_CODE" => array(	// ��������
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
						"PROPERTY_CODE_MOBILE" => "",	// �������� �������, ������������ �� ��������� �����������
						"SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",	// URL, ������� �� �������� � ���������� �������
						"SEF_MODE" => "Y",	// �������� ��������� ���
						"SHOW_CLOSE_POPUP" => "N",	// ���������� ������ ����������� ������� �� ����������� �����
						"SHOW_DISCOUNT_PERCENT" => "N",	// ���������� ������� ������
						"SHOW_MAX_QUANTITY" => "N",	// ���������� ������� ������
						"SHOW_OLD_PRICE" => "N",	// ���������� ������ ����
						"SHOW_PRICE_COUNT" => "1",	// �������� ���� ��� ����������
						"SHOW_SLIDER" => "Y",	// ���������� ������� ��� �������
						"SLIDER_INTERVAL" => "3000",	// �������� ����� �������, ��
						"SLIDER_PROGRESS" => "N",	// ���������� ������ ���������
						"TEMPLATE_THEME" => "blue",	// �������� ����
						"USE_ENHANCED_ECOMMERCE" => "N",	// ���������� ������ ����������� �������� � Google � ������
						"USE_PRICE_COUNT" => "N",	// ������������ ����� ��� � �����������
						"USE_PRODUCT_QUANTITY" => "N",	// ��������� �������� ���������� ������
						"VIEW_MODE" => "SECTION",	// ����� ���������
						"COMPONENT_TEMPLATE" => "main_top",
						"SEF_RULE" => " /catalog/#SECTION_CODE#/",	// ������� ��� ���������
					),
					false
				);?>	
		</div><!--/callback_popup-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>