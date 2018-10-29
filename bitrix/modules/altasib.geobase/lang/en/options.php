<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2017 ALTASIB
 */ 

$CookiePX = COption::GetOptionString("main", "cookie_name", "BITRIX_SM");

$MESS['ALTASIB_IS'] = "Shop complete solutions for 1C-Bitrix";
$MESS['ALTASIB_GEOBASE_DESCR'] = 'The module receives the user\'s location by its IP-address and stores this data in the session and if it is established in the cookies.<br/><br/>
<b>Developer Information</b>
<br/>Data is stored in the cookies as a JSON-encoded array: '. $CookiePX .'_ ALTASIB_GEOBASE and'. $CookiePX .'_ ALTASIB_GEOBASE_CODE, and in the form of regular arrays: $_SESSION["ALTASIB_GEOBASE"] and $_SESSION["ALTASIB_GEOBASE_CODE"] - auto-sensing and user specified respectively.<br/><br/>
<div id = "altasib_description_open_btn"><span class = "altasib_description_open_text">Read more</span></div><div id = "altasib_description_full">Get information, you can:
<pre>
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetAddres();
	print_r($arData);
}
// to obtain data KLADR defined automatically by location:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetCodeByAddr();
	print_r($arData);
}
// to obtain data KLADR user-defined:
if(CModule::IncludeModule("altasib.geobase")) {
	$arData = CAltasibGeoBase::GetDataKladr();
	print_r($arData);
}
// for the location of a <a href="/bitrix/admin/sale_location_admin.php" target="_blank">list of locations</a>, installed on site:
if(CModule::IncludeModule("altasib.geobase")) {
	$resData = CAltasibGeoBase::GetBXLocations();
	print_r($resData);
}
<pre>
// Do not allow handlers module (to perform scripts for cron):
define("NO_GEOBASE", true);
</pre>
// Get data from cookies:
$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));
print_r($arDataC);
</pre>
<pre>
// for bitrix locations of selected cities:
if(CModule::IncludeModule("altasib.geobase")) {
	// dinamic
	$rsData = CAltasibGeoBaseAllSelected::GetMoreCities();
	while($arCity = $rsData->Fetch()) {
		$idLocation = CAltasibGeoBase::GetBXLocations($arCity["C_NAME"], $arCity["CTR_NAME_RU"]);
		$arLocs = CSaleLocation::GetByID($idLocation, LANGUAGE_ID);
		print_r($arLocs);
	}
	// cached data
	$arCities = CAltasibGeoBaseSelected::GetMoreCacheCities();
	print_r($arCities);
}
</pre>
<pre>
// for the current favorite city data:
if(CModule::IncludeModule("altasib.geobase")) {
	$arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
	print_r($arSelCity); // data of the current city, which corresponds to the chosen, if there is a match
	if(!empty($arSelCity) && count($arSelCity) > 0)
	{
		if(!empty($arSelCity) && $arSelCity["ACTIVE"] == "Y" && intval($arSelCity["ID"])>0)
		{
			$arUFields = CAltasibGeoBaseSelected::GetFieldsCity($arSelCity["ID"], false);
			print_r($arUFields); // printing custom properties for the selected city
		}
	}
	else
	{
		// Output of the nearest town of favorites
		// String $searchMode - search mode:
		// "geo" - the coordinates, "region" - in the region, "all" - the coordinates, and region
		// bool $userChoiceEn - whether to use the user selection data
		$arNData = CAltasibGeoBaseSelected::GetNearestCityFromSelected($searchMode = "all", $userChoiceEn = true);
		print_r($arNData);
	}
}
</pre><br/>

