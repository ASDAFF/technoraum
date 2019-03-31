<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "� ��������-�������� TechnoRaum �� ������ �������� ������� Karcher �� ��������� �����. ��������� �� �������� 8(800)250-13-08.");
$APPLICATION->SetPageProperty("keywords", "Karcher, ʸ����, ������, ������ ����������� ���� � ����������, ������ ������ � ����������, ������� ������, ������� ������ ���������, ��������� ������� karcher, ������ ������, ������, ������ karcher � �������� ��������, ������ �������� �������, ������ ������ ����");
$APPLICATION->SetPageProperty("title", "���� ������ (Karcher) � ����������. | ����������� ��������-������� ���������");
$APPLICATION->SetTitle("������ ������� Karcher � ���������� �� ����������� �����: �������� ���� �� ��� ������.");
?>

<section class="section top_banner_section">

	<div class="inner_section">
		<?$APPLICATION->IncludeComponent("nbrains:slider", ".main.slider", Array(
		"BTN_SLIDE_CONTROL" => "bottom",	// ������������ �������������� ������
		"CACHE_GROUPS" => "Y",	// ��������� ����� �������
		"CACHE_TIME" => "36000000",	// ����� ����������� (���.)
		"CACHE_TYPE" => "A",	// ��� �����������
		"CHECK_DATES" => "Y",	// ���������� ������ �������� �� ������ ������ ��������
		"HEIGHT" => "400",	// ������ �� ��������� � ��������
		"HIDDEN_ARROWS" => "false",	// �������� ������� ����������, ����� ���� �������� �������
		"IBLOCK_ID" => "5",	// ��� ��������������� �����
		"IBLOCK_TYPE" => "materials",	// ��� ��������������� ����� (������������ ������ ��� ��������)
		"PROGRESS_BAR_COLOR" => "feee35",	// ���� �������� ���� #
		"PROGRESS_BAR_HEIGHT" => "4",	// ������ �������� ���� � ��������
		"PROGRESS_BAR_PLACE" => "bottom",	// ������������ �������� ����
		"RIGHT_PX_TEXT" => "70",	// ������������ ������ �� ������� ���� � px.
		"SORT_BY1" => "ACTIVE_FROM",	// ���� ��� ������ ���������� ��������
		"SORT_BY2" => "SORT",	// ���� ��� ������ ���������� ��������
		"SORT_ORDER1" => "DESC",	// ����������� ��� ������ ���������� ��������
		"SORT_ORDER2" => "ASC",	// ����������� ��� ������ ���������� ��������
		"TIME_SLIDE" => "5",	// ����� ����������� ������ � ��������
		"TOP_PX_TEXT" => "135",	// ������������ ������ �� �������� ���� � px.
		"WIDTH" => "1170",	// ������ �� ����������� � ��������
		"PROPERTY_CODE" => array("SLIDE_SHOW","SLIDE_IMAGE")
	),
	false
);?>
	</div>

	<div class="inner_section">
		<?$APPLICATION->IncludeComponent("nbrains:news.line", "service.home", Array(
			"ACTIVE_DATE_FORMAT" => "d.m.Y",	// ������ ������ ����
			"CACHE_GROUPS" => "Y",	// ��������� ����� �������
			"CACHE_TIME" => "300",	// ����� ����������� (���.)
			"CACHE_TYPE" => "A",	// ��� �����������
			"DETAIL_URL" => "",	// URL, ������� �� �������� � ���������� �������� �������
			"FIELD_CODE" => array(	// ����
				0 => "DETAIL_TEXT",
				1 => "PREVIEW_PICTURE",
			),
			"IBLOCKS" => array(	// ��� ��������������� �����
				0 => "14",
			),
			"IBLOCK_TYPE" => "materials",	// ��� ��������������� �����
			"NEWS_COUNT" => "3",	// ���������� �������� �� ��������
			"SORT_BY1" => "SORT",	// ���� ��� ������ ���������� ��������
			"SORT_BY2" => "SORT",	// ���� ��� ������ ���������� ��������
			"SORT_ORDER1" => "ASC",	// ����������� ��� ������ ���������� ��������
			"SORT_ORDER2" => "ASC",	// ����������� ��� ������ ���������� ��������
		),
			false
		);?>
	</div>

 </section>

