<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2017 ALTASIB
 */

$CookiePX = COption::GetOptionString("main", "cookie_name", "BITRIX_SM");

$MESS['ALTASIB_IS'] = "������� ������� ������� ��� 1�-�������";
$MESS['ALTASIB_GEOBASE_DESCR'] = '������ �������� �������������� ������������ �� ��� IP-������ � ��������� ��� ������ � ������ �, ���� �����������, � cookies.<br/><br/>
<b>���������� ��� �������������</b><br/>
������ �������� � cookies � ���� JSON-�������������� ��������: '.$CookiePX.'_ALTASIB_GEOBASE � '.$CookiePX.'_ALTASIB_GEOBASE_CODE,
� � ���� ������� ��������: $_SESSION["ALTASIB_GEOBASE"] � $_SESSION["ALTASIB_GEOBASE_CODE"] - ���������������� � ��������� ������������� ��������������.
<br/><br/><div id="altasib_description_open_btn">
	<span class="altasib_description_open_text">������ ������</span>
</div>
<div id="altasib_description_full">
�������� ������ ����� ���:
<pre>
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetAddres();
	print_r($arData);
}
// ��� ��������� ������ �����, ������������ ������������� �� ��������������:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetCodeByAddr();
	print_r($arData);
}
// ��� ��������� ������ �����, �������� �������������:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetDataKladr();
	print_r($arData);
}
// ��� ��������� �������������� �� <a href="/bitrix/admin/sale_location_admin.php" target="_blank">������ ��������������</a>, ������������� �� �����:
if(CModule::IncludeModule("altasib.geobase")) {
	$resData = CAltasibGeoBase::GetBXLocations();
	print_r($resData);
}
// ����� ������ �� cookies:
$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));
print_r($arDataC);
</pre>
<pre>
// ��������� ���������� ������������ ������ (��� ���������� �������� �� cron):
define("NO_GEOBASE", true);
</pre>
<pre>
// ��� ��������� �������������� ��������� �������:
if(CModule::IncludeModule("altasib.geobase")) {
	// ������������ ������
	$rsData = CAltasibGeoBaseAllSelected::GetMoreCities();
	while($arCity = $rsData->Fetch()) {
		$idLocation = CAltasibGeoBase::GetBXLocations($arCity["C_NAME"], $arCity["CTR_NAME_RU"]);
		$arLocs = CSaleLocation::GetByID($idLocation, LANGUAGE_ID);
		print_r($arLocs);
	}
	// �������������� ������
	$arCities = CAltasibGeoBaseSelected::GetMoreCacheCities();
	print_r($arCities);
}
</pre>
<pre>
// ��� ��������� ������ �������� ���������� ������:
if(CModule::IncludeModule("altasib.geobase")) {
	$arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
	print_r($arSelCity); // ������ �������� ������, ������� ������������� ����������, ���� ���� ����������
	if(!empty($arSelCity) && count($arSelCity) > 0)
	{
		if(!empty($arSelCity) && $arSelCity["ACTIVE"] == "Y" && intval($arSelCity["ID"])>0)
		{
			$arUFields = CAltasibGeoBaseSelected::GetFieldsCity($arSelCity["ID"], false);
			print_r($arUFields); // ���������� ���������. ������� ��� ���������� ������
		}
	}
	else
	{
		// ����� ���������� ������ �� ���������
		// string $searchMode - ����� ������:
		// "geo" - �� �����������, "region" - �� �������, "all" - �� ����������� � �������
		// bool $userChoiceEn - ������������ �� ������ ������ ������������
		$arNData = CAltasibGeoBaseSelected::GetNearestCityFromSelected($searchMode = "all", $userChoiceEn = true);
		print_r($arNData);
	}
}
</pre><br/>
<b>������� ������</b><br/><br/>
<table class="internal altasib_events" width="100%">
	<thead>
		<tr>
			<th>�������</th>
			<th>����������</th>
			<th>�����</th>
			<th>� ������</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>OnAfterSetSelectCity</td>
			<td>����� ������ ������ �������������</td>
			<td>CAltasibGeoBase::SetCodeKladr
			<br/>CAltasibGeoBase::SetCodeMM</td>
			<td>1.1.3</td>
		</tr>
		<tr>
			<td>OnAfterAutoDetectCity</td>
			<td>����� ��������������� ����������� ������</td>
			<td>CAltasibGeoBase::GetAddres</td>
			<td>1.5.0</td>
		</tr>
		<tr>
			<td>OnBeforeResultCitySearch</td>
			<td>����� ��������� ������ � �/� � ������ ������ � ���������� "�����&nbsp;������"</td>
			<td>CAltasibGeoBase::CitySearch</td>
			<td>1.9.0</td>
		</tr>
	</tbody>