<b>Events module</b>:<br/><br/>
<table class="internal altasib_events" width="100%">
	<thead>
		<tr>
			<th>Event</th>
			<th>Called</th>
			<th>Method</th>
			<th>From version</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>OnAfterSetSelectCity</td>
			<td>after selecting the city of user</td>
			<td>CAltasibGeoBase::SetCodeKladr
			<br/>CAltasibGeoBase::SetCodeMM</td>
			<td>1.1.3</td>
		</tr>
		<tr>
			<td>OnAfterAutoDetectCity</td>
			<td>After automatic detection city</td>
			<td>CAltasibGeoBase::GetAddres</td>
			<td>1.5.0</td>
		</tr>
			<tr>
			<td>OnBeforeResultCitySearch</td>
			<td>Before sending data to the village in a search string in the component "Select&nbsp;city"</td>
			<td>CAltasibGeoBase::CitySearch</td>
			<td>1.9.0</td>
		</tr>
	</tbody>
</table>
<br/>
<pre>
// Example of an event handler OnAfterAutoDetectCity:
AddEventHandler("altasib.geobase", "OnAfterAutoDetectCity", "AutoDetectCityHandler");
function AutoDetectCityHandler($arFields) {
	if(function_exists("prn_")){
		prn_($arFields); // array entry in the file
	}
}
</pre>
<pre>
// Example of an event handler OnBeforeResultCitySearch:
AddEventHandler("altasib.geobase", "OnBeforeResultCitySearch", "BeforeResultCitySearch");
function BeforeResultCitySearch(&$arCity) {
	// Output in the search list of the settlements of the Altai Region
	foreach($arCity as $k => $np)
	{
		if($np["REGION"] != "Алтайский Край") // encoded UTF-8
		{
			unset($arCity[$k]); // changes in the resulting array
		}
	}
	return true; // When returning false answers to the customer will not
}
</pre>
<b>JavaScript-events of module</b><br/><br/>
There is also a js-event <b>onAfterSetCity</b>, is called after the selection (setting) of the city by the user.<br/>Input parameters for the event handler: 
(string name, string id, string full_name, string data);<br/>
<pre>// Example reload the page to select a city:
BX.addCustomEvent("onAfterSetCity", function(city, city_id, full_name){
    location.reload();
});
</pre><br/>
JS event <b>onBeforeYourCityOpen</b> called before showing the window "Your city" in the component template altasib:geobase.your.city.<br/>
<pre>
// Example ban display of the window "Your city" and output instead of the window "Select city":
BX.addCustomEvent(altasib_geobase, "onBeforeYourCityOpen", function(){
	altasib_geobase.yc_not_open=true; // ban popup "Select city"
	altasib_geobase.sc_open(); // opening popup city selection
});
</pre><br/><br/>
<div id="altasib_description_close_btn">
	<span class="altasib_description_open_text">Collapse</span>
</div>
</div>
';

$MESS['ALTASIB_GEOBASE_SET_COOKIE'] = "Save to cookies location information";
$MESS['ALTASIB_GEOBASE_SET_TIMEOUT'] = "Script Execution time:";
$MESS['ALTASIB_TAB_BD_DATA'] = "Data";
$MESS['ALTASIB_TAB_TITLE_DATA'] = "Sources positioning module supports";
$MESS['ALTASIB_GEOBASE_DB_UPDATE_IPGEOBASE'] = "Updating the database module from the site <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>";
$MESS['ALTASIB_TAB_BD_CITIES'] = "Favorites Cities";
$MESS['ALTASIB_TAB_TITLE_DB_CITIES'] = "Add and edit cities (regions) in favorites list module";
$MESS['ALTASIB_TITLE_LOAD_FILE'] = "Download archive:";
$MESS['ALTASIB_TITLE_UNPACK_FILE'] = "Unpacking:";
$MESS['ALTASIB_TITLE_DB_UPDATE'] = "Database Update (<a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>):";

$MESS['ALTASIB_NOTICE_UPDATE_AVAILABLE'] = "Available updated archive of data from the website <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>.";
$MESS['ALTASIB_NOTICE_UPDATE_NOT_AVAILABLE'] = "No update is available on the website <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>.";
$MESS['ALTASIB_NOTICE_DBUPDATE_SUCCESSFUL'] = "Database Update from site <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> completed successfully.";
$MESS['ALTASIB_GEOBASE_GET_UPDATE'] = "Check for updates archives database locations on site <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> automatically:";
$MESS["ALTASIB_NOTICE_UPDATE_MANUAL_MODE"] = "To check for updates from the site <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a> click \"Check for updates\"";

