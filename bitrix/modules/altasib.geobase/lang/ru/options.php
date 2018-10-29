<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2017 ALTASIB
 */

$CookiePX = COption::GetOptionString("main", "cookie_name", "BITRIX_SM");

$MESS['ALTASIB_IS'] = "Магазин готовых решений для 1С-Битрикс";
$MESS['ALTASIB_GEOBASE_DESCR'] = 'Модуль получает местоположение пользователя по его IP-адресу и сохраняет эти данные в сессию и, если установлено, в cookies.<br/><br/>
<b>Информация для разработчиков</b><br/>
Данные хранятся в cookies в виде JSON-закодированных массивов: '.$CookiePX.'_ALTASIB_GEOBASE и '.$CookiePX.'_ALTASIB_GEOBASE_CODE,
и в виде обычных массивов: $_SESSION["ALTASIB_GEOBASE"] и $_SESSION["ALTASIB_GEOBASE_CODE"] - автоопределенный и указанный пользователем соответственно.
<br/><br/><div id="altasib_description_open_btn">
	<span class="altasib_description_open_text">Читать дальше</span>
</div>
<div id="altasib_description_full">
Получить данные можно так:
<pre>
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetAddres();
	print_r($arData);
}
// для получения данных КЛАДР, определенных автоматически по местоположению:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetCodeByAddr();
	print_r($arData);
}
// для получения данных КЛАДР, заданных пользователем:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetDataKladr();
	print_r($arData);
}
// для получения местоположения из <a href="/bitrix/admin/sale_location_admin.php" target="_blank">списка местоположений</a>, установленных на сайте:
if(CModule::IncludeModule("altasib.geobase")) {
	$resData = CAltasibGeoBase::GetBXLocations();
	print_r($resData);
}
// Взять данные из cookies:
$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));
print_r($arDataC);
</pre>
<pre>
// Запретить выполнение обработчиков модуля (для выполнения скриптов по cron):
define("NO_GEOBASE", true);
</pre>
<pre>
// для получения местоположений избранных городов:
if(CModule::IncludeModule("altasib.geobase")) {
	// динамические данные
	$rsData = CAltasibGeoBaseAllSelected::GetMoreCities();
	while($arCity = $rsData->Fetch()) {
		$idLocation = CAltasibGeoBase::GetBXLocations($arCity["C_NAME"], $arCity["CTR_NAME_RU"]);
		$arLocs = CSaleLocation::GetByID($idLocation, LANGUAGE_ID);
		print_r($arLocs);
	}
	// закешированные данные
	$arCities = CAltasibGeoBaseSelected::GetMoreCacheCities();
	print_r($arCities);
}
</pre>
<pre>
// для получения данных текущего избранного города:
if(CModule::IncludeModule("altasib.geobase")) {
	$arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
	print_r($arSelCity); // данные текущего города, который соответствует избранному, если есть совпадение
	if(!empty($arSelCity) && count($arSelCity) > 0)
	{
		if(!empty($arSelCity) && $arSelCity["ACTIVE"] == "Y" && intval($arSelCity["ID"])>0)
		{
			$arUFields = CAltasibGeoBaseSelected::GetFieldsCity($arSelCity["ID"], false);
			print_r($arUFields); // распечатка пользоват. свойств для избранного города
		}
	}
	else
	{
		// вывод ближайшего города из избранных
		// string $searchMode - режим поиска:
		// "geo" - по координатам, "region" - по региону, "all" - по координатам и региону
		// bool $userChoiceEn - использовать ли данные выбора пользователя
		$arNData = CAltasibGeoBaseSelected::GetNearestCityFromSelected($searchMode = "all", $userChoiceEn = true);
		print_r($arNData);
	}
}
</pre><br/>
<b>События модуля</b><br/><br/>
<table class="internal altasib_events" width="100%">
	<thead>
		<tr>
			<th>Событие</th>
			<th>Вызывается</th>
			<th>Метод</th>
			<th>С версии</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>OnAfterSetSelectCity</td>
			<td>После выбора города пользователем</td>
			<td>CAltasibGeoBase::SetCodeKladr
			<br/>CAltasibGeoBase::SetCodeMM</td>
			<td>1.1.3</td>
		</tr>
		<tr>
			<td>OnAfterAutoDetectCity</td>
			<td>После автоматического определения города</td>
			<td>CAltasibGeoBase::GetAddres</td>
			<td>1.5.0</td>
		</tr>
		<tr>
			<td>OnBeforeResultCitySearch</td>
			<td>Перед отправкой данных о н/п в строку поиска в компоненте "Выбор&nbsp;города"</td>
			<td>CAltasibGeoBase::CitySearch</td>
			<td>1.9.0</td>
		</tr>
	</tbody>