</table>
<br/>
<pre>
// ������ ����������� ������� OnAfterAutoDetectCity:
AddEventHandler("altasib.geobase", "OnAfterAutoDetectCity", "AutoDetectCityHandler");
function AutoDetectCityHandler($arFields) {
	if(function_exists("prn_")){
		prn_($arFields); // ������ ������� � ����
	}
}
</pre>
<pre>
// ������ ����������� ������� OnBeforeResultCitySearch:
AddEventHandler("altasib.geobase", "OnBeforeResultCitySearch", "BeforeResultCitySearch");
function BeforeResultCitySearch(&$arCity) {
	// ����� � ������ ������ ������ ���������� ������� ���������� ����
	foreach($arCity as $k => $np)
	{
		if($np["REGION"] != "��������� ����") // � ��������� UTF-8
		{
			unset($arCity[$k]); // ��������� ��������������� �������
		}
	}
	return true; // ��� �������� false ������ ������� �� �����
}
</pre>
<b>JavaScript-������� ������</b><br/><br/>
JavaScript-������� <b>onAfterSetCity</b> ���������� ����� ������ (���������) ������ �������������.<br/>������� ��������� ����������� �������: 
(string name, string id, string full_name, string data);<br/>
<pre>// ������ ������������ �������� �� ������ ������:
BX.addCustomEvent("onAfterSetCity", function(city, city_id, full_name){
    location.reload();
});
</pre><br/>
JS-������� <b>onBeforeYourCityOpen</b> ���������� ����� ������� ���� "��� �����" � ������� ���������� altasib:geobase.your.city.<br/>
<pre>
// ������ ������� ������ ���� "��� �����" � ����� ������ ���� ���� "����� ������":
BX.addCustomEvent(altasib_geobase, "onBeforeYourCityOpen", function(){
	altasib_geobase.yc_not_open=true; // ������ ������ "��� �����"
	altasib_geobase.sc_open(); // �������� ������ ������ ������
});
</pre><br/><br/>
<div id="altasib_description_close_btn">
	<span class="altasib_description_open_text">��������</span>
</div>
</div>
';
$MESS['ALTASIB_GEOBASE_SET_COOKIE']			= "��������� � cookies ���������� � ��������������:";
$MESS['ALTASIB_GEOBASE_SET_TIMEOUT']		= "����� ���������� ������� (�):";
$MESS['ALTASIB_TAB_BD_DATA']				= "������";
$MESS['ALTASIB_TAB_TITLE_DATA']				= "��������� ����������� ��������������, �������������� �������";
$MESS['ALTASIB_GEOBASE_DB_UPDATE_IPGEOBASE'] = "���������� ��� ������ ������";
$MESS['ALTASIB_TAB_BD_CITIES']				= "��������� ������";
$MESS['ALTASIB_TAB_TITLE_DB_CITIES']		= "���������� � �������������� ������ ��������� ������� (��������) ������";
$MESS['ALTASIB_TITLE_LOAD_FILE']			= "�������� ������:";
$MESS['ALTASIB_TITLE_UNPACK_FILE']			= "���������� ������:";
$MESS['ALTASIB_TITLE_DB_UPDATE']			= "���������� ���� ������ (<a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>):";
$MESS['ALTASIB_NOTICE_UPDATE_AVAILABLE']	= "�������� ����������� ����� ������ � ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>.";
$MESS['ALTASIB_NOTICE_UPDATE_NOT_AVAILABLE'] = "��������� ���������� �� ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> ���.";
$MESS['ALTASIB_NOTICE_DBUPDATE_SUCCESSFUL']	= "���������� ���� ������ � ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> ������� ���������.";
$MESS['ALTASIB_GEOBASE_GET_UPDATE']			= "��������� ������� ���������� ������� �� �������������� �� ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> �������������:";
$MESS["ALTASIB_NOTICE_UPDATE_MANUAL_MODE"]	= "��� �������� ���������� � ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> ������� ������ \"��������� ����������\"";