$MESS["ALTASIB_CHECK_UPDATES"] = "Check for updates online <a href='http://ipgeobase.ru/' target='_blank'>ipgeobase.ru</a>...";
$MESS["ALTASIB_GEOBASE_SOURCE"] = "Source location:";
$MESS["ALTASIB_GEOBASE_NOT_USING"] = "Do not use a local database";
$MESS["ALTASIB_GEOBASE_LOCAL_DB"] = "Use the local database ipgeobase.ru";
$MESS["ALTASIB_GEOBASE_STATISTIC"] = "Use Web analytics Bitrix";
$MESS["ALTASIB_GEOBASE_UPDATE"] = "Update";
$MESS["ALTASIB_GEOBASE_CHECK_UPDATE"] = "Check for updates";
$MESS["ALTASIB_GEOBASE_WIN_YOUR_CITY_ENABLE"] = "Enable <b>automatic show</b> a popup window \"Your city\":";
$MESS["ALTASIB_GEOBASE_ONLY_SELECT_CITIES"] = 'Use only city from <a title="Favorites Cities" onclick="tabControl.SelectTab(\'edit3\'); return false;"> list of favorites cities</a>, without a field Search:';

$MESS['ALTASIB_TITLE_CITIES_LIST'] = "List of cities (regions)";
$MESS['ALTASIB_TABLE_CITY_DELETE'] = "Delete";
$MESS['ALTASIB_TABLE_CITY_ADD'] = "Add";
$MESS['ALTASIB_INP_CITY_ADD'] = "Adding a city (region) to the list of selected cities";
$MESS['ALTASIB_INP_ENTER_CITY'] = "Enter the name of the city (region)";
$MESS['ALTASIB_TABLE_CITY_NAME'] = "Name";
$MESS['ALTASIB_TABLE_CITY_CODE'] = "Code n/a";
$MESS['ALTASIB_TABLE_SORT'] = "Sort";
$MESS['ALTASIB_TABLE_DISTRICT'] = "District";
$MESS['ALTASIB_TABLE_REGION'] = "Region";
$MESS['ALTASIB_TABLE_COUNTRY_CODE'] = "Country code";
$MESS['ALTASIB_TABLE_COUNTRY'] = "Country";
$MESS['ALTASIB_TABLE_CITY_ACT'] = "Action";
$MESS['ALTASIB_GEOBASE_AUTO_DISPLAY'] = "Automatic display";
$MESS['ALTASIB_GEOBASE_GLOBAL_COMPONENTS'] = "General Settings components";
$MESS['ALTASIB_GEOBASE_LOCATIONS'] = "Settings replacement location in the module Sale";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_DESCR'] = "\"Your city\" - a component that displays a pop-up window with the possibility of confirming the city visitor defined by its IP address, as well as a link to change";
$MESS['ALTASIB_GEOBASE_YOUR_CITY_TEMPLATES'] = "Template component \"Your city\" connect automatically";
$MESS['ALTASIB_GEOBASE_POPUP_BACK'] = "Dimming background in the derivation of pop-ups";
$MESS['ALTASIB_GEOBASE_REGION_DISABLE'] = "Do not print the name of the region and the district";