</table>
<br/>
<pre>
// Пример обработчика события OnAfterAutoDetectCity:
AddEventHandler("altasib.geobase", "OnAfterAutoDetectCity", "AutoDetectCityHandler");
function AutoDetectCityHandler($arFields) {
	if(function_exists("prn_")){
		prn_($arFields); // запись массива в файл
	}
}
</pre>
<pre>
// Пример обработчика события OnBeforeResultCitySearch:
AddEventHandler("altasib.geobase", "OnBeforeResultCitySearch", "BeforeResultCitySearch");
function BeforeResultCitySearch(&$arCity) {
	// Вывод в список поиска только населенных пунктов Алтайского края
	foreach($arCity as $k => $np)
	{
		if($np["REGION"] != "Алтайский Край") // в кодировке UTF-8
		{
			unset($arCity[$k]); // изменение результирующего массива
		}
	}
	return true; // При возврате false ответа клиенту не будет
}
</pre>
<b>JavaScript-события модуля</b><br/><br/>
JavaScript-событие <b>onAfterSetCity</b> вызывается после выбора (установки) города пользователем.<br/>Входные параметры обработчика события: 
(string name, string id, string full_name, string data);<br/>
<pre>// Пример перезагрузки страницы по выбору города:
BX.addCustomEvent("onAfterSetCity", function(city, city_id, full_name){
    location.reload();
});
</pre><br/>
JS-событие <b>onBeforeYourCityOpen</b> вызывается перед показом окна "Ваш город" в шаблоне компонента altasib:geobase.your.city.<br/>
<pre>
// Пример запрета показа окна "Ваш город" и вывод вместо него окна "Выбор города":
BX.addCustomEvent(altasib_geobase, "onBeforeYourCityOpen", function(){
	altasib_geobase.yc_not_open=true; // запрет попапа "Ваш город"
	altasib_geobase.sc_open(); // открытие попапа выбора города
});
</pre><br/><br/>
<div id="altasib_description_close_btn">
	<span class="altasib_description_open_text">Свернуть</span>
</div>
</div>
';
$MESS['ALTASIB_GEOBASE_SET_COOKIE']			= "Сохранять в cookies информацию о местоположении:";
$MESS['ALTASIB_GEOBASE_SET_TIMEOUT']		= "Время выполнения скрипта (с):";
$MESS['ALTASIB_TAB_BD_DATA']				= "Данные";
$MESS['ALTASIB_TAB_TITLE_DATA']				= "Источники определения местоположения, поддерживаемые модулем";
$MESS['ALTASIB_GEOBASE_DB_UPDATE_IPGEOBASE'] = "Обновление баз данных модуля";
$MESS['ALTASIB_TAB_BD_CITIES']				= "Избранные города";
$MESS['ALTASIB_TAB_TITLE_DB_CITIES']		= "Добавление и редактирование списка избранных городов (регионов) модуля";
$MESS['ALTASIB_TITLE_LOAD_FILE']			= "Загрузка архива:";
$MESS['ALTASIB_TITLE_UNPACK_FILE']			= "Распаковка архива:";
$MESS['ALTASIB_TITLE_DB_UPDATE']			= "Обновление базы данных (<a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>):";
$MESS['ALTASIB_NOTICE_UPDATE_AVAILABLE']	= "Доступен обновленный архив данных с сайта <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>.";
$MESS['ALTASIB_NOTICE_UPDATE_NOT_AVAILABLE'] = "Доступных обновлений на сайте <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> нет.";
$MESS['ALTASIB_NOTICE_DBUPDATE_SUCCESSFUL']	= "Обновление базы данных с сайта <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> успешно завершено.";
$MESS['ALTASIB_GEOBASE_GET_UPDATE']			= "Проверять наличие обновлений архивов БД местоположений на сайте <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> автоматически:";
$MESS["ALTASIB_NOTICE_UPDATE_MANUAL_MODE"]	= "Для проверки обновлений с сайта <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> нажмите кнопку \"Проверить обновления\"";