$MESS["ALTASIB_CHECK_UPDATES"]				= "�������� ������� ���������� �� ����� <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>...";
$MESS["ALTASIB_GEOBASE_SOURCE"]				= "�������� ����������� ��������������:";
$MESS["ALTASIB_GEOBASE_NOT_USING"]			= "�� ������������ ��������� ��";
$MESS["ALTASIB_GEOBASE_LOCAL_DB"]			= "��������� ���� ipgeobase.ru";
$MESS["ALTASIB_GEOBASE_STATISTIC"]			= "���-��������� 1�-�������";
$MESS["ALTASIB_GEOBASE_SOURCE_MM"]			= "��������� ���� maxmind.com";
$MESS["ALTASIB_GEOBASE_IPGEOBASE_MM"]		= "��������� ���� ipgeobase.ru � maxmind.com";

$MESS["ALTASIB_GEOBASE_UPDATE"]				= "��������";
$MESS["ALTASIB_GEOBASE_CHECK_UPDATE"]		= "��������� ����������";

$MESS["ALTASIB_GEOBASE_WIN_YOUR_CITY_ENABLE"]	= "�������� <b>�������������� �����</b> ������������ ���� \"��� �����\":";
$MESS["ALTASIB_GEOBASE_ONLY_SELECT_CITIES"]		= '������������ ������ ������ �� <a title="��������� ������" onclick="tabControl.SelectTab(\'edit3\'); return false;">������ ��������� �������</a>, ��� ���� ������:';


$MESS['ALTASIB_TITLE_CITIES_LIST']	= "������ ��������� ������� (��������)";
$MESS['ALTASIB_TABLE_CITY_DELETE']	= "�������";
$MESS['ALTASIB_TABLE_CITY_ADD']		= "��������";
$MESS['ALTASIB_INP_CITY_ADD']		= "���������� ������ (�������) � ������ ��������� �������";
$MESS['ALTASIB_INP_ENTER_CITY']		= "������� �������� ������ (�������)";
$MESS['ALTASIB_TABLE_CITY_NAME']	= "��������";
$MESS['ALTASIB_TABLE_CITY_CODE']	= "��� �/�";
$MESS['ALTASIB_TABLE_SORT']			= "����";
$MESS['ALTASIB_TABLE_DISTRICT']		= "�����";
$MESS['ALTASIB_TABLE_REGION']		= "������";
$MESS['ALTASIB_TABLE_COUNTRY_CODE']	= "��� ������";
$MESS['ALTASIB_TABLE_COUNTRY']		= "C�����";
$MESS['ALTASIB_TABLE_CITY_ACT']		= "��������";
$MESS['ALTASIB_TABLE_CITY_UFIELD']		= "���������������� ����";
$MESS['ALTASIB_GEOBASE_AUTO_DISPLAY']	= "�������������� �����";
$MESS['ALTASIB_GEOBASE_GLOBAL_COMPONENTS']	= "����� ��������� �����������";
$MESS['ALTASIB_GEOBASE_LOCATIONS']	= "��������� ������ �������������� � ������ ��������-�������";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_DESCR'] = "\"<b>��� �����</b>\" - ���������, ��������� ����������� ���� � ������������ ������������� ������ ����������, ������������� �� ��� IP ������, � ����� ������ ��� ���������.";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_TEMPLATES'] = "������ ���������� \"��� �����\", ������������� �������������:";
$MESS['ALTASIB_GEOBASE_POPUP_BACK'] = "��������� ��� ��� ������ ����������� ����:";
$MESS['ALTASIB_GEOBASE_REGION_DISABLE'] = "�� �������� �������� ������� � ������:";


$MESS['ALTASIB_GEOBASE_SELECT_CITY_DESCR'] = "\"<b>����� ������</b>\" - ���������, ��������� ������ �� �������� ������������ ���� ��� ������ � ���������� ������ ����������";
$MESS['ALTASIB_GEOBASE_SELECT_CITY_TEMPLATES'] = "������ ���������� \"����� ������\", ������������ ��� ������ ���������� �������������:";