$MESS['ALTASIB_GEOBASE_SELECT_CITY_DESCR'] = "\"Select the city\" - a component that displays a link to open a pop-up window to select and save the town visitor";
$MESS['ALTASIB_GEOBASE_SELECT_CITY_TEMPLATES'] = "Template component \"Select the city\" connected component in the call automatically";
$MESS['ALTASIB_GEOBASE_SITES'] = "Websites, pages that use automatic display";
$MESS['ALTASIB_GEOBASE_TEMPLATE'] = "Website templates, which enable Auto Play";
$MESS['ALTASIB_GEOBASE_SECTION_LINK'] = "Which sections replace the \"Location\" page for ordering (comma separated list, a blank value means' display without restrictions')<br />Example: <i>'/personal/order/make/, /personal/'</i>";
$MESS['ALTASIB_GEOBASE_SALE_LOCATION'] = "Location of the country default:";
$MESS['ALTASIB_GEOBASE_URL_NOT_FOUND'] = "The requested URL address of the remote server not found.";
$MESS['ALTASIB_GEOBASE_SET_SQL'] = "Adding to a long SQL-queries line \"SET SQL_BIG_SELECTS=1\"";

$MESS['ALTASIB_GEOBASE_RUSSIA'] = "Russia";
$MESS['ALTASIB_GEOBASE_RF'] = "Russian Federation";
$MESS['ALTASIB_GEOBASE_JQUERY'] = "Connect jQuery:";
$MESS['ALTASIB_GEOBASE_JQUERY_NOT'] = "The site is already connected jQuery";
$MESS['ALTASIB_GEOBASE_JQUERY_YES'] = "Yes, connect";
$MESS['ALTASIB_GEOBASE_JQUERY_2'] = "Connect jQuery v.2";
$MESS['ALTASIB_GEOBASE_FIELD_LOC_IND'] = "The ID of the input field the location of an individual on the checkout page:";
$MESS['ALTASIB_GEOBASE_FIELD_LOC_LEG'] = "The ID of the input field location of the legal entity on the checkout page:";
$MESS['ALTASIB_NOTICE_MM_UPDATE_AVAILABLE'] = "Available updated GeoLite data archive site <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a>. ";
$MESS['ALTASIB_NOTICE_MM_UPDATE_NOT_AVAILABLE'] = "An update is available on the website <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> found . ";
$MESS['ALTASIB_NOTICE_MM_DBUPDATE_SUCCESSFUL'] = "Update database file GeoLite site <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> completed successfully. ";
$MESS['ALTASIB_GEOBASE_MM_GET_UPDATE'] = "Check for updates GeoLite database online <a href='http://dev.maxmind.com/geoip/legacy/geolite/' target='_blank'>maxmind.com</a> automatically: ";
$MESS["ALTASIB_NOTICE_MM_UPDATE_MANUAL_MODE"] = "To check for updates from the site <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a> click \" Check for Updates \"";
$MESS['ALTASIB_TITLE_MM_DB_UPDATE'] = "Updating the database (<a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a>):";
$MESS["ALTASIB_CHECK_MM_UPDATES"] = "Check for updates online <a href='http://dev.maxmind.com/' target='_blank'>maxmind.com</a> ...";

$MESS['ALTASIB_GEOBASE_DEMO_MODE'] = "The module works in demo mode. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>Buy a version without limitation</a>";
$MESS['ALTASIB_GEOBASE_DEMO_EXPIRED'] = "Demo period of the module ended. <a target='_blank' href='http://marketplace.1c-bitrix.ru/tobasket.php?ID=#MODULE#'>Buy module</a>";
$MESS['ALTASIB_GEOBASE_NF'] = "Module #MODULE# is not defined.";
$MESS['ALTASIB_GEOBASE_AUTODETECT_EN'] = "Automatically add a certain city to the list of selected cities:";
$MESS['ALTASIB_GEOBASE_CITIES_WORLD_ENABLE'] = "Show cities in the world in the search component \"Select city\":";
$MESS['ALTASIB_GEOBASE_EDT_UF'] = "Edit Custom Fields";
$MESS['ALTASIB_GEOBASE_UF_LIST'] = "List of Custom Fields";
$MESS['ALTASIB_GEOBASE_UF_NOTE'] = "Edit Custom Fields of module can be made at <a href='/bitrix/admin/userfield_admin.php?lang=ru&set_filter=Y&find=#ENTITY_ID#&find_type=ENTITY_ID'>Custom Fields</a>.";
$MESS['ALTASIB_GEOBASE_REDIRECT_ENABLE'] = "Make the transition on the link in the user field of the city, in the choice of this city:<br/><em style='font-size:smaller;'>If the links do not have to be carried out to restart the page.</em>";