$MESS["ALTASIB_CHECK_UPDATES"]				= "Проверка наличия обновлений на сайте <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>...";
$MESS["ALTASIB_GEOBASE_SOURCE"]				= "Источник определения местоположения:";
$MESS["ALTASIB_GEOBASE_NOT_USING"]			= "Не использовать локальные БД";
$MESS["ALTASIB_GEOBASE_LOCAL_DB"]			= "Локальная база ipgeobase.ru";
$MESS["ALTASIB_GEOBASE_STATISTIC"]			= "Веб-аналитика 1С-Битрикс";
$MESS["ALTASIB_GEOBASE_SOURCE_MM"]			= "Локальная база maxmind.com";
$MESS["ALTASIB_GEOBASE_IPGEOBASE_MM"]		= "Локальные базы ipgeobase.ru и maxmind.com";

$MESS["ALTASIB_GEOBASE_UPDATE"]				= "Обновить";
$MESS["ALTASIB_GEOBASE_CHECK_UPDATE"]		= "Проверить обновления";

$MESS["ALTASIB_GEOBASE_WIN_YOUR_CITY_ENABLE"]	= "Включить <b>автоматический показ</b> всплывающего окна \"Ваш город\":";
$MESS["ALTASIB_GEOBASE_ONLY_SELECT_CITIES"]		= 'Использовать только города из <a title="Избранные города" onclick="tabControl.SelectTab(\'edit3\'); return false;">списка избранных городов</a>, без поля поиска:';


$MESS['ALTASIB_TITLE_CITIES_LIST']	= "Список избранных городов (регионов)";
$MESS['ALTASIB_TABLE_CITY_DELETE']	= "Удалить";
$MESS['ALTASIB_TABLE_CITY_ADD']		= "Добавить";
$MESS['ALTASIB_INP_CITY_ADD']		= "Добавление города (региона) в список избранных городов";
$MESS['ALTASIB_INP_ENTER_CITY']		= "Введите название города (региона)";
$MESS['ALTASIB_TABLE_CITY_NAME']	= "Название";
$MESS['ALTASIB_TABLE_CITY_CODE']	= "Код н/п";
$MESS['ALTASIB_TABLE_SORT']			= "Сорт";
$MESS['ALTASIB_TABLE_DISTRICT']		= "Район";
$MESS['ALTASIB_TABLE_REGION']		= "Регион";
$MESS['ALTASIB_TABLE_COUNTRY_CODE']	= "Код страны";
$MESS['ALTASIB_TABLE_COUNTRY']		= "Cтрана";
$MESS['ALTASIB_TABLE_CITY_ACT']		= "Действие";
$MESS['ALTASIB_TABLE_CITY_UFIELD']		= "Пользовательские поля";
$MESS['ALTASIB_GEOBASE_AUTO_DISPLAY']	= "Автоматический показ";
$MESS['ALTASIB_GEOBASE_GLOBAL_COMPONENTS']	= "Общие настройки компонентов";
$MESS['ALTASIB_GEOBASE_LOCATIONS']	= "Настройки замены местоположения в модуле Интернет-магазин";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_DESCR'] = "\"<b>Ваш город</b>\" - компонент, выводящий всплывающее окно с возможностью подтверждения города посетителя, определенного по его IP адресу, а также ссылку для изменения.";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_TEMPLATES'] = "Шаблон компонента \"Ваш город\", подключаемого автоматически:";
$MESS['ALTASIB_GEOBASE_POPUP_BACK'] = "Затемнять фон при выводе всплывающих окон:";
$MESS['ALTASIB_GEOBASE_REGION_DISABLE'] = "Не выводить названия региона и района:";


$MESS['ALTASIB_GEOBASE_SELECT_CITY_DESCR'] = "\"<b>Выбор города</b>\" - компонент, выводящий ссылку на открытие всплывающего окна для выбора и сохранения города посетителя";
$MESS['ALTASIB_GEOBASE_SELECT_CITY_TEMPLATES'] = "Шаблон компонента \"Выбор города\", подключаемый при вызове компонента автоматически:";

$MESS ['ALTASIB_GEOBASE_SITES'] = "Cайты, на страницах которых задействовать автоматический показ:";
$MESS ['ALTASIB_GEOBASE_TEMPLATE'] = "Шаблоны сайта, в которых задействовать автопоказ:";