$MESS ['ALTASIB_GEOBASE_SITES'] = "C����, �� ��������� ������� ������������� �������������� �����:";
$MESS ['ALTASIB_GEOBASE_TEMPLATE'] = "������� �����, � ������� ������������� ���������:";

$MESS['ALTASIB_GEOBASE_SECTION_LINK'] = "� ����� �������� �������� ������ \"��������������\" ��� �������� ���������� ������ (������ ����� �������, ������ �������� �������� '���������� ��� �����������')<br />������: <i>'/personal/order/make/, /personal/'</i>";
$MESS['ALTASIB_GEOBASE_SALE_LOCATION'] = "�������������� ������ �� ���������:";
$MESS['ALTASIB_GEOBASE_URL_NOT_FOUND'] = "������������� URL ����� ���������� ������� �� ������.";
$MESS['ALTASIB_GEOBASE_SET_SQL'] = "��������� ������� SQL-��������:<br/><i style='font-size:smaller;'>��������� ��������� \"SQL_BIG_SELECTS\"</i>";
$MESS['ALTASIB_GEOBASE_RUSSIA'] = "������";
$MESS['ALTASIB_GEOBASE_RF'] = "���������� ���������";

$MESS['ALTASIB_GEOBASE_JQUERY'] = "���������� jQuery:";
$MESS['ALTASIB_GEOBASE_JQUERY_NOT'] = "�� ����� ��� ��������� jQuery";
$MESS['ALTASIB_GEOBASE_JQUERY_YES'] = "��, ����������";
$MESS['ALTASIB_GEOBASE_JQUERY_2'] = "���������� jQuery v.2";

$MESS['ALTASIB_GEOBASE_FIELD_LOC_IND'] = "������������� �������� ���� ����� �������������� ����������� ���� �� �������� ���������� ������:";
$MESS['ALTASIB_GEOBASE_FIELD_LOC_LEG'] = "������������� �������� ���� ����� �������������� ������������ ���� �� �������� ���������� ������:";

$MESS['ALTASIB_NOTICE_MM_UPDATE_AVAILABLE'] = "�������� ����������� ����� ������ GeoLite � ����� <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a>.";
$MESS['ALTASIB_NOTICE_MM_UPDATE_NOT_AVAILABLE'] = "��������� ���������� �� ����� <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> �� �������.";
$MESS['ALTASIB_NOTICE_MM_DBUPDATE_SUCCESSFUL'] = "���������� ����� ���� ������ GeoLite � ����� <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> ������� ���������.";
$MESS['ALTASIB_GEOBASE_MM_GET_UPDATE'] = "��������� ������� ���������� GeoLite ���� �� ����� <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> �������������:";
$MESS["ALTASIB_NOTICE_MM_UPDATE_MANUAL_MODE"] = "��� �������� ���������� � ����� <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a> ������� ������ \"��������� ����������\"";

$MESS['ALTASIB_TITLE_MM_DB_UPDATE'] = "���������� ���� ������ (<a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a>):";
$MESS["ALTASIB_CHECK_MM_UPDATES"] = "�������� ������� ���������� �� ����� <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a>...";

$MESS['ALTASIB_GEOBASE_DEMO_MODE'] = "������ �������� � ���������������� ������. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>������ ������ ��� �����������</a>";
$MESS['ALTASIB_GEOBASE_DEMO_EXPIRED'] = "���������������� ������ ������ ������ ����������. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>������ ������</a>";
$MESS['ALTASIB_GEOBASE_NF'] = "������ #MODULE# �� ������";

$MESS['ALTASIB_GEOBASE_AUTODETECT_EN'] = "��������� ������������� ������������ ����� � ������ ��������� �������:";
$MESS['ALTASIB_GEOBASE_CITIES_WORLD_ENABLE'] = "���������� ������ ���� � ������ ������ ���������� \"����� ������\":";
$MESS['ALTASIB_GEOBASE_EDT_UF'] = "�������������� ���������������� �����";
$MESS['ALTASIB_GEOBASE_UF_LIST'] = "������ ���������������� �����";
$MESS['ALTASIB_GEOBASE_UF_NOTE'] = "�������������� ���������������� ����� ������ ����� ���������� �� �������� <a href='/bitrix/admin/userfield_admin.php?lang=ru&set_filter=Y&find=#ENTITY_ID#&find_type=ENTITY_ID'>���������������� ����</a>";
$MESS['ALTASIB_GEOBASE_REDIRECT_ENABLE'] = "������������ ������� �� ������, ��������� � ���������������� ���� ������, ��� ������ ����� ������:<br/><em style='font-size:smaller;'>���� ������ ���, ����� ������������ ������������ ��������.</em>";