<?$APPLICATION->IncludeComponent("bitrix:news.line", ".block.home", Array(
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// ������ ������ ����
	"CACHE_GROUPS" => "Y",	// ��������� ����� �������
	"CACHE_TIME" => "300",	// ����� ����������� (���.)
	"CACHE_TYPE" => "A",	// ��� �����������
	"DETAIL_URL" => "",	// URL, ������� �� �������� � ���������� �������� �������
	"FIELD_CODE" => array(	// ����
		0 => "CODE",
		1 => "NAME",
		2 => "PREVIEW_TEXT",
		3 => "PREVIEW_PICTURE",
		4 => "",
	),
	"IBLOCKS" => array(	// ��� ��������������� �����
		0 => "12",
	),
	"IBLOCK_TYPE" => "materials",	// ��� ��������������� �����
	"NEWS_COUNT" => "3",	// ���������� �������� �� ��������
	"SORT_BY1" => "SORT",	// ���� ��� ������ ���������� ��������
	"SORT_BY2" => "SORT",	// ���� ��� ������ ���������� ��������
	"SORT_ORDER1" => "ASC",	// ����������� ��� ������ ���������� ��������
	"SORT_ORDER2" => "ASC",	// ����������� ��� ������ ���������� ��������
),
	false
);?>


<section class="section glav_cat_section hits_section">
<div class="inner_section clearfix">
	<div class="the_section_head">
		<p class="section_title">
 			<a href="#">���� ������</a>
		</p>
	</div>
	<?
	$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"recom_main", 
	array(
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
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:8:18\",\"DATA\":{\"logic\":\"Equal\",\"value\":12}}]}",
		"DETAIL_URL" => "/catalog/element/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "catalog",
		"LABEL_PROP" => array(
		),
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "� �������",
		"MESS_BTN_BUY" => "������",
		"MESS_BTN_COMPARE" => "��������",
		"MESS_BTN_DETAIL" => "���������",
		"MESS_NOT_AVAILABLE" => "��� � �������",
		"OFFERS_LIMIT" => "0",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
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
		"PROPERTY_CODE_MOBILE" => array(
		),
		"SECTION_URL" => "/catalog/#SECTION_CODE#/",
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
		"COMPONENT_TEMPLATE" => "recom_main",
		"SEF_RULE" => " /catalog/#SECTION_CODE#/"
	),
	false
);?>
</div>
 </section><!--/glav_cat_section--> <section class="section glav_cat_section new_cat_section">
<div class="inner_section clearfix">
	<div class="the_section_head">
		<p class="section_title">
 			<a href="#">�������</a>
		</p>
	</div>
<?
	$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"main_top1", 
	array(
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
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:8:18\",\"DATA\":{\"logic\":\"Equal\",\"value\":13}}]}",
		"DETAIL_URL" => "/catalog/element/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "catalog",
		"LABEL_PROP" => array(
		),
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "� �������",
		"MESS_BTN_BUY" => "������",
		"MESS_BTN_COMPARE" => "��������",
		"MESS_BTN_DETAIL" => "���������",
		"MESS_NOT_AVAILABLE" => "��� � �������",
		"OFFERS_LIMIT" => "99",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
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
		"PROPERTY_CODE_MOBILE" => array(
		),
		"SECTION_URL" => "/catalog/#SECTION_CODE#/",
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
		"COMPONENT_TEMPLATE" => "main_top1",
		"SEF_RULE" => " /catalog/#SECTION_CODE#/"
	),
	false
);?>
</div>
 </section><!--/glav_cat_section-->