$MESS['ALTASIB_GEOBASE_SECTION_LINK'] = "В каких разделах заменять строку \"Местоположение\" для страницы оформления заказа (список через запятую, пустое значение означает 'отображать без ограничений')<br />Пример: <i>'/personal/order/make/, /personal/'</i>";
$MESS['ALTASIB_GEOBASE_SALE_LOCATION'] = "Местоположение страны по умолчанию:";
$MESS['ALTASIB_GEOBASE_URL_NOT_FOUND'] = "Запрашиваемый URL адрес удаленного сервера не найден.";
$MESS['ALTASIB_GEOBASE_SET_SQL'] = "Поддержка длинных SQL-запросов:<br/><i style='font-size:smaller;'>установка константы \"SQL_BIG_SELECTS\"</i>";
$MESS['ALTASIB_GEOBASE_RUSSIA'] = "Россия";
$MESS['ALTASIB_GEOBASE_RF'] = "Российская Федерация";

$MESS['ALTASIB_GEOBASE_JQUERY'] = "Подключать jQuery:";
$MESS['ALTASIB_GEOBASE_JQUERY_NOT'] = "На сайте уже подключен jQuery";
$MESS['ALTASIB_GEOBASE_JQUERY_YES'] = "Да, подключать";
$MESS['ALTASIB_GEOBASE_JQUERY_2'] = "Подключать jQuery v.2";

$MESS['ALTASIB_GEOBASE_FIELD_LOC_IND'] = "Идентификатор элемента поля ввода местоположения физического лица на странице оформления заказа:";
$MESS['ALTASIB_GEOBASE_FIELD_LOC_LEG'] = "Идентификатор элемента поля ввода местоположения юридического лица на странице оформления заказа:";

$MESS['ALTASIB_NOTICE_MM_UPDATE_AVAILABLE'] = "Доступен обновленный архив данных GeoLite с сайта <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a>.";
$MESS['ALTASIB_NOTICE_MM_UPDATE_NOT_AVAILABLE'] = "Доступных обновлений на сайте <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> не найдено.";
$MESS['ALTASIB_NOTICE_MM_DBUPDATE_SUCCESSFUL'] = "Обновление файла базы данных GeoLite с сайта <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> успешно завершено.";
$MESS['ALTASIB_GEOBASE_MM_GET_UPDATE'] = "Проверять наличие обновлений GeoLite базы на сайте <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> автоматически:";
$MESS["ALTASIB_NOTICE_MM_UPDATE_MANUAL_MODE"] = "Для проверки обновлений с сайта <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a> нажмите кнопку \"Проверить обновления\"";

$MESS['ALTASIB_TITLE_MM_DB_UPDATE'] = "Обновление базы данных (<a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a>):";
$MESS["ALTASIB_CHECK_MM_UPDATES"] = "Проверка наличия обновлений на сайте <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a>...";

$MESS['ALTASIB_GEOBASE_DEMO_MODE'] = "Модуль работает в демонстрационном режиме. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>Купить версию без ограничений</a>";
$MESS['ALTASIB_GEOBASE_DEMO_EXPIRED'] = "Демонстрационный период работы модуля закончился. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>Купить модуль</a>";
$MESS['ALTASIB_GEOBASE_NF'] = "Модуль #MODULE# не найден";

$MESS['ALTASIB_GEOBASE_AUTODETECT_EN'] = "Добавлять автоматически определенный город к списку избранных городов:";
$MESS['ALTASIB_GEOBASE_CITIES_WORLD_ENABLE'] = "Показывать города мира в строке поиска компонента \"Выбор города\":";
$MESS['ALTASIB_GEOBASE_EDT_UF'] = "Редактирование пользовательских полей";
$MESS['ALTASIB_GEOBASE_UF_LIST'] = "Список пользовательских полей";
$MESS['ALTASIB_GEOBASE_UF_NOTE'] = "Редактирование пользовательских полей модуля можно произвести на странице <a href='/bitrix/admin/userfield_admin.php?lang=ru&set_filter=Y&find=#ENTITY_ID#&find_type=ENTITY_ID'>Пользовательские поля</a>";
$MESS['ALTASIB_GEOBASE_REDIRECT_ENABLE'] = "Осуществлять переход по ссылке, указанной в пользовательском поле города, при выборе этого города:<br/><em style='font-size:smaller;'>Если ссылки нет, будет осуществлена перезагрузка страницы.</em>";