$MESS['ALTASIB_GEOBASE_FIELD_TEMPLATE'] = "������ �������������� �������� ��� ����� ����� �������������� �� �������� ���������� ������:";
$MESS['ALTASIB_GEOBASE_FIELD_LOCPERSON'] = "�������� �������������� ��� ���� ����������� ##PERSON_TYPE_ID# <b>#PERSON_TYPE_NAME#</b> [#SITES#]:";

$MESS['ALTASIB_GEOBASE_MODE_LOC'] = "����� ������ ��������������:";
$MESS['ALTASIB_GEOBASE_MODE_CITIES'] = "������ ������ (�/�)";
$MESS['ALTASIB_GEOBASE_MODE_REGIONS'] = "������ �������";
$MESS['ALTASIB_GEOBASE_MODE_ALL'] = "������ � �������";

$MESS['ALTASIB_GEOBASE_AUTODT_HIT_EN'] = "���������� ������������� ����� �� �����:<br/><i style='font-size:smaller;'>���������� ������ � ������, ���� ���� �� �������� ���������� <b>����������</b></i>";
$MESS['ALTASIB_GEOBASE_REDIRECT_ONHIT_EN'] = "������������ ��������������� �� ������, ��������� � ���������������� ���� ������, ��� ������ �� ���� �����������, ������������� ���� �����:";

$MESS['ALTASIB_GEOBASE_GEOIP_SETS'] = "��������� ������� geoip.elib.ru";
$MESS['ALTASIB_GEOBASE_GEOIP_REG'] = "��� ������������� ������� <a href='http://geoip.top/' title='Geo IP'>geoip.elib.ru</a> ��������� <a href='https://geoip.top/cgi-bin/kernel.pl?Reg=1' title='Geo IP - ������ �������'>�����������</a> � ����������� ���������� ����� � ������ \"��� �����\" � ���������� <b>���� �����</b>.";

$MESS['ALTASIB_GEOBASE_IPGEOBASE_ENABLE'] = "������������ ������-������ <a href='http://ipgeobase.ru/' target='_blank' title='��������� ���������� � ���������� IP-�������. ����� ��������������� (������) IP-������'>ipgeobase.ru</a>:";
$MESS['ALTASIB_GEOBASE_GEOIP_ENABLE'] = "������������ ������-������ <a href='http://geoip.elib.ru/' target='_blank' title='������ \"Geo IP\" - ����������� �������������� ��������� �� IP ������'>geoip.elib.ru</a>:<br/><i style='font-size:smaller;'>���������� ����������� � <a href=\"#GeoIPSettings\">����������</a> ���� �����</i>";
$MESS['ALTASIB_GEOBASE_GEOIP_CODE_SITES'] = "��� SID ����� #SITE# � ������� <a href='http://geoip.elib.ru/' target='_blank' title='������ \"Geo IP\"'>geoip.elib.ru</a>:";
$MESS['ALTASIB_GEOBASE_REDIRECT_SAVE_CURPAGE'] = "������������ ��������������� � ����������� �������������� ����:<br/><i style='font-size:smaller;'>� ������, ��������� � ���������������� ���� ������, ��������� ������� ������������� ����</i>";
$MESS['ALTASIB_GEOBASE_CITIES_ONLY_LARGE'] = "���������� ������ ������, ������� ������� � �������� ������ �� ���������� ������� � <u>���� ������</u>:";
$MESS['ALTASIB_GEOBASE_RDR_SETTS']	= "��������� ���������������";
$MESS['ALTASIB_GEOBASE_RDR_TIME'] = "� ������� ������ ������� ������� � ����������� ���������������:<br/><i style='font-size:smaller;'>��������������� ��� ����������� ������������ � cookies</i>.";