$MESS['ALTASIB_GEOBASE_FIELD_TEMPLATE'] = "Template identifier element locations for input fields on the ordering page:";
$MESS['ALTASIB_GEOBASE_FIELD_LOCPERSON'] = "The property is the location for the type of payer ##PERSON_TYPE_ID# <b>#PERSON_TYPE_NAME#</b> [#SITES#]:";
$MESS['ALTASIB_GEOBASE_MODE_LOC'] = "Output mode locations:";
$MESS['ALTASIB_GEOBASE_MODE_CITIES'] = "Only sities (towns)";
$MESS['ALTASIB_GEOBASE_MODE_REGIONS'] = "Only regions";
$MESS['ALTASIB_GEOBASE_MODE_ALL'] = "Cities and regions";

$MESS['ALTASIB_GEOBASE_AUTODT_HIT_EN'] = "Detect automatically the city on the hits:<br/><i style='font-size:smaller;'>write to the session, even if the components are not included <b>Auto Play</b></i>";
$MESS['ALTASIB_GEOBASE_REDIRECT_ONHIT_EN'] = "Redirecting the link specified in the user field of the city, at the entry to the site visitor to confirm this city:";

$MESS['ALTASIB_GEOBASE_GEOIP_SETS'] = "Settings service geoip.elib.ru";
$MESS['ALTASIB_GEOBASE_GEOIP_REG'] = "To use the service <a href='http://geoip.top/' title='Geo IP'>geoip.elib.ru</a> required <a href='https://geoip.top/cgi-bin/kernel.pl?Reg=1' title='Geo IP - Personal Area'>registration</a> and subsequently adding the site list \"My sites\" to give <b>site code</b>.";

$MESS['ALTASIB_GEOBASE_IPGEOBASE_ENABLE'] = "Use the online service <a href='http://ipgeobase.ru/' target='_blank' title='Geography of the Russian and Ukrainian IP-addresses. Search for the location (city) IP-address'>ipgeobase.ru</a>:";
$MESS['ALTASIB_GEOBASE_GEOIP_ENABLE'] = "Use the online service <a href='http://geoip.elib.ru/' target='_blank' title='Service \"Geo IP\" - The definition of geographical coordinates by IP address'>geoip.elib.ru</a>:<br/><i style='font-size:smaller;'>need to register and <a href=\"#GeoIPSettings\">add</a> code site</i>";
$MESS['ALTASIB_GEOBASE_GEOIP_CODE_SITES'] = "The SID of website #SITE# in system <a href='http://geoip.elib.ru/' target='_blank' title='Service \"Geo IP\"'>geoip.elib.ru</a>:";
$MESS['ALTASIB_GEOBASE_REDIRECT_SAVE_CURPAGE'] = "Implement redirection while maintaining a relative path:<br/><i style='font-size:smaller;'>To link in the user field of the city, add the current relative path</i>";
$MESS['ALTASIB_GEOBASE_CITIES_ONLY_LARGE'] = "Show only cities, large towns and district centers of the settlements in the <u>Search box</u>:";

$MESS['ALTASIB_GEOBASE_RDR_SETTS']	= "Redirects settings";
$MESS['ALTASIB_GEOBASE_RDR_TIME'] = "How long to remember the perfect redirection:<br/><i style='font-size:smaller;'>Redirecting visitors is stored in cookies</i>.";
$MESS['ALTASIB_GEOBASE_RDR_NOT_TIME']	= "Do not remember";
$MESS['ALTASIB_GEOBASE_RDR_SESSIONS']	= "Session";
$MESS['ALTASIB_GEOBASE_RDR_HOUR']	= "1 hour";
$MESS['ALTASIB_GEOBASE_RDR_DAY']	= "1 day";
$MESS['ALTASIB_GEOBASE_RDR_WEEK']	= "1 week";
$MESS['ALTASIB_GEOBASE_RDR_MONTH']	= "1 month";
$MESS['ALTASIB_GEOBASE_RDR_YEAR']	= "1 year";