$MESS['ALTASIB_GEOBASE_FIELD_TEMPLATE'] = "Шаблон идентификатора элемента для полей ввода местоположений на странице оформления заказа:";
$MESS['ALTASIB_GEOBASE_FIELD_LOCPERSON'] = "Свойство местоположение для типа плательщика ##PERSON_TYPE_ID# <b>#PERSON_TYPE_NAME#</b> [#SITES#]:";

$MESS['ALTASIB_GEOBASE_MODE_LOC'] = "Режим вывода местоположений:";
$MESS['ALTASIB_GEOBASE_MODE_CITIES'] = "Только города (н/п)";
$MESS['ALTASIB_GEOBASE_MODE_REGIONS'] = "Только регионы";
$MESS['ALTASIB_GEOBASE_MODE_ALL'] = "Города и регионы";

$MESS['ALTASIB_GEOBASE_AUTODT_HIT_EN'] = "Определять автоматически город на хитах:<br/><i style='font-size:smaller;'>записывать данные в сессию, даже если не включены компоненты <b>автопоказа</b></i>";
$MESS['ALTASIB_GEOBASE_REDIRECT_ONHIT_EN'] = "Осуществлять перенаправление по ссылке, указанной в пользовательском поле города, при заходе на сайт посетителем, подтвердившим этот город:";

$MESS['ALTASIB_GEOBASE_GEOIP_SETS'] = "Настройки сервиса geoip.elib.ru";
$MESS['ALTASIB_GEOBASE_GEOIP_REG'] = "Для использования сервиса <a href='http://geoip.top/' title='Geo IP'>geoip.elib.ru</a> требуется <a href='https://geoip.top/cgi-bin/kernel.pl?Reg=1' title='Geo IP - Личный кабинет'>регистрация</a> и последующее добавление сайта в список \"Мои сайты\" с получением <b>кода сайта</b>.";

$MESS['ALTASIB_GEOBASE_IPGEOBASE_ENABLE'] = "Использовать онлайн-сервис <a href='http://ipgeobase.ru/' target='_blank' title='География российских и украинских IP-адресов. Поиск местонахождения (города) IP-адреса'>ipgeobase.ru</a>:";
$MESS['ALTASIB_GEOBASE_GEOIP_ENABLE'] = "Использовать онлайн-сервис <a href='http://geoip.elib.ru/' target='_blank' title='Сервис \"Geo IP\" - Определение географических координат по IP адресу'>geoip.elib.ru</a>:<br/><i style='font-size:smaller;'>необходима регистрация и <a href=\"#GeoIPSettings\">добавление</a> кода сайта</i>";
$MESS['ALTASIB_GEOBASE_GEOIP_CODE_SITES'] = "Код SID сайта #SITE# в системе <a href='http://geoip.elib.ru/' target='_blank' title='Сервис \"Geo IP\"'>geoip.elib.ru</a>:";
$MESS['ALTASIB_GEOBASE_REDIRECT_SAVE_CURPAGE'] = "Осуществлять перенаправление с сохранением относительного пути:<br/><i style='font-size:smaller;'>К ссылке, указанной в пользовательском поле города, добавится текущий относительный путь</i>";
$MESS['ALTASIB_GEOBASE_CITIES_ONLY_LARGE'] = "Показывать только города, крупные поселки и районные центры из населенных пунктов в <u>поле поиска</u>:";
$MESS['ALTASIB_GEOBASE_RDR_SETTS']	= "Настройки перенаправлений";
$MESS['ALTASIB_GEOBASE_RDR_TIME'] = "В течение какого времени помнить о совершенном перенаправлении:<br/><i style='font-size:smaller;'>Перенаправление для посетителей запоминается в cookies</i>.";

$MESS['ALTASIB_GEOBASE_RDR_NOT_TIME']	= "Не запоминать";
$MESS['ALTASIB_GEOBASE_RDR_SESSIONS']	= "Сессия";
$MESS['ALTASIB_GEOBASE_RDR_HOUR']	= "1 час";
$MESS['ALTASIB_GEOBASE_RDR_DAY']	= "1 сутки";
$MESS['ALTASIB_GEOBASE_RDR_WEEK']	= "1 неделя";
$MESS['ALTASIB_GEOBASE_RDR_MONTH']	= "1 месяц";
$MESS['ALTASIB_GEOBASE_RDR_YEAR']	= "1 год";