$MESS['ALTASIB_GEOBASE_RDR_NOT_TIME']	= "�� ����������";
$MESS['ALTASIB_GEOBASE_RDR_SESSIONS']	= "������";
$MESS['ALTASIB_GEOBASE_RDR_HOUR']	= "1 ���";
$MESS['ALTASIB_GEOBASE_RDR_DAY']	= "1 �����";
$MESS['ALTASIB_GEOBASE_RDR_WEEK']	= "1 ������";
$MESS['ALTASIB_GEOBASE_RDR_MONTH']	= "1 �����";
$MESS['ALTASIB_GEOBASE_RDR_YEAR']	= "1 ���";

$MESS['ALTASIB_GEOBASE_NOT_SET'] = "<�� �����������>";
$MESS['ALTASIB_GEOBASE_SPREAD_COOKIE'] = "�������������� ���� ���������� ������ �� ��� ������";
$MESS['ALTASIB_GEOBASE_LOOCKUP'] = "����������� ������ � ������ � ������ ����������";
$MESS['ALTASIB_GEOBASE_LOOCKUP_USR'] = "������������ ������������� ������������� ������ � ��������� ����������� ������ <a href=\"\" target=\"_blank\">����������</a>";
$MESS['ALTASIB_GEOBASE_SRV'] = '���������� ������ ����������� ������� $_SERVER';
$MESS['ALTASIB_GEOBASE_SRV_NOTE'] = "�������� �� �������� �������� ������ �� IP-������ ����������.<br/>";
$MESS['ALTASIB_GEOBASE_SRV_DETECT'] = "��������� <u>#IP#</u> �����.";
$MESS['ALTASIB_GEOBASE_SRV_ND'] = "���������� IP ����� ������� <u>�� ���������</u>. ���������� ���������� � �������.";
$MESS['ALTASIB_GEOBASE_KEY'] = "����";
$MESS['ALTASIB_GEOBASE_VAL'] = "��������";
$MESS['ALTASIB_GEOBASE_SRV_CHK'] = "�������� ����������� IP-������";
$MESS['ALTASIB_GEOBASE_YOUR_MODE'] = "����� ������ ���� ������������� ������:";
$MESS['ALTASIB_GEOBASE_LARGE'] = "������� ����������� ����";
$MESS['ALTASIB_GEOBASE_SMALL'] = "���������� ����";

$MESS['ALTASIB_GEOBASE_YC_MODE'] = "���������� ���� ������������� ������ ��������� � �������� ������ ��� ������� ������ ���������� \"����� ������\" (altasib:geobase.select.city) �� ������� �������� ������.";

$MESS['AGB_SCHEME_BRIGHT'] = "�����";
$MESS['AGB_SCHEME_PALE'] = "�����-�������";
$MESS['AGB_OTHER'] = "(������)";

$MESS['AGB_SCHEME_GB'] = "��������-������";
$MESS['AGB_SCHEME_GG'] = "�������-�����";
$MESS['AGB_SCHEME_R'] = "�������";
$MESS['AGB_SCHEME_G'] = "�������";
$MESS['AGB_SCHEME_P'] = "���������";
$MESS['AGB_SCHEME_O'] = "����-������-���������";
$MESS['AGB_SCHEME_S'] = "������������� ������-�����";
$MESS['AGB_SCHEME_SS'] = "��������-�����";

$MESS['AGB_SCHEME_GB_L'] = "������� ����������-�������";
$MESS['AGB_SCHEME_GG_L'] = "������-�����";
$MESS['AGB_SCHEME_R_L'] = "���������-�������";
$MESS['AGB_SCHEME_G_L'] = "�������� ������";
$MESS['AGB_SCHEME_P_L'] = "������� ��������-�����";
$MESS['AGB_SCHEME_O_L'] = "������-����������";
$MESS['AGB_SCHEME_S_L'] = "�������-�����";
$MESS['AGB_SCHEME_SS_L'] = "������-�����";

$MESS['ALTASIB_GEOBASE_COLOR_SCHEME'] = "���� ����";
$MESS['AGB_COLOR_SCHEME'] = "�������� �����";
$MESS['AGB_COLOR_OTHER'] = "������ ����, � ������� #XXXXXX";
$MESS['ALTASIB_GEOBASE_COLOR_SHM'] = "��������� �����";

$MESS['ALTASIB_GEOBASE_COLOR_NOTE'] = "��� ������� ����� ������� �������� <a href='#' onclick='#ON_CLICK#'>������</a> �������� �����.";