$MESS['ALTASIB_GEOBASE_NOT_SET'] = "<not set>";
$MESS['ALTASIB_GEOBASE_SPREAD_COOKIE'] = "Spread cookie selected cities on all domains";

$MESS['ALTASIB_GEOBASE_LOOCKUP'] = "Definition of cities and countries in the Statistics module";
$MESS['ALTASIB_GEOBASE_LOOCKUP_USR'] = "Use the data set by the user in determining the source module <a href=\"\" target=\"_blank\">Statistic</a>";

$MESS['ALTASIB_GEOBASE_SRV'] = 'Printing global array keys $_SERVER';
$MESS['ALTASIB_GEOBASE_SRV_NOTE'] = "Verify that the server sends data about the visitor's IP address.<br/>";
$MESS['ALTASIB_GEOBASE_SRV_DETECT'] = "Defined <u>#IP#</u> address.";
$MESS['ALTASIB_GEOBASE_SRV_ND'] = "Global client IP address <u>not defined</u>. Need to contact the hoster.";
$MESS['ALTASIB_GEOBASE_KEY'] = "Key";
$MESS['ALTASIB_GEOBASE_VAL'] = "Value";
$MESS['ALTASIB_GEOBASE_SRV_CHK'] = "Verify the IP address definition";
$MESS['ALTASIB_GEOBASE_YOUR_MODE'] = "Mode for displaying the city confirmation window:";
$MESS['ALTASIB_GEOBASE_LARGE'] = "Normal pop-up window";
$MESS['ALTASIB_GEOBASE_SMALL'] = "Compact window";

$MESS['ALTASIB_GEOBASE_YC_MODE'] = "Compact city confirmation window is displayed in the public only if there is a call to the component \"Select city\" (altasib:geobase.select.city) with the current city line.";

$MESS['AGB_SCHEME_BRIGHT'] = "Bright";
$MESS['AGB_SCHEME_PALE'] = "Pale";
$MESS['AGB_OTHER'] = "(other)";

$MESS['AGB_SCHEME_GB'] = "Slate-green";
$MESS['AGB_SCHEME_GG'] = "Protective blue";
$MESS['AGB_SCHEME_R'] = "Red";
$MESS['AGB_SCHEME_G'] = "Green";
$MESS['AGB_SCHEME_P'] = "Purple";
$MESS['AGB_SCHEME_O'] = "Bright red-orange";
$MESS['AGB_SCHEME_S'] = "Mother-of-pearl light gray";
$MESS['AGB_SCHEME_SS'] = "Pigeon blue";

$MESS['AGB_SCHEME_GB_L'] = "Light bluish green";
$MESS['AGB_SCHEME_GG_L'] = "Pale blue";
$MESS['AGB_SCHEME_R_L'] = "Pastel pink";
$MESS['AGB_SCHEME_G_L'] = "Moderately green";
$MESS['AGB_SCHEME_P_L'] = "Light purplish blue";
$MESS['AGB_SCHEME_O_L'] = "Light coral";
$MESS['AGB_SCHEME_S_L'] = "Smoke-white";
$MESS['AGB_SCHEME_SS_L'] = "Pale gray";

$MESS['ALTASIB_GEOBASE_COLOR_SCHEME'] = "Theme color";
$MESS['AGB_COLOR_SCHEME'] = "Color scheme";
$MESS['AGB_COLOR_OTHER'] = "Another color, in the format #XXXXXX";
$MESS['ALTASIB_GEOBASE_COLOR_SHM'] = "Color Settings";

$MESS['ALTASIB_GEOBASE_COLOR_NOTE'] = "To set the color manually, select an <a href='#' onclick='#ON_CLICK#'>empty</a> color scheme.";
