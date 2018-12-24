<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "В интернет-магазине TechnoRaum вы можете заказать технику Karcher по доступным ценам. Подробнее по телефону 8(800)250-13-08.");
$APPLICATION->SetPageProperty("keywords", "Karcher, Кёрхер, керхер, керхер официальный сайт в краснодаре, купить керхер в краснодаре, магазин керхер, магазин керхер краснодар, фирменный магазин karcher, товары керхер, керхер, купить karcher в интернет магазине, керхер интернет магазин, товары керхер цены");
$APPLICATION->SetPageProperty("title", "Сайт Керхер (Karcher) в Краснодаре. | Официальный интернет-магазин ТехноРаум");
$APPLICATION->SetTitle("Купить технику Karcher в Краснодаре на официальном сайте: выгодные цены на все товары.");
?>

<section class="section top_banner_section">

	<div class="inner_section">
		<?$APPLICATION->IncludeComponent("nbrains:slider", ".main.slider", Array(
		"BTN_SLIDE_CONTROL" => "bottom",	// расположение переключателей слайда
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"HEIGHT" => "400",	// размер по вертикали в пикселях
		"HIDDEN_ARROWS" => "false",	// Скрывает элемент управления, когда мышь покидает слайдер
		"IBLOCK_ID" => "5",	// Код информационного блока
		"IBLOCK_TYPE" => "materials",	// Тип информационного блока (используется только для проверки)
		"PROGRESS_BAR_COLOR" => "feee35",	// Цвет прогресс бара #
		"PROGRESS_BAR_HEIGHT" => "4",	// ширина прогресс бара в пикселях
		"PROGRESS_BAR_PLACE" => "bottom",	// Расположение прогресс бара
		"RIGHT_PX_TEXT" => "70",	// расположение текста от правого края в px.
		"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
		"TIME_SLIDE" => "5",	// время отображения слайда в секундах
		"TOP_PX_TEXT" => "135",	// расположение текста от верхнего края в px.
		"WIDTH" => "1170",	// размер по горизонтали в пикселях
		"PROPERTY_CODE" => array("SLIDE_SHOW","SLIDE_IMAGE")
	),
	false
);?>
	</div>

	<div class="inner_section">
		<div class="icons_wrap">
			<div class="icons_div">
				<div class="img">
					 <img src="/bitrix/templates/TechnoRaum/img/i1.png" alt="">
				</div>
				<div class="text">
					 <? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/adv1.php",Array(),Array("MODE"=>"html")); ?>
				</div>
			</div>
			<div class="icons_div">
				<div class="img">
					<img src="/bitrix/templates/TechnoRaum/img/i2.png" alt="">
				</div>
				<div class="text">
					 <? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/adv2.php",Array(),Array("MODE"=>"html")); ?>
				</div>
			</div>
			<div class="icons_div">
				<div class="img">
					<img src="/bitrix/templates/TechnoRaum/img/i3.png" alt="">
				</div>
				<div class="text">
					 <? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/adv3.php",Array(),Array("MODE"=>"html")); ?>
				</div>
			</div>
		</div>
	</div>

 </section>