<section class="section middle_consult_section middle_consult_section1">
	<div class="inner_section clearfix">
		<div class="middle_consult_img">
	 		<img src="<?=SITE_TEMPLATE_PATH?>/img/middle_consult_img.png" alt="">
		</div>
		<div class="middle_order_right clearfix">
			<div class="the_form mini_form mini_form_mark2">
				<form method="post" class="mform">
					<input type="hidden" name="form_id" value="1" />
					<p class="title">
						 ��������� ������ � ������� ������������ ��� ������������?
					</p>
					<p>
						 ��������� ������ ����� ������, �� �������� � ����,<br>
						 ���������������� � �������� ����������� ������������
					</p>
					<div class="the_form_div the_form_div_text_and_submit">
						<input type="text" name="tel" required placeholder="��� ����� ��������">
						<input type="submit" name="submit1" value="��������� ������" onclick="yaCounter51314392.reachGoal('zayavka')">
					</div>
					<div class="the_form_div the_form_div_accept">
						<input type="checkbox" class="checkbox-toggle" name="accept" required
							   checked="checked"
							   data-tt-type="square_v"
							   data-tt-label-check="� c������� �� <a href='/soglasie-na-obrabotku-personalnykh-dannykh/' target=_blank>��������� ������������ ������</a>"
							   data-tt-label-uncheck="�� �� ���� �������� �� ��������� ������������ ������.">
					</div>
				</form>
			</div>
			 <!--/mini_form mini_form_mark2-->
		</div>
	</div>
</section>
<!--/middle_consult_section-->

<section class="section glav_news_section">
<div class="inner_section">
	<div class="the_section_head">
		<p class="section_title">
			 ������� � ������
		</p>
		<a class="the_section_head_all" href="/news/">��� ������� � ������</a>
	</div>
	<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"slider_news", 
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "��������",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "7",
		"IBLOCK_TYPE" => "materials",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "UNIT",
			1 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "0",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "�������",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"COMPONENT_TEMPLATE" => "slider_news",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"SEF_FOLDER" => "/news/",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);
?>
</div>
</section><!--/glav_news_section-->

<section class="section middle_consult_section subscribe_section">
	<div class="inner_section clearfix">
		<div class="middle_consult_img">
			<img src="<?=SITE_TEMPLATE_PATH?>/img/subscribe_img0.png" alt="">
		</div>
		<div class="middle_order_right clearfix">
			<div class="the_form mini_form mini_form_mark2">
					 <?$APPLICATION->IncludeComponent("bitrix:sender.subscribe", "template1", Array(
						"AJAX_MODE" => "N",	// �������� ����� AJAX
							"AJAX_OPTION_ADDITIONAL" => "",	// �������������� �������������
							"AJAX_OPTION_HISTORY" => "N",	// �������� �������� ��������� ��������
							"AJAX_OPTION_JUMP" => "N",	// �������� ��������� � ������ ����������
							"AJAX_OPTION_STYLE" => "Y",	// �������� ��������� ������
							"CACHE_TIME" => "3600",	// ����� ����������� (���.)
							"CACHE_TYPE" => "A",	// ��� �����������
							"CONFIRMATION" => "Y",	// ����������� ������������� �������� �� email
							"HIDE_MAILINGS" => "N",	// ������ ������ ��������, � ����������� �� ���
							"SET_TITLE" => "N",	// ������������� ��������� ��������
							"SHOW_HIDDEN" => "N",	// �������� ������� �������� ��� ��������
							"USER_CONSENT" => "N",	// ����������� ��������
							"USER_CONSENT_ID" => "0",	// ����������
							"USER_CONSENT_IS_CHECKED" => "Y",	// ����� �� ��������� �����������
							"USER_CONSENT_IS_LOADED" => "N",	// ��������� ����� �����
							"USE_PERSONALIZATION" => "Y",	// ���������� �������� �������� ������������
							"COMPONENT_TEMPLATE" => "footer-subscribe"
						),
						false
					);?>
			</div>
			 <!--/mini_form mini_form_mark2-->
		</div>
	</div>
 </section>
<!--/middle_consult_section subscribe_section-->


<section class="section seo_section">
<div class="inner_section the_content_section clearfix">
	<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/seo_h2.php",Array(),Array("MODE"=>"html")); ?>
	<div>
		<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/seo_text.php",Array(),Array("MODE"=>"html")); ?>
	</div>
</div>
 </section><!--/seo_section-->


<?
require($_SERVER["DOCUMENT_ROOT"]."/include/product_popup.php");
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>