$MESS['ALTASIB_GEOBASE_NOT_SET'] = "<не установлено>";
$MESS['ALTASIB_GEOBASE_SPREAD_COOKIE'] = "Распространять куку выбранного города на все домены";
$MESS['ALTASIB_GEOBASE_LOOCKUP'] = "Определение города и страны в модуле статистики";
$MESS['ALTASIB_GEOBASE_LOOCKUP_USR'] = "Использовать установленные пользователем данные в источнике определения модуля <a href=\"\" target=\"_blank\">Статистики</a>";
$MESS['ALTASIB_GEOBASE_SRV'] = 'Распечатка ключей глобального массива $_SERVER';
$MESS['ALTASIB_GEOBASE_SRV_NOTE'] = "Проверка на передачу сервером данных об IP-адресе посетителя.<br/>";
$MESS['ALTASIB_GEOBASE_SRV_DETECT'] = "Определен <u>#IP#</u> адрес.";
$MESS['ALTASIB_GEOBASE_SRV_ND'] = "Глобальный IP адрес клиента <u>не определен</u>. Необходимо обратиться к хостеру.";
$MESS['ALTASIB_GEOBASE_KEY'] = "Ключ";
$MESS['ALTASIB_GEOBASE_VAL'] = "Значение";
$MESS['ALTASIB_GEOBASE_SRV_CHK'] = "Проверка определения IP-адреса";
$MESS['ALTASIB_GEOBASE_YOUR_MODE'] = "Режим вывода окна подтверждения города:";
$MESS['ALTASIB_GEOBASE_LARGE'] = "Обычное всплывающее окно";
$MESS['ALTASIB_GEOBASE_SMALL'] = "Компактное окно";

$MESS['ALTASIB_GEOBASE_YC_MODE'] = "Компактное окно подтверждения города выводится в публичке только при наличии вызова компонента \"Выбор города\" (altasib:geobase.select.city) со строкой текущего города.";

$MESS['AGB_SCHEME_BRIGHT'] = "Яркая";
$MESS['AGB_SCHEME_PALE'] = "Нежно-бледная";
$MESS['AGB_OTHER'] = "(другое)";

$MESS['AGB_SCHEME_GB'] = "Синевато-зелёный";
$MESS['AGB_SCHEME_GG'] = "Защитно-синий";
$MESS['AGB_SCHEME_R'] = "Красный";
$MESS['AGB_SCHEME_G'] = "Зеленый";
$MESS['AGB_SCHEME_P'] = "Пурпурный";
$MESS['AGB_SCHEME_O'] = "Ярко-красно-оранжевый";
$MESS['AGB_SCHEME_S'] = "Перламутровый светло-серый";
$MESS['AGB_SCHEME_SS'] = "Голубино-синий";

$MESS['AGB_SCHEME_GB_L'] = "Светлый голубовато-зеленый";
$MESS['AGB_SCHEME_GG_L'] = "Бледно-синий";
$MESS['AGB_SCHEME_R_L'] = "Пастельно-розовый";
$MESS['AGB_SCHEME_G_L'] = "Умеренно зелёный";
$MESS['AGB_SCHEME_P_L'] = "Светлый пурпурно-синий";
$MESS['AGB_SCHEME_O_L'] = "Светло-коралловый";
$MESS['AGB_SCHEME_S_L'] = "Дымчато-белый";
$MESS['AGB_SCHEME_SS_L'] = "Бледно-серый";

$MESS['ALTASIB_GEOBASE_COLOR_SCHEME'] = "Цвет темы";
$MESS['AGB_COLOR_SCHEME'] = "Цветовая схема";
$MESS['AGB_COLOR_OTHER'] = "Другой цвет, в формате #XXXXXX";
$MESS['ALTASIB_GEOBASE_COLOR_SHM'] = "Настройки цвета";

$MESS['ALTASIB_GEOBASE_COLOR_NOTE'] = "Для задания цвета вручную выберите <a href='#' onclick='#ON_CLICK#'>пустую</a> цветовую схему.";