<?
$main_sections = array(7, 8, 11);
$APPLICATION->IncludeComponent("bitrix:catalog", "home_categories", Array(
	"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
		"ADD_ELEMENT_CHAIN" => "Y",	// Включать название элемента в цепочку навигации
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
		"ADD_SECTIONS_CHAIN" => "Y",	// Включать раздел в цепочку навигации
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"BASKET_URL" => "/personal/cart/",	// URL, ведущий на страницу с корзиной покупателя
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
		"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"DETAIL_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"DETAIL_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",	// Использовать код группы из переменной, если не задан раздел элемента
		"DETAIL_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"DETAIL_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"DETAIL_PROPERTY_CODE" => array(	// Свойства
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
		"DETAIL_SET_CANONICAL_URL" => "N",	// Устанавливать канонический URL
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",	// Включить сохранение информации о просмотре товара на детальной странице для старых шаблонов
		"DETAIL_SHOW_PICTURE" => "Y",	// Показывать изображение
		"DETAIL_STRICT_SECTION_CHECK" => "N",	// Строгая проверка раздела для детального показа элемента
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем товары в разделе
		"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки товаров в разделе
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки товаров в разделе
		"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки товаров в разделе
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки" в детальном просмотре
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки" в детальном просмотре
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Подарки" в строке в детальном просмотре
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка" в детальном просмотре
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",	// Текст заголовка "Товары к подарку"
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Товары к подарку" в детальном просмотре
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Товары к подарку" в строке в детальном просмотре
		"GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",	// Текст заголовка "Подарки" в списке
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки" в списке
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Подарки" строке в списке
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка" в списке
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
		"GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
		"GIFTS_SHOW_NAME" => "Y",	// Показывать название
		"GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
		"HIDE_NOT_AVAILABLE" => "N",	// Недоступные товары
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",	// Недоступные торговые предложения
		"IBLOCK_ID" => "8",	// Инфоблок
		"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LINE_ELEMENT_COUNT" => "3",	// Количество элементов, выводимых в одной строке таблицы
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",	// URL на страницу, где будет показан список связанных элементов
		"LINK_IBLOCK_ID" => "",	// ID инфоблока, элементы которого связаны с текущим элементом
		"LINK_IBLOCK_TYPE" => "",	// Тип инфоблока, элементы которого связаны с текущим элементом
		"LINK_PROPERTY_SID" => "",	// Свойство, в котором хранится связь
		"LIST_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства раздела
		"LIST_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства раздела
		"LIST_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства раздела
		"LIST_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "DESC",
			2 => "",
		),
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
		"PAGER_TITLE" => "Товары",	// Название категорий
		"PAGE_ELEMENT_COUNT" => "12",	// Количество элементов на странице
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"PRICE_CODE" => array(	// Тип цены
			0 => "price",
		),
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
		"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
		"PRODUCT_PROPERTIES" => "",	// Характеристики товара, добавляемые в корзину
		"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
		"SECTION_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"SECTION_COUNT_ELEMENTS" => "Y",	// Показывать количество элементов в разделе
		"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
		"SECTION_SHOW_PARENT_NAME" => "Y",	// Показывать заголовок раздела
		"SECTION_TOP_DEPTH" => "2",	// Максимальная отображаемая глубина разделов
		"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SET_STATUS_404" => "Y",	// Устанавливать статус 404
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"SHOW_404" => "Y",	// Показ специальной страницы
		"SHOW_DEACTIVATED" => "N",	// Показывать деактивированные товары
		"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
		"SHOW_TOP_ELEMENTS" => "Y",	// Выводить топ элементов
		"TOP_ELEMENT_COUNT" => "1",	// Количество выводимых элементов
		"TOP_ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем товары в разделе
		"TOP_ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки товаров в разделе
		"TOP_ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки товаров в разделе
		"TOP_ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки товаров в разделе
		"TOP_LINE_ELEMENT_COUNT" => "3",	// Количество элементов, выводимых в одной строке таблицы
		"TOP_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"USER_CONSENT" => "N",	// Запрашивать согласие
		"USER_CONSENT_ID" => "0",	// Соглашение
		"USER_CONSENT_IS_CHECKED" => "Y",	// Галка по умолчанию проставлена
		"USER_CONSENT_IS_LOADED" => "N",	// Загружать текст сразу
		"USE_ALSO_BUY" => "N",	// Показывать блок "С этим товаром покупают"
		"USE_COMPARE" => "N",	// Разрешить сравнение товаров
		"USE_ELEMENT_COUNTER" => "Y",	// Использовать счетчик просмотров
		"USE_FILTER" => "Y",	// Показывать фильтр
		"USE_GIFTS_DETAIL" => "Y",	// Показывать блок "Подарки" в детальном просмотре
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",	// Показывать блок "Товары к подарку" в детальном просмотре
		"USE_GIFTS_SECTION" => "Y",	// Показывать блок "Подарки" в списке
		"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
		"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
		"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
		"USE_REVIEW" => "N",	// Разрешить отзывы
		"USE_STORE" => "N",	// Показывать блок "Количество товара на складе"
		"COMPONENT_TEMPLATE" => "main_catalog",
		"SEF_FOLDER" => "/",	// Каталог ЧПУ (относительно корня сайта)
		"FILE_404" => "",	// Страница для показа (по умолчанию /404.php)
		"FILTER_NAME" => "",	// Фильтр
		"FILTER_FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(	// Тип цены
			0 => "price",
		),
		"TEMPLATE_THEME" => "blue",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"TOP_VIEW_MODE" => "SECTION",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?>
<section class="section glav_cat_section hits_section">
<div class="inner_section clearfix">
	<div class="the_section_head">
		<p class="section_title">
 			<a href="#">Хиты продаж</a>
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
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
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
 			<a href="#">Новинки</a>
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
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
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
						 Требуется помощь в подборе оборудования или консультация?
					</p>
					<p>
						 Отправьте заявку прямо сейчас, мы свяжемся с Вами,<br>
						 проконсультируем и подберем оптимальное оборудование
					</p>
					<div class="the_form_div the_form_div_text_and_submit">
						<input type="text" name="tel" required placeholder="Ваш номер телефона">
						<input type="submit" name="submit1" value="Отправить заявку">
					</div>
					<div class="the_form_div the_form_div_accept">
						<input type="checkbox" class="checkbox-toggle" name="accept" required
							   checked="checked"
							   data-tt-type="square_v"
							   data-tt-label-check="Я cогласен на <a href='/soglasie-na-obrabotku-personalnykh-dannykh/' target=_blank>обработку персональных данных</a>"
							   data-tt-label-uncheck="Вы не дали согласие на обработку персональных данных.">
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
			 Новости и статьи
		</p>
		<a class="the_section_head_all" href="/news/">Все новости и статьи</a>
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
		"DETAIL_PAGER_TITLE" => "Страница",
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
		"PAGER_TITLE" => "Новости",
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
						"AJAX_MODE" => "N",	// Включить режим AJAX
							"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
							"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
							"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
							"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
							"CACHE_TIME" => "3600",	// Время кеширования (сек.)
							"CACHE_TYPE" => "A",	// Тип кеширования
							"CONFIRMATION" => "Y",	// Запрашивать подтверждение подписки по email
							"HIDE_MAILINGS" => "N",	// Скрыть список рассылок, и подписывать на все
							"SET_TITLE" => "N",	// Устанавливать заголовок страницы
							"SHOW_HIDDEN" => "N",	// Показать скрытые рассылки для подписки
							"USER_CONSENT" => "N",	// Запрашивать согласие
							"USER_CONSENT_ID" => "0",	// Соглашение
							"USER_CONSENT_IS_CHECKED" => "Y",	// Галка по умолчанию проставлена
							"USER_CONSENT_IS_LOADED" => "N",	// Загружать текст сразу
							"USE_PERSONALIZATION" => "Y",	// Определять подписку текущего пользователя
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
	<div class="text_toggling_div">
		<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/seo_text.php",Array(),Array("MODE"=>"html")); ?>
	</div>
	<a class="read_more_toggler" href="#"><span>Раскрыть текст</span></a>
</div>
 </section><!--/seo_section-->


<?
require($_SERVER["DOCUMENT_ROOT"]."/include/product_popup.php");
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>