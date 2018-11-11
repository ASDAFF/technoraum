<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2018 ALTASIB
 */

global $DBType;
IncludeModuleLangFile(__FILE__);

$arClassesList = array(
	"CAltasibGeoBaseTools" => "classes/general/geobase.php",
	"CAltasibGeoBaseSelected" => "classes/general/selected.php",
	"CAltasibGeoBaseIPTools" => "classes/general/iptools.php",
	"CAltasibGeoBaseMobile_Detect" => "classes/general/Mobile_Detect.php",
	"CAltasibGeoBaseCityLookup" => "classes/general/city_loockup.php",
	"CAltasibGeoBaseImport" => "classes/".$DBType."/import.php"
);

Class CAltasibGeoBase
{
	const CITY_INC = "/upload/altasib/geobase/src/geoipcity.inc";
	const REG_VARS = "/upload/altasib/geobase/src/geoipregionvars.php";
	const GEO_LITE = "/upload/altasib/geobase/GeoLiteCity.dat";
	const MID = "altasib.geobase";

	function __construct($num1)
	{
		$this->RegionName = $num1;
	}

	function GetAddres($ip = "")
	{
		if(defined("NO_GEOBASE") && NO_GEOBASE === true)
			return false;

		global $APPLICATION;
		if($ip == "")
		{
			if(!is_array($_SESSION["ALTASIB_GEOBASE"]))
			{
				$ip = CAltasibGeoBaseIP::getUserHostIP();

				if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
				{
					$last_ip = $APPLICATION->get_cookie("ALTASIB_LAST_IP");
					$sData = $APPLICATION->get_cookie("ALTASIB_GEOBASE");
				}
				if(($ip == $last_ip) && $sData && count(CAltasibGeoBase::deCodeJSON($sData)) > 0)
				{
					$arData = CAltasibGeoBase::deCodeJSON($sData);
					$_SESSION["ALTASIB_GEOBASE"] = $arData;
				}
				else
				{
					$arData = CAltasibGeoBase::GetData($ip); //local_db, statistic - true
					if(!$arData)
					{
						if(COption::GetOptionString(self::MID, "ipgeobase_enable", "Y") != "N")
						{
							$arData = CAltasibGeoBaseIP::GetGeoDataIpgeobase_ru($ip);
						}
					}
					if(!$arData)
					{
						if(COption::GetOptionString(self::MID, "geoip_enable", "Y") != "N")
						{
							$arData = CAltasibGeoBaseIP::GetGeoDataGeoip_Elib_ru($ip);
						}
					}
					if(empty($arData["CITY_NAME"]))
					{
						$arDataM = CAltasibGeoBase::GetMaxmindData($ip);
						if(!empty($arDataM["COUNTRY_CODE"]))
							$arData = $arDataM;
					}

					if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
					{
						$sData = CAltasibGeoBase::CodeJSON($arData);
						$APPLICATION->set_cookie("ALTASIB_LAST_IP", $ip, time() + 31104000); //60*60*24*30*12
						$APPLICATION->set_cookie("ALTASIB_GEOBASE", $sData, time() + 31104000); //60*60*24*30*12
					}

					$_SESSION["ALTASIB_GEOBASE"] = $arData;

					$events = GetModuleEvents(self::MID, "OnAfterAutoDetectCity");
					while($arEvent = $events->Fetch()){
						ExecuteModuleEvent($arEvent, array($arData));
					}
				}
			}
		}
		else
		{
			$arData = CAltasibGeoBase::GetData($ip);
			if(!$arData)
			{
				if(COption::GetOptionString(self::MID, "ipgeobase_enable", "Y") != "N")
				{
					$arData = CAltasibGeoBaseIP::GetGeoDataIpgeobase_ru($ip);
				}

				if(!$arData)
				{
					if(COption::GetOptionString(self::MID, "geoip_enable", "Y") != "N")
					{
						$arData = CAltasibGeoBaseIP::GetGeoDataGeoip_Elib_ru($ip);
					}
				}
				if(!$arData)
					return false;
			}
			return $arData;
		}
		return $_SESSION["ALTASIB_GEOBASE"];
	}

	function CodeJSON($data)
	{
		$sJSON = CUtil::PhpToJSObject($data);
		if(ToLower(SITE_CHARSET) !== "utf-8")
			$sJSON = iconv(SITE_CHARSET, "UTF-8", $sJSON);
		$sJSON = str_replace("'", '"', $sJSON);
		return $sJSON;
	}

	function deCodeJSON($data)
	{
		$resData = (array)json_decode($data, true);
		if(ToLower(SITE_CHARSET) !== "utf-8")
			$resData = CAltasibGeoBase::iconvArrUtfTo1251($resData);

		return $resData;
	}

	function iconvArrUtfTo1251($arr)
	{
		if(is_array($arr))
		{
			foreach($arr as $key=>$Prop)
			{
				if(is_array($Prop))
					$arProp[$key] = CAltasibGeoBase::iconvArrUtfTo1251($Prop);
				else
					$arProp[$key] = iconv('UTF-8', 'WINDOWS-1251', $Prop);
			}
		}
		else
			$arProp = iconv('UTF-8', 'WINDOWS-1251', $arr);

		return $arProp;
	}

	function OnPrologHandler()
	{
		global $APPLICATION;
		if(!IsModuleInstalled(self::MID))
			return false;

		if(defined("ADMIN_SECTION") && ADMIN_SECTION === true)
			return false;

		if(defined("NO_GEOBASE") && NO_GEOBASE === true)
			return false;

		$upLink = trim(COption::GetOptionString(self::MID, "section_link", "/personal/order/make/"));
		if($upLink != "")
		{
			$dir = $APPLICATION->GetCurDir();

			if(substr($dir, 0, 8) == "/bitrix/")
				return false;

			$arLink = explode(",", $upLink);
			if(!is_array($arLink))
				return false;

			foreach($arLink as $v)
			{
				$v = trim($v);
				if($v == "")
					continue;
				if(substr($v, 0, 1) != "/")
					$v = "/".$v;

				$subDir = substr($dir, 0, strlen($v));
				if($subDir == $v)
				{
					CAltasibGeoBase::addScriptsOnSite();
					return true;
				}
			}
			return false;
		}
		else{
			CAltasibGeoBase::addScriptsOnSite();
			return true;
		}
	}

	function addScriptsOnSite()
	{
		global $APPLICATION;
		if(ADMIN_SECTION !== true)
		{
			$jQEn = COption::GetOptionString(self::MID, "enable_jquery", "ON");
			if($jQEn == "ON")
				CJSCore::Init(array('jquery'));
			elseif($jQEn == "2")
				CJSCore::Init(array('jquery2'));
		}

		$APPLICATION->AddHeadScript("/bitrix/js/main/core/core.min.js", true);
		$APPLICATION->AddHeadScript("/bitrix/js/altasib/geobase/script.js", true);

		$defLoc = "";
		if(($strLoc = COption::GetOptionString(self::MID, "def_location", "")) != ""
			&& CModule::IncludeModule("sale"))
		{
			$arCtry = CSaleLocation::GetCountryLangByID($strLoc, LANGUAGE_ID);
			$defLoc = "altasib_geobase.def_location='".htmlspecialcharsbx(trim($arCtry["NAME"]))."';";
		}
		$bxLoc = "";

		$intLoc = CAltasibGeoBase::GetBXLocations();

		if(!empty($intLoc))
		{
			$bxLoc = "altasib_geobase.bx_loc='".intval($intLoc)."';";
			if(CModule::IncludeModule('sale'))
			{
				$dbVars = CSaleLocation::GetList(array("SORT" => "ASC"), array("ID" => $intLoc), false, false, array("CODE"));
				if($vars = $dbVars->Fetch())
				{
					$bxLoc .= "altasib_geobase.bx_loc_code='".$vars["CODE"]."';";
				}
			}
		}

		if(CModule::IncludeModule('sale'))
		{
			$arLocs = array();
			$arLVals = array();
			$sLocTpl = COption::GetOptionString(self::MID, "location_template", "ORDER_PROP_");
			$rsPType = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("ACTIVE"=>'Y', "LID"=>SITE_ID), false, false, array("ID"));
			while($arPType = $rsPType->Fetch())
			{
				$arLocs[] = $arPType["ID"];

				$sLVals = COption::GetOptionString(self::MID, "field_loc_".$arPType["ID"], "");
				if(!empty($sLVals))
					$arLVals[$arPType["ID"]] = $sLocTpl.$sLVals;
			}
			if(!empty($arLocs))
			{
				$strPT = "altasib_geobase.pt=".CUtil::PhpToJSObject($arLocs).";";
			}
			if(!empty($arLVals))
			{
				$sPTvals = "altasib_geobase.pt_vals=".CUtil::PhpToJSObject($arLVals).";";
			}

			$arLDefs = array();
			$arLDefCode = array();
			$dbPr = CSaleOrderProps::GetList(array("SORT" => "ASC"), array("IS_LOCATION"=>'Y'), false, false, array("DEFAULT_VALUE", "DEFAULT_VALUE_ORIG"));
			while($arProps = $dbPr->Fetch())
			{
				if(!empty($arProps["DEFAULT_VALUE"]))
					$arLDefs[] = $arProps["DEFAULT_VALUE"];
				if(!empty($arProps["DEFAULT_VALUE_ORIG"]))
					$arLDefCode[] = $arProps["DEFAULT_VALUE_ORIG"];
			}

			if(!empty($arLDefs))
			{
				$strPV = "altasib_geobase.pv_default=".CUtil::PhpToJSObject($arLDefs).";";
			}
			if(!empty($arLDefCode))
			{
				$strPV .= "altasib_geobase.pv_def_code=".CUtil::PhpToJSObject($arLDefCode).";";
			}
		}

		if(empty($strPT))
		{
			$strPT = "altasib_geobase.pt=['1','2'];";
		}
		if(empty($sPTvals))
		{
			$sPTvals = "altasib_geobase.pt_vals={"
				."'1':'".htmlspecialcharsbx(trim(COption::GetOptionString(self::MID, "field_loc_ind", "ORDER_PROP_2")))."',"
				."'2':'".htmlspecialcharsbx(trim(COption::GetOptionString(self::MID, "field_loc_leg", "ORDER_PROP_3")))."'};";
		}

		$sJS = "<script>if(typeof altasib_geobase=='undefined'){var altasib_geobase={};}"
			.$strPT.$sPTvals
			."altasib_geobase.country='".GetMessage("ALTASIB_GEOBASE_RUSSIA")."';"
			."altasib_geobase.COOKIE_PREFIX='".COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."';"
			."altasib_geobase.bitrix_sessid='".bitrix_sessid()."';"
			."altasib_geobase.SITE_ID='".SITE_ID."';"
			.$defLoc.$bxLoc.$strPV."</script>";
		$APPLICATION->AddHeadString($sJS, true);
	}

	function OnBeforeUserAddHandler(&$arFields)
	{
		global $APPLICATION;

		if(defined("NO_GEOBASE") && NO_GEOBASE === true)
			return;

		//Cookies
		$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));

		if(!empty($_SESSION["ALTASIB_GEOBASE_CODE"]) || !empty($arDataC)){
			$arDtKLADR = CAltasibGeoBase::GetDataKladr();
		}
		else
		{
			$arData = CAltasibGeoBase::GetCodeByAddr();
			if($arData)
			{
				if($arData["CITY"]["NAME"] != GetMessage('ALTASIB_GEOBASE_KLADR_CITY_NAME'))
					$arDtKLADR = $arData;
			}
			else
				$arIPLoc = CAltasibGeoBase::GetAddres();
		}

		if($arIPLoc)
		{
			$sCountry = "";
			if(!empty($arIPLoc["COUNTRY"]))
				$sCountry = $arIPLoc["COUNTRY"];
			elseif(!empty($arIPLoc["COUNTRY_CODE"]))
			{
				if($arIPLoc["COUNTRY_CODE"] == "RU")
					$sCountry = GetMessage("ALTASIB_GEOBASE_RUSSIA");
			}
			$arCountries = GetCountryArray();
			foreach($arCountries['reference'] as $id => $country){
				if($sCountry == $country)
					$countryId = $arCountries['reference_id'][$id];
			}

			if(!empty($countryId))
				$arFields['PERSONAL_COUNTRY'] = $countryId;

			if(!empty($arIPLoc["REGION_NAME"]) && strlen($arIPLoc["REGION_NAME"])>0)
				$arFields['PERSONAL_STATE'] = $arIPLoc["REGION_NAME"];

			if(!empty($arIPLoc["CITY_NAME"]))
				$arFields['PERSONAL_CITY'] = $arIPLoc["CITY_NAME"];
		}
		elseif($arDtKLADR)
		{
			$sCountry = "";
			if(empty($arDtKLADR["COUNTRY"]))
				$sCountry = GetMessage("ALTASIB_GEOBASE_RUSSIA");
			else
				$sCountry = $arDtKLADR["COUNTRY"];

			$arCountries = GetCountryArray();
			foreach($arCountries["reference"] as $id => $country){
				if($sCountry == $country)
					$countryId = $arCountries["reference_id"][$id];
			}

			if(!empty($countryId))
				$arFields['PERSONAL_COUNTRY'] = $countryId;

			if(!empty($arDtKLADR["REGION"]["NAME"]) && strlen($arDtKLADR["REGION"]["NAME"])>0)
				$arFields['PERSONAL_STATE'] = $arDtKLADR["REGION"]["NAME"]." ".$arDtKLADR["REGION"]["SOCR"];

			if(!empty($arDtKLADR["CITY"]["NAME"]))
				$arFields['PERSONAL_CITY'] = $arDtKLADR["CITY"]["NAME"];
		}
		return true;
	}

	function GetData($ip)
	{
		$source = COption::GetOptionString(self::MID, "source", "local_db");
		if($source == "not_using")
			return false;
		elseif($source == "local_db")
			return CAltasibGeoBase::GetGeoData($ip);
		elseif($source == "statistic")
			return CAltasibGeoBase::GetStatisticData();
		elseif($source == "maxmind")
			return CAltasibGeoBase::GetMaxmindData($ip);
		elseif($source == "ipgb_mm"){
			$arData = CAltasibGeoBase::GetGeoData($ip);
			if(empty($arData["CITY_NAME"]))
				$arMM = CAltasibGeoBase::GetMaxmindData($ip);
			if(!empty($arMM["COUNTRY_CODE"]))
				$arData = $arMM;
			return $arData;
		}
	}

	function GetMaxmindData($ip)
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"].self::CITY_INC))
			require_once($_SERVER["DOCUMENT_ROOT"].self::CITY_INC);
		if(file_exists($_SERVER["DOCUMENT_ROOT"].self::REG_VARS))
			require_once($_SERVER["DOCUMENT_ROOT"].self::REG_VARS);

		if(file_exists($_SERVER["DOCUMENT_ROOT"].self::GEO_LITE))
			$gi = GeoIP_open($_SERVER["DOCUMENT_ROOT"].self::GEO_LITE, GEOIP_STANDARD);
		else
			return;

		$record = GeoIP_record_by_addr($gi, $ip); //Get data from the database
		$arRec = (array)$record; //Display data on the screen
		GeoIP_close($gi); //Close the database connection

		$arData = array(
			"COUNTRY_CODE" => $arRec["country_code"],
			"COUNTRY_CODE3" => $arRec["country_code3"],
			"COUNTRY_NAME" => $arRec["country_name"],
			"REGION_CODE" => $arRec["region"],
			"REGION_NAME" => $GEOIP_REGION_NAME[$arRec["country_code"]][$arRec["region"]],
			"CITY_NAME" => $arRec["city"],
			"POSTINDEX" => $arRec["postal_code"],
			"CONTINENT_CODE" => $arRec["continent_code"],
			"latitude" => $arRec["latitude"],
			"longitude" => $arRec["longitude"]
		);
		return $arData;
	}

	function GetGeoData($ip)
	{
		global $DB;
		if($DB->TableExists('altasib_geobase_codeip') && $DB->TableExists('altasib_geobase_cities'))
		{
			$arIP	= explode('.', $ip);
			$codeIP = $arIP[0]*pow(256, 3) + $arIP[1]*pow(256, 2) + $arIP[2]*256 + $arIP[/*2*/3];

			$data = $DB->Query("SELECT * FROM altasib_geobase_codeip
				INNER JOIN altasib_geobase_cities ON altasib_geobase_codeip.CITY_ID = altasib_geobase_cities.ID
				WHERE altasib_geobase_codeip.BLOCK_BEGIN <= $codeIP AND $codeIP <= altasib_geobase_codeip.BLOCK_END"
			);
			$arData = $data->Fetch();
		} else
			$arData = array();

		return $arData;
	}

	function GetIsUpdateDataFile($Host, $Path, $File, $lFName)
	{
		$resp = "N";
		if(file_exists($lFName)){
			$res = @fsockopen($Host, 80, $errno, $errstr, 3);

			if($res){
				$sReq = "HEAD ".$Path.$File." HTTP/1.1\r\n";
				$sReq.= "Host: ".$Host."\r\n";
				$sReq.= "\r\n";

				fputs($res, $sReq);
				while($line = fgets($res, 4096)){
					if(@preg_match("/Content-Length: *([0-9]+)/i", $line, $regs)){
						if(@filesize($lFName) != trim($regs[1]))
							$resp = true;
						else
							$resp = false;

						break;
					}
				}
				fclose($res);
			}
		} else
			$resp = true;
		return $resp;
	}

	function SetUpdateAgent()
	{
		if(COption::GetOptionString(self::MID, "get_update", "N") != "Y")
			return "CAltasibGeoBase::SetUpdateAgent();";

		define('LOAD_HOST', 'ipgeobase.ru');
		define('LOAD_PATH', '/files/db/Main/');
		define('LOAD_FILE', 'geo_files.tar.gz');
		$protocol = (CMain::IsHTTPS() ? "https://" : "http://");
		if(!CAltasibGeoBaseIP::CheckServiceAccess($protocol.LOAD_HOST.LOAD_PATH.LOAD_FILE))
			return "CAltasibGeoBase::SetUpdateAgent();";

		$strFile = $_SERVER["DOCUMENT_ROOT"]."/upload/altasib/geoip/".basename($protocol.LOAD_HOST.LOAD_PATH.LOAD_FILE);

		if(CAltasibGeoBase::GetIsUpdateDataFile(LOAD_HOST, LOAD_PATH, LOAD_FILE, $strFile)){
			include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/admin_notify.php");
			CAdminNotify::Add(
				array(
					"MESSAGE"		=> date('d.m.Y').GetMessage("ALTASIB_GEOBASE_AVAILABLE"),
					"TAG"			=> "GEOBASE_DB_UPDATE_".date('d.m.Y'),
					"MODULE_ID"		=> self::MID,
					"ENABLE_CLOSE"	=> "Y"
				)
			);
		}
		return "CAltasibGeoBase::SetUpdateAgent();";
	}

	function GetStatisticData()
	{
		if(CModule::IncludeModule("statistic")){
			$obCity = new CCity();
			$arCity = $obCity->GetFullInfo();

			$arRsCity = array(
				"BLOCK_ADDR" => $arCity["IP_ADDR"]["VALUE"],
				"COUNTRY_CODE" => $arCity["COUNTRY_CODE"]["VALUE"],
				"COUNTRY" => $arCity["COUNTRY_NAME"]["VALUE"],
				"REGION_NAME" => $arCity["REGION_NAME"]["VALUE"],
				"CITY_NAME" => $arCity["CITY_NAME"]["VALUE"]
			);
			return $arRsCity;
		}
		else
			return false;
	}

	function GetCitySuggest() //altasib_geobase_get.php
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
			return false;

		if(defined("NO_GEOBASE") && NO_GEOBASE === true)
			return;

		// if multidomain session is different
		$arDomain = self::GetDomains();

		if(!check_bitrix_sessid('sessid') && count($arDomain) < 2 && !IsIE())
			return false;

		CUtil::JSPostUnescape();

		$res = false;
		if(!isset($_POST['city_name']) && !isset($_POST['save']))
		{
			return false;
		}
		elseif(empty($_POST['city_name']) && empty($_POST['save']))
			die('pusto');
		elseif(isset($_POST['city_name']) && empty($_POST['save']) && empty($_POST['set_loc']))
		{
			return CAltasibGeoBase::CitySearch(false);
		}
		elseif(isset($_POST['save']) && $_POST['save'] == 'Y')
		{
			if(!empty($_POST['city_id']))
			{
				global $DB;
				$cityID = $DB->ForSql($_POST['city_id']);
				$regID = $DB->ForSql($_POST['REGION_CODE']);
				if(!empty($regID))
					$res = CAltasibGeoBase::SetCodeKladr($cityID, $regID);
				else
					$res = CAltasibGeoBase::SetCodeKladr($cityID);
			}
			elseif(isset($_POST['CITY_NAME']) && isset($_POST['COUNTRY_CODE']))
			{
				global $DB;
				$cityName = $DB->ForSql($_POST['CITY_NAME']);
				$ctryCode = $DB->ForSql($_POST['COUNTRY_CODE']);
				$regionCode = $DB->ForSql($_POST['REGION_CODE']);
				if($ctryCode == 'RU')
				{
					$rsCity = CAltasibGeoBase::GetCityByNameReg(trim(htmlspecialcharsEx($cityName)),
						array('ID', 'NAME', 'CODE'), '', false, false);
					if($arCity = $rsCity->Fetch())
					{
						$res = CAltasibGeoBase::SetCodeKladr($arCity["CODE"]);
					}
				}

				if(!$res)
				{
					$res = CAltasibGeoBase::SetCodeMM($cityName, $ctryCode, $regionCode);
				}
			}

			if($res && COption::GetOptionString(self::MID, 'redirect_enable', 'Y') == "Y")
			{
				$res .= ";".CAltasibGeoBase::GetRedirectUri(htmlspecialcharsEx($_REQUEST["url"]), (htmlspecialcharsEx($_REQUEST["reload"])!=="NO"));

				if(COption::GetOptionString(self::MID, "SPREAD_COOKIE", "Y") == "Y")
				{
					$resSpr = '';
					foreach(self::GetSpreadCookieUrls() as $url){
						$resSpr .= "new Image().src='".CUtil::JSEscape($url)."';\n";
					}

					if($resSpr)
						$res .= ";".$resSpr;
				}
			}

			return $res;
		}
	}

	/**
	 * Returns array of urls which contain signed cross domain cookies.
	 *
	 * @return array
	 */
	function GetSpreadCookieUrls()
	{
		$res = array();
		if(COption::GetOptionString(self::MID, "SPREAD_COOKIE", "Y")=="Y")
		{
			global $APPLICATION;
			$arSpread = array();
			if(isset($APPLICATION->arrSPREAD_COOKIE) && is_array($APPLICATION->arrSPREAD_COOKIE) && !empty($APPLICATION->arrSPREAD_COOKIE))
			{
				$arSpread = $APPLICATION->arrSPREAD_COOKIE;
			}
			if(isset($_SESSION['SPREAD_COOKIE']) && is_array($_SESSION['SPREAD_COOKIE']) && !empty($_SESSION['SPREAD_COOKIE']))
			{
				$arSpread = array_merge($arSpread, $_SESSION['SPREAD_COOKIE']);
			}

			$params = "";

			if(!empty($arSpread) && is_object(current($arSpread)))
			{
				$response = \Bitrix\Main\Context::getCurrent()->getResponse();

				if(is_array($_SESSION['SPREAD_COOKIE']))
				{
					foreach($_SESSION['SPREAD_COOKIE'] as $cookie)
					{
						if($cookie instanceof \Bitrix\Main\Web\Cookie)
						{
							$response->addCookie($cookie, false);
						}
					}
				}
				$cookies = $response->getCookies();

				if(!empty($cookies))
				{
					foreach($cookies as $cookie)
					{
						if($cookie->getSpread() & \Bitrix\Main\Web\Cookie::SPREAD_SITES)
						{
							$params .= $cookie->getName().chr(1).
								$cookie->getValue().chr(1).
								$cookie->getExpires().chr(1).
								$cookie->getPath().chr(1).
								chr(1). //domain is empty
								$cookie->getSecure().chr(1).
								$cookie->getHttpOnly().chr(2);
						}
					}
					unset($arSpread);
				}
			}

			if(!empty($arSpread))
			{
				foreach($arSpread as $name => $ar)
				{
					$ar["D"] = ""; // domain must be empty
					$params .= $name.chr(1).$ar["V"].chr(1).$ar["T"].chr(1).$ar["F"].chr(1).$ar["D"].chr(1).$ar["S"].chr(1).$ar["H"].chr(2);
				}
			}

			if(!empty($params))
			{
				$salt = $_SERVER["REMOTE_ADDR"]."|".@filemtime($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/version.php")."|".LICENSE_KEY;
				$params = "s=".urlencode(base64_encode($params))."&k=".urlencode(md5($params.$salt));
				$arDomain = self::GetDomains();

				if(count($arDomain)>0)
				{
					$protocol = (CMain::IsHTTPS()) ? "https://" : "http://";
					$arCurUrl = parse_url($protocol.$_SERVER["HTTP_HOST"]."/".$_SERVER["REQUEST_URI"]);
					foreach($arDomain as $domain)
					{
						if(strlen(trim($domain))>0)
						{
							$url = $protocol.$domain."/bitrix/tools/altasib.geobase/spread.php?".$params;
							$arrUrl = parse_url($url);
							if($arrUrl["host"] != $arCurUrl["host"])
								$res[] = $url;
						}
					}
				}
			}
		}

		return $res;
	}

	/**
	 * Returns array of there are domains.
	 *
	 * $bUnique - if true, deletes similar domains
	 * @return array
	 */
	function GetDomains($bUnique = false)
	{
		$arDomain = array();
		$arDomain[] = $_SERVER["HTTP_HOST"];
		$v1 = "sort";
		$v2 = "asc";
		$rs = CSite::GetList($v1, $v2, array("ACTIVE" => "Y"));
		while($ar = $rs->Fetch())
		{
			$arD = explode("\n", str_replace("\r", "\n", $ar["DOMAINS"]));
			if(is_array($arD) && count($arD)>0)
				foreach($arD as $d)
					if(strlen(trim($d))>0)
						$arDomain[] = $d;
		}

		if(count($arDomain)>0)
		{
			$arDomain = array_unique($arDomain);

			if($bUnique)
			{
				$arUniqDomains = array();
				$arDomain2 = array_unique($arDomain);
				foreach($arDomain as $domain1)
				{
					$bGood = true;
					foreach($arDomain2 as $domain2)
					{
						if(strlen($domain1)>strlen($domain2) && substr($domain1, -(strlen($domain2)+1)) == ".".$domain2)
						{
							$bGood = false;
							break;
						}
					}
					if($bGood)
						$arUniqDomains[] = $domain1;
				}
				return $arUniqDomains;
			}
		}
		return $arDomain;
	}

	function GetSelectCity() //handler for select_city.php
	{
		$Templ = explode(",", COption::GetOptionString(self::MID, "select_city_templates"));

		$arPars = array();
		$arPars["REGION_DISABLE"] = COption::GetOptionString(self::MID, 'region_disable', 'N');
		$arPars["POPUP_BACK"] = COption::GetOptionString(self::MID, 'popup_back', 'Y');

		global $APPLICATION;
		$APPLICATION->IncludeComponent(
			'altasib:geobase.select.city',
			(empty($Templ[0]) ? "" : $Templ[0]),
			$arPars,
			false
		);
	}

	function GetYourCity() //handler for your_city.php
	{
		$res = false;
		if(isset($_POST['locate']) && $_POST['locate'] == 'Y')
		{
			if(!is_array($_SESSION["ALTASIB_GEOBASE_CODE"]))
			{
				global $APPLICATION;
				$TemplYC = explode(",", COption::GetOptionString(self::MID, "your_city_templates"));

				if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
				{
					$strData = $APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE");
				}
				if(empty($strData)){
					$arPars = array();
					$arPars["REGION_DISABLE"] = COption::GetOptionString(self::MID, 'region_disable', 'N');
					$arPars["POPUP_BACK"] = COption::GetOptionString(self::MID, 'popup_back', 'Y');

					$APPLICATION->IncludeComponent(
						"altasib:geobase.your.city",
						(empty($TemplYC[0]) ? "" : $TemplYC[0]),
						$arPars,
						false
					);
				}
			}
		}
		elseif(isset($_POST['set_loc']) && $_POST['set_loc'] == 'Y')
		{
			if(!empty($_POST['city_id']))
			{
				global $DB;
				$cityID = $DB->ForSql($_POST['city_id']);
				$regID = $DB->ForSql($_POST['REGION_CODE']);
				if(!empty($regID))
					$res = CAltasibGeoBase::SetCodeKladr($cityID, $regID);
				else
					$res = CAltasibGeoBase::SetCodeKladr($cityID);
			}
			elseif(isset($_POST['CITY_NAME']) && isset($_POST['COUNTRY_CODE']))
			{
				global $DB;
				$cityName = $DB->ForSql($_POST['CITY_NAME']);
				$ctryCode = $DB->ForSql($_POST['COUNTRY_CODE']);
				$regCode = $DB->ForSql($_POST['REGION_CODE']);
				$res = CAltasibGeoBase::SetCodeMM($cityName, $ctryCode, $regCode);
			}
			else
				$res = false;

			if($res && COption::GetOptionString(self::MID, 'redirect_enable', 'Y') == "Y")
			{
				$res .= ";".CAltasibGeoBase::GetRedirectUri(htmlspecialcharsEx($_REQUEST["url"]), (htmlspecialcharsEx($_REQUEST["reload"])!=="NO"));

				if(COption::GetOptionString(self::MID, "SPREAD_COOKIE", "Y") == "Y")
				{
					$rsSpr = '';
					foreach(self::GetSpreadCookieUrls() as $url){
						$rsSpr .= "new Image().src='".CUtil::JSEscape($url)."';\n";
					}

					if($rsSpr)
						$res .= ";".$rsSpr;
				}
			}
		}
		return $res;
	}

	function CitySearch($bAdmSect = false)
	{
		$city = trim(urldecode($_POST['city_name']));
		$lang = $_POST['lang'];
		if(ToLower(SITE_CHARSET) == 'windows-1251')
		{
			$city1 = @iconv("UTF-8", "windows-1251//IGNORE", $city); //All AJAX requests come in Unicode
			if($city1)
				$city = $city1;	//if used Windows-machine
		}
		$city = addslashes($city);

		if($bAdmSect)
			$bCityWorldEn = true;
		else
			$bCityWorldEn = (COption::GetOptionString(self::MID, 'cities_world_enable', 'Y') == "Y");

		$sRegionEn = COption::GetOptionString(self::MID, 'mode_location', 'cities');
		$bOnlyLarge = (COption::GetOptionString(self::MID, 'cities_only_large', 'N') == "Y");

		$arCity = array();
		$obCache = new CPHPCache();
		$cacheID = "CitySearchByString".$city.$bAdmSect.$bCityWorldEn.$sRegionEn.$bOnlyLarge.$lang;
		if($obCache->InitCache(15552000, $cacheID, "/altasib/geobase/"))
		{
			$arCity = $obCache->GetVars();
		}
		elseif($obCache->StartDataCache())
		{
			$arCity = self::CitySearchByString($city, $bAdmSect, $bCityWorldEn, $sRegionEn, $bOnlyLarge, $lang);
			if(empty($arCity))
			{
				if(CModule::IncludeModule('search'))
				{
					$arLang = CSearchLanguage::GuessLanguage($city);
					if(is_array($arLang) && $arLang["from"] != $arLang["to"])
					{
						$trCity = CSearchLanguage::ConvertKeyboardLayout($city, $arLang["from"], $arLang["to"]);
						if($trCity)
							$arCity = self::CitySearchByString($trCity, $bAdmSect, $bCityWorldEn, $sRegionEn, $bOnlyLarge, $lang);
					}
				}
			}

			BXClearCache(true, "/altasib/geobase/");

			$obCache->EndDataCache($arCity);
		}

		$bArReindex = false;
		foreach(GetModuleEvents(self::MID, "OnBeforeResultCitySearch", true) as $arEvent)
		{
			if(ExecuteModuleEventEx($arEvent, array(&$arCity))===false)
				return false;
			else
				$bArReindex = true;
		}
		if($bArReindex)
			$arCity = array_values($arCity);

		echo json_encode($arCity);
	}

	function CitySearchByString($city, $bAdmSect = false, $bCityWorldEn, $sRegionEn, $bOnlyLarge, $lang)
	{
		$citylen = strlen($city);
		$arCity = array();
		$i = 0;

		if(isset($lang) && strtolower($lang) == "ru" || !$bCityWorldEn) //LANGUAGE_ID
		{
			if($sRegionEn == "regions" || $sRegionEn == "all" || $bAdmSect)
			{
				if($citylen == 2)
					$rsRegions = CAltasibGeoBase::GetRegionByName($city, true, 7);
				elseif($citylen == 3)
					$rsRegions = CAltasibGeoBase::GetRegionByName($city, false, 20);
				elseif($citylen > 3)
					$rsRegions = CAltasibGeoBase::GetRegionByName($city, false, false);

				while($arData = $rsRegions->Fetch())
				{
					if(ToLower(SITE_CHARSET) == 'windows-1251')
					{
						$arCity[(string) $i] = array(
							'REGION' => iconv('windows-1251', 'utf-8', $arData['FULL_NAME']),
							'COUNTRY' => iconv('windows-1251', 'utf-8', GetMessage('ALTASIB_GEOBASE_RUSSIA'))
						);
					}
					else
					{
						$arCity[(string) $i] = array(
							'REGION' => $arData['FULL_NAME'],
							'COUNTRY' => GetMessage('ALTASIB_GEOBASE_RUSSIA')
						);
					}
					$arCity[(string) $i]['ID'] = $arData['ID'];
					$arCity[(string) $i]['DISTRICT'] = '';
					$arCity[(string) $i++]['C_CODE'] = $arData['CODE'];
				}
			}

			if($sRegionEn != "regions" || $bAdmSect)
			{
				if($citylen == 2)
					$rezData = CAltasibGeoBase::GetDataFromKLADR($city, true, 7, $bOnlyLarge);
				elseif($citylen == 3)
					$rezData = CAltasibGeoBase::GetDataFromKLADR($city, false, 20, $bOnlyLarge);
				elseif($citylen > 3)
					$rezData = CAltasibGeoBase::GetDataFromKLADR($city, false, false, $bOnlyLarge);

				$rezDataReg = CAltasibGeoBase::GetCityAsRegionOfKladrByName($city, false, false);

				if($rezDataReg)
				{
					while($arReg = $rezDataReg->Fetch()){
						if(array_key_exists("R_PINDEX", $arReg)){
							if(ToLower(SITE_CHARSET) == 'windows-1251')
							{
								$arCity[(string) $i] = array(
									'CITY' => iconv('windows-1251', 'utf-8', $arReg['R_SOCR'].'. '.$arReg['R_NAME']),
									'REGION' => iconv('windows-1251', 'utf-8', $arReg['R_FNAME'])
								);
							}
							else
							{
								$arCity[(string) $i] = array(
									'CITY' => $arReg['R_SOCR'].'. '.$arReg['R_NAME'],
									'REGION' => $arReg['R_FNAME']
								);
							}
							$arCity[(string) $i++]['C_CODE'] = $arReg['R_CODE'].'000000000';
						}
					}
				}
				while($arData = $rezData->Fetch())
				{
					if(array_key_exists("C_NAME", $arData))
					{
						if(ToLower(SITE_CHARSET) == 'windows-1251')
						{
							$arCity[(string) $i] = array(
								'CITY' => iconv('windows-1251', 'utf-8', $arData['C_SOCR'].'. '.$arData['C_NAME']),
								'DISTRICT' => iconv('windows-1251', 'utf-8', $arData['D_NAME'].' '.$arData['D_SOCR']),
								'REGION' => iconv('windows-1251', 'utf-8', $arData['R_NAME'])
							);
						}
						else
						{
							$arCity[(string) $i] = array(
								'CITY' => $arData['C_SOCR'].'. '.$arData['C_NAME'],
								'DISTRICT' => $arData['D_NAME'].' '.$arData['D_SOCR'],
								'REGION' => $arData['R_NAME']
							);
						}
						$arCity[(string) $i]['ID'] = $arData['ID'];
						$arCity[(string) $i++]['C_CODE'] = $arData['C_CODE'];
					}
				}

				if($arRegion = CAltasibGeoBase::GetHomeRegion())
				{
					if(ToLower(SITE_CHARSET) == 'windows-1251')
						$GLOBALS['RegionName'] = iconv('windows-1251', 'utf-8', $arRegion['FULL_NAME']);
					else
						$GLOBALS['RegionName'] = $arRegion['FULL_NAME'];

					usort($arCity, array('CAltasibGeoBaseTools', 'CompareArr'));
				}
				if($bCityWorldEn)
				{
					$arMM = CAltasibGeoBase::CitySearchMM($city, $lang, false);
					if(is_array($arMM))
						$arCity = array_merge($arCity, $arMM);
				}
			}
		}
		else
		{
			$arCity = CAltasibGeoBase::CitySearchMM($city, $lang, true);
		}

		return $arCity;
	}

	function CitySearchMM($city, $lang, $russia_en = false)
	{
		$citylen = strlen($city);
		$arCity = array();
		$i = 0;
		if($citylen == 2)
			$rezData = CAltasibGeoBase::GetDataByCities($city, $russia_en, true, 7);
		elseif($citylen == 3)
			$rezData = CAltasibGeoBase::GetDataByCities($city, $russia_en, false, 20);
		elseif($citylen > 3)
			$rezData = CAltasibGeoBase::GetDataByCities($city, $russia_en, false, false);

		if($rezData)
		{
			while($arData = $rezData->Fetch())
			{
				if(array_key_exists("CITY_EN", $arData))
				{
					if(CAltasibGeoBase::CheckCountry($arData['COUNTRY_CODE']))
					{
						$regName = CAltasibGeoBase::GetNameReg($arData['COUNTRY_CODE'], $arData['REGION_ID'], true);

						if(ToLower(SITE_CHARSET) == 'windows-1251')
						{
							$arCity[(string) $i] = array(
								'CITY' => iconv('windows-1251', 'utf-8', $arData['CITY_RU']),
								'COUNTRY' => iconv('windows-1251', 'utf-8', $arData['COUNTRY_RU'])
							);
							if(!empty($regName))
								$arCity[(string) $i]['REGION'] = $regName;
						}
						else
						{
							$arCity[(string) $i] = array(
								'CITY' => $arData['CITY_RU'],
								'COUNTRY' => $arData['COUNTRY_RU']
							);
							if(!empty($regName))
								$arCity[(string) $i]['REGION'] = $regName;
						}
					}else{
						$arCity[(string) $i]['CITY'] = $arData['CITY_EN'];
						$arCity[(string) $i]['COUNTRY'] = $arData['COUNTRY_EN'];
					}
					if(empty($arCity[$i]['REGION']) && file_exists($_SERVER["DOCUMENT_ROOT"].self::REG_VARS)){
						require_once($_SERVER["DOCUMENT_ROOT"].self::REG_VARS);
						$arCity[(string) $i]['REGION'] = $GEOIP_REGION_NAME[$arData['COUNTRY_CODE']][$arData['REGION_ID']];
					}
					$arCity[(string) $i]['ID'] = $arData['id'];
					$arCity[(string) $i]['C_CODE'] = $arData['CITY_ID'];
					$arCity[(string) $i++]['COUNTRY_CODE'] = $arData['COUNTRY_CODE'];
				}
			}
			return $arCity;
		}
	}

	function CheckCountry($countryCode)
	{
		$arSNG = array("AZ", "AM", "BY", "KZ", "KG", "MD", "RU", "TJ", "TM", "UZ", "UA", "GE");
		return in_array($countryCode, $arSNG);
	}

	function GetRegionLang($countryCode, $regionID)
	{
		$rsRgn = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "ru", 1);
		if(!($arRegLoc = $rsRgn->Fetch()))
		{
			$arSNG = array("AZ", "AM", "BY", "KZ", "KG", "MD", "RU", "TJ", "TM", "UZ", "UA", "GE");
			$arSNGlang = array("az", "ascii", "be", "kk", "ky", "mo", "ru", "ascii", "ascii", "uz", "uk", "ka");

			$rezRL = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID,
					$arSNGlang[array_search($countryCode, $arSNG)], 1);
			if(!($arRegLoc = $rezRL->Fetch()))
			{
				$rezEN = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "en", 1);
				if(!($arRegLoc = $rezEN->Fetch()))
				{
					$rsASCII = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "ascii", 1);
					$arRegLoc = $rsASCII->Fetch();
				}
			}
		}
		return $arRegLoc;
	}

	function GetCityAsRegionOfKladrByName($city, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT t1.FULL_NAME AS R_FNAME, t1.NAME AS R_NAME, t1.SOCR AS R_SOCR,
				t1.CODE AS R_CODE, t1.POSTINDEX AS R_PINDEX
			FROM altasib_geobase_kladr_region AS t1
			WHERE LOWER(t1.FULL_NAME)
			LIKE '.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.($limit != false ? 'LIMIT '.$limit : '')
			.'AND t1.ACTIVE = "Y" AND (t1.CODE = 77 OR t1.CODE = 78 OR t1.CODE = 92 OR t1.CODE = 99) ORDER BY R_CODE'
		);

		return $data;
	}

	function GetCityAsRegionOfKladrById($limit=false, $regId='77')
	{
		global $DB;

		$data = $DB->Query('SELECT t1.FULL_NAME AS R_FNAME, t1.NAME AS R_NAME, t1.SOCR AS R_SOCR,
				t1.CODE AS R_CODE, t1.POSTINDEX AS R_PINDEX
			FROM altasib_geobase_kladr_region AS t1
			WHERE (t1.CODE = "'.$regId.'") '
			.($limit != false ? 'LIMIT '.$limit : '')
			.'AND t1.ACTIVE = "Y" ORDER BY R_CODE'
		);
		return $data;
	}

	function GetRegionByName($city, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT `ID`, `FULL_NAME`, `NAME`, `SOCR`, `CODE` FROM altasib_geobase_kladr_region '
			.'WHERE LOWER(FULL_NAME) '
			.'LIKE '.($strict != false ? '"'.strtolower($city).'" ' : '"%'.strtolower($city).'%"')
			.' AND `ACTIVE` = "Y" ORDER BY `CODE`'
			.($limit != false ? ' LIMIT '.$limit : '')
		);
		return $data;
	}

	function GetRegionByCode($id)
	{
		global $DB;
		$city_id = $DB->ForSql($id);
		$rsData = $DB->Query('SELECT `ID` as R_ID, `FULL_NAME` as R_FNAME, `NAME` as R_NAME, `SOCR` as R_SOCR, `CODE` as R_CODE, `POSTINDEX` as R_PINDEX '
			.'FROM altasib_geobase_kladr_region '
			.'WHERE LOWER(CODE) LIKE "'.$city_id.'" ORDER BY ID LIMIT 1'
		);
		return $rsData;
	}

	function GetCityByName($city, $afields, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT '.implode(',', $afields)
			.' FROM altasib_geobase_kladr_cities
			WHERE LOWER(NAME)
			LIKE '.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.($limit != false ? 'LIMIT '.$limit : '')
			.'AND `ACTIVE` = "Y" ORDER BY `CODE`, `SORTINDEX` '
		);
		return $data;
	}

	function GetCityByNameReg($city, $afields, $idReg, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT '.implode(',', $afields)
			.' FROM altasib_geobase_kladr_cities
			WHERE LOWER(NAME)
			LIKE '.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.'AND LOWER(ID_DISTRICT) LIKE CONCAT("'.$idReg.'", "%") '
			.($limit != false ? 'LIMIT '.$limit : '')
			.'AND `ACTIVE` = "Y" ORDER BY `SORTINDEX` DESC, `STATUS` DESC'
		);
		return $data;
	}

	function GetDistrictByName($city, $afields, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT '.implode(',', $afields)
			.' FROM altasib_geobase_kladr_districts
			WHERE LOWER(NAME)
			LIKE '.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.($limit != false ? 'LIMIT '.$limit : '')
			.'AND `ACTIVE` = "Y" ORDER BY `CODE` '
		);
		return $data;
	}

	function GetDataSearch($city, $strict=false, $limit=false) //ipgeobase.ru - DB
	{
		global $DB;
		if($DB->TableExists('altasib_geobase_cities')){
			$city = $DB->ForSql($city);

			$data = $DB->Query('SELECT ID, CITY_NAME, REGION_NAME, COUNTY_NAME FROM altasib_geobase_cities
				WHERE LOWER(`CITY_NAME`)
				LIKE '.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
				.($limit != false ? 'LIMIT '.$limit : '')
			);
			return $data;
		} else
			return false;
	}

	function GetDataFromKLADR($city, $strict=false, $limit=false, $onlyLarge=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		if($onlyLarge)
		{
			$g = GetMessage("ALTASIB_GEOBASE_G");
			$pgt = GetMessage("ALTASIB_GEOBASE_PGT");
			$rp = GetMessage("ALTASIB_GEOBASE_RP");
			$town = GetMessage("ALTASIB_GEOBASE_TOWN");
		}
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");

		$rsKLADR = $DB->Query('SELECT t1.ID, t1.NAME AS C_NAME, t1.CODE AS C_CODE, t1.ID_DISTRICT, t1.SOCR AS C_SOCR,
				t2.FULL_NAME AS R_NAME, t2.CODE AS R_CODE,
				t3.NAME AS D_NAME, t3.SOCR AS D_SOCR
			FROM altasib_geobase_kladr_cities AS t1
			LEFT JOIN altasib_geobase_kladr_districts AS t3
				ON t1.ID_DISTRICT = t3.CODE
			LEFT JOIN altasib_geobase_kladr_region AS t2
				ON SUBSTRING(t1.ID_DISTRICT,1,2) = t2.CODE
			WHERE LOWER(t1.NAME) LIKE '
				.($strict != false ? '"'.strtolower($city).'" ' : '"'.strtolower($city).'%" ')
				.($onlyLarge != false ? 'AND (t1.STATUS > 0 OR t1.SOCR="'.$g.'" OR t1.SOCR="'.$pgt.'" OR t1.SOCR="'.$rp.'" OR t1.SOCR="'.$town.'") ' : '')
				.'AND t1.ACTIVE = "Y" ORDER BY t1.SORTINDEX, t2.CODE, t1.NAME '
				.($limit != false ? 'LIMIT '.$limit : '')
		);
		return $rsKLADR;
	}

	function GetDataByCountries($city, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);

		$data = $DB->Query('SELECT * FROM altasib_geobase_mm_country AS country
			WHERE LOWER(country.name_ru) LIKE '
				.($strict != false ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.'ORDER BY country.id '.($limit != false ? 'LIMIT '.$limit : '')
		);
		return $data;
	}

	function GetDataByCities($city, $ru_en=false, $strict=false, $limit=false)
	{
		global $DB;
		$city = $DB->ForSql($city);
		if($strict != false) $strict = true;

		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");

		$data = $DB->Query('SELECT c1.id as CITY_ID, c1.country_id as COUNTRY_ID, c1.name_ru as CITY_RU,
				c1.name_en as CITY_EN, c1.region as REGION_ID, c1.postal_code as POST, c1.latitude, c1.longitude,
				c2.name_ru as COUNTRY_RU, c2.name_en as COUNTRY_EN, c2.code as COUNTRY_CODE
			FROM altasib_geobase_mm_city AS c1
			LEFT JOIN altasib_geobase_mm_country AS c2
			ON COUNTRY_ID = c2.id
			WHERE (LOWER(c1.name_ru) LIKE '
				.($strict ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.' OR LOWER(c1.name_en) LIKE '
				.($strict ? '"'.strtolower($city).'" ' : 'CONCAT("'.strtolower($city).'", "%") ')
			.($ru_en ? ') ' : ') AND c1.country_id != 20 && !(c1.country_id = 112 && (c1.region = 20 || c1.region = 11)) ') //Krim
			.' ORDER BY c1.id '.($limit != false ? 'LIMIT '.$limit : '')
		);
		return $data;
	}

	function GetNameReg($countryCode, $regionID, $recurs=false)
	{
		$arSNG = array("AZ", "AM", "BY", "KZ", "KG", "MD", "RU", "TJ", "TM", "UZ", "UA", "GE");
		$arSNGlang = array("az", "ascii", "be", "kk", "ky", "mo", "ru", "ascii", "ascii", "uz", "uk", "ka");
		$rsRegn = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "ru", 1);
		if(!($arRegLoc = $rsRegn->Fetch()))
		{
			$rezRL = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID,
				$arSNGlang[array_search($countryCode, $arSNG)], 1);
			if(!($arRegLoc = $rezRL->Fetch()))
			{
				$rezEN = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "en", 1);
				if(!($arRegLoc = $rezEN->Fetch()))
				{
					$rsASCII = CAltasibGeoBase::GetRegionLocation($countryCode, $regionID, "ascii", 1);
					$arRegLoc = $rsASCII->Fetch();
				}
			}
			if(empty($arRegLoc["region_name"]) && $recurs){
				if(file_exists($_SERVER["DOCUMENT_ROOT"].self::REG_VARS))
					require_once($_SERVER["DOCUMENT_ROOT"].self::REG_VARS);
				else
					return;
				$reg_name = $GEOIP_REGION_NAME[$countryCode][$regionID];
				$arRegID = CAltasibGeoBase::GetCodeReg($countryCode, $reg_name);
				if(!empty($arRegID))
					$arRegLoc = CAltasibGeoBase::GetNameReg($countryCode, $arRegID, false);
				else
					return;
			}
		}
		if(isset($arRegLoc["region_name"]))
			return $arRegLoc["region_name"];
		else
			return;
	}

	function GetCodeReg($countryCode, $region_name)
	{
		global $DB;
		$countryCode = $DB->ForSql($countryCode);
		$data = $DB->Query('SELECT c1.region_code FROM altasib_geobase_mm_region as c1
				WHERE c1.country_code LIKE "'.$countryCode.'"
				AND c1.region_name LIKE "'.$region_name.'%" LIMIT 1'
		);
		$rez = $data->Fetch();
		if(empty($rez["region_code"]) && strlen($region_name)> 5){
			return CAltasibGeoBase::GetCodeReg($countryCode, substr($region_name, 0, -2));
		}
		return $rez["region_code"];
	}

	function GetRegionLocation($countryCode, $region_code, $lang, $limit=false)
	{
		global $DB;
		$countryCode = $DB->ForSql($countryCode);
		$region_code = $DB->ForSql($region_code);
		$lang = $DB->ForSql($lang);
		$data = $DB->Query('SELECT * FROM altasib_geobase_mm_region AS c1
				WHERE c1.country_code LIKE "'.$countryCode.'"
				AND c1.region_code LIKE "'.$region_code.'"
				AND c1.lang LIKE "'.$lang.'" '
				.($limit != false ? 'LIMIT '.$limit : '')
		);
		return $data;
	}

	function GetHomeRegion()
	{
		$arDtGeo = CAltasibGeoBase::GetAddres();
		if($arDtGeo)
		{
			$reg = $arDtGeo["REGION_NAME"];
			$findme = GetMessage("ALTASIB_GEOBASE_RESPUBLIC");
			$pos = strpos($reg, $findme);
			if($pos !== FALSE)
				$reg = substr($reg, $pos+10).' '.$findme;

			$rsRegs = CAltasibGeoBase::GetRegionByName($reg, false, false);
			if($arRegion = $rsRegs->Fetch())
			{
				$arInfo = array(
					"CODE" => $arRegion["CODE"],
					"NAME" => $arRegion["NAME"],
					"FULL_NAME" => $arRegion["FULL_NAME"],
					"SOCR" => $arRegion["SOCR"]
				);
			}
		}
		return $arInfo;
	}

	function GetCodeByAddr($ip_addr = "")
	{
		$arGeo = CAltasibGeoBase::GetAddres($ip_addr);
		if($arGeo)
		{
			if(empty($arGeo["REGION_NAME"]) && empty($arGeo["CITY_NAME"]))
			{
				if((COption::GetOptionString(self::MID, "ipgeobase_enable", "Y") == "Y"
					|| COption::GetOptionString(self::MID, "geoip_enable", "Y") == "Y")
					&& COption::GetOptionString(self::MID, "source", "local_db") == "statistic")
				{
					if(!$arGeo = CAltasibGeoBaseIPTools::GetGeoData($arGeo["BLOCK_ADDR"]))
						return false;
				}
				else
					return false;
			}
			$reg = $arGeo["REGION_NAME"];
			$regR = $reg;
			$findme = GetMessage("ALTASIB_GEOBASE_RESPUBLIC");
			$pos = strpos($reg, $findme);
			if($pos !== FALSE)
				$regR = trim(substr($reg, $pos+10).' '.$findme);

			$rsRegions = CAltasibGeoBase::GetRegionByName($regR, true, false);

			$arRegion = $rsRegions->Fetch();
			$regLen = strlen($regR);

			if(!$arRegion && $regLen)
			{
				$arRegion = CAltasibGeoBase::GetRegionByName($regR, false, false)->Fetch();
			}
			if(!$arRegion && $regLen)
			{
				$arRp = CAltasibGeoBaseSelected::GetArReplace(true);

				if(isset($arRp[$regR]) || isset($arRp[$reg]))
				{
					$region = (!empty($arRp[$regR]) ? $arRp[$regR] : $arRp[$reg]);
					$arRegion = CAltasibGeoBase::GetRegionByName($region, false, false)->Fetch();
				}
			}

			if(!$arRegion && $regLen > 5)
			{
				$len = ($pos !== FALSE ? 15 : 5);

				$regR = trim(substr($regR, 0, $regLen-$len));
				$rsRegions = CAltasibGeoBase::GetRegionByName($regR, false, false);
				$arRegion = $rsRegions->Fetch();

				if(!$arRegion && $regLen > 5){
					$regR = trim(substr($regR, 0, $regLen-5));
					$rsRegions = CAltasibGeoBase::GetRegionByName($regR, false, false);
					$arRegion = $rsRegions->Fetch();
				}
				if(!$arRegion && $regLen > 7){
					$regR = trim(substr($regR, 0, $regLen-5));
					$rsRegions = CAltasibGeoBase::GetRegionByName($regR, false, false);
					$arRegion = $rsRegions->Fetch();
				}
			}
			if($arRegion)
			{
				$arInfo["REGION"] = array(
					"CODE" => $arRegion["CODE"],
					"NAME" => $arRegion["NAME"],
					"FULL_NAME" => $arRegion["FULL_NAME"],
					"SOCR" => $arRegion["SOCR"]
				);
				$arInfo["DISTRICT"] = array( //default
					"CODE" => $arRegion["CODE"].'000',
					"NAME" => '',
					"SOCR" => ''
				);
				if($arRegion["CODE"] == 77 || $arRegion["CODE"] == 78
					|| $arRegion["CODE"] == 92 || $arRegion["CODE"] == 99 //Mosqow SPb, Sevastopol, Baykonur
				)
				{
					$arInfo["CITY"] = array(
						"ID" => $arRegion["CODE"].'000000000',
						"NAME" => $arRegion["NAME"],
						"SOCR" => $arRegion["SOCR"],
						"POSTINDEX" => $arRegion["POSTINDEX"],
						"ID_DISTRICT" => $arRegion["CODE"].'000'
					);
					$arInfo["CODE"] = $arRegion["CODE"].'000000000';

					$rsCity = CAltasibGeoBase::GetCityByNameReg(trim(htmlspecialcharsEx($arGeo["CITY_NAME"])),
						array('ID', 'NAME', 'SOCR', 'POSTINDEX', 'ID_DISTRICT', 'CODE'), $arRegion["CODE"], false, false);
					if($arCity = $rsCity->Fetch()){
						$arInfo["CITY"] = array(
							"ID" => $arCity["ID"],
							"NAME" => $arCity["NAME"],
							"SOCR" => $arCity["SOCR"],
							"POSTINDEX" => $arCity["POSTINDEX"],
							"ID_DISTRICT" => $arCity["ID_DISTRICT"]
						);
						$arInfo["CODE"] = $arCity["CODE"];

						$rsDistr = CAltasibGeoBase::GetDistrictByName($arCity["ID_DISTRICT"],
							array('NAME', 'SOCR', 'CODE'), false, false);
						if($arDistr = $rsDistr->Fetch()){
							$arInfo["DISTRICT"] = array(
								"CODE" => $arDistr["CODE"],
								"NAME" => $arDistr["NAME"],
								"SOCR" => $arDistr["SOCR"]
							);
						}
					}
				}
				else //others cities
				{
					if(COption::GetOptionString(self::MID, "mode_location", "cities") != "regions")
					{
						$cName = trim(htmlspecialcharsEx($arGeo["CITY_NAME"]));
						$arSlct = array('ID', 'NAME', 'SOCR', 'POSTINDEX', 'ID_DISTRICT', 'CODE');
						$rsCity = CAltasibGeoBase::GetCityByNameReg($cName, $arSlct, $arRegion["CODE"], false, false);
						$arCity = $rsCity->Fetch();
						if(!$arCity)
						{
							$posCName = strpos($cName, "-");
							if($posCName !== FALSE)
								$cName = trim(substr($cName, 0, $posCName));
							$rsCity = CAltasibGeoBase::GetCityByNameReg($cName, $arSlct, $arRegion["CODE"], false, false);
							$arCity = $rsCity->Fetch();
						}
						if(!$arCity)
						{
							$posComa = strpos($cName, ".");
							if($posComa !== FALSE)
								$cName = trim(substr($cName, $posComa+1));
							$rsCity = CAltasibGeoBase::GetCityByNameReg($cName, $arSlct, $arRegion["CODE"], false, false);
							$arCity = $rsCity->Fetch();
						}
						if($arCity)
						{
							$arInfo["CITY"] = array(
								"ID" => $arCity["ID"],
								"NAME" => $arCity["NAME"],
								"SOCR" => $arCity["SOCR"],
								"POSTINDEX" => $arCity["POSTINDEX"],
								"ID_DISTRICT" => $arCity["ID_DISTRICT"]
							);
							$arInfo["CODE"] = $arCity["CODE"];

							$rsDistr = CAltasibGeoBase::GetDistrictByName($arCity["ID_DISTRICT"],
								array('NAME', 'SOCR', 'CODE'), false, false);
							if($arDistr = $rsDistr->Fetch())
							{
								$arInfo["DISTRICT"] = array(
									"CODE" => $arDistr["CODE"],
									"NAME" => $arDistr["NAME"],
									"SOCR" => $arDistr["SOCR"]
								);
							}
						}
					}
				}
			}
		}
		return $arInfo;
	}

	function GetCodeKladr()
	{
		global $APPLICATION;

		if(!is_array($_SESSION["ALTASIB_GEOBASE_CODE"]))
		{
			$ip = CAltasibGeoBaseIP::getUserHostIP();

			if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
			{
				$last_ip = $APPLICATION->get_cookie("ALTASIB_LAST_IP");
				$strData = $APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE");
			}

			if(($ip == $last_ip) && $strData)
			{
				$arData = CAltasibGeoBase::deCodeJSON($strData);
			}
			else
			{
				global $DB;

				if($DB->TableExists('altasib_geobase_kladr_region'))
					$arData = CAltasibGeoBase::GetCodeByAddr();
				elseif($DB->TableExists('altasib_kladr_region'))
					$arData = CAltasibGeoBaseTools::GetCodeKladrByAddr();

				if(!$arData) return false;

				if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
				{
					$strData = CAltasibGeoBase::CodeJSON($arData);
					$APPLICATION->set_cookie("ALTASIB_LAST_IP", $ip, time() + 31104000); //60*60*24*30*12
					$APPLICATION->set_cookie("ALTASIB_GEOBASE_CODE", $strData, time() + 2592000, "/", self::GetCookieServerName(), false, true); //60*60*24*30
				}
			}
			$_SESSION["ALTASIB_GEOBASE_CODE"] = $arData;
		}
		return $_SESSION["ALTASIB_GEOBASE_CODE"];
	}

	function GetDataKladr()
	{
		global $APPLICATION;

		if(!is_array($_SESSION["ALTASIB_GEOBASE_CODE"]))
		{
			$ip = CAltasibGeoBaseIP::getUserHostIP();

			if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
			{
				$last_ip = $APPLICATION->get_cookie("ALTASIB_LAST_IP");
				$strData = $APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE");
			}

			if(($ip == $last_ip) && $strData)
			{
				$arData = CAltasibGeoBase::deCodeJSON($strData);
			}
			else
			{
				global $DB;

				if($DB->TableExists('altasib_geobase_kladr_region'))
					$arData = CAltasibGeoBase::GetCodeByAddr();
				elseif($DB->TableExists('altasib_kladr_region'))
					$arData = CAltasibGeoBaseTools::GetCodeKladrByAddr();
			}
			if(!$arData)
				return false;
			else
				return $arData;
		}
		return $_SESSION["ALTASIB_GEOBASE_CODE"];
	}

	function SetCodeKladr($cityID, $regionCode = false)
	{
		global $APPLICATION;

		$sModLoc = COption::GetOptionString(self::MID, "mode_location", "cities");
		$bIsReg = false;
		if(!empty($regionCode) && !empty($cityID) && $cityID == $regionCode)
			$bIsReg = true;

		if($regionCode && ($sModLoc != "cities" || $bIsReg))
		{
			if($sModLoc != "regions")
				$arData = CAltasibGeoBase::GetInfoKladrByCode($cityID, $regionCode);
			else
				$arData = CAltasibGeoBase::GetInfoKladrByCode(false, $regionCode);
		}
		else
		{
			$arData = CAltasibGeoBase::GetInfoKladrByCode($cityID);

			if(!$arData)
				$arData = CAltasibGeoBase::GetInfoMMByCode($cityID);
		}
		if(!$arData)
			return false;

		if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
		{
			$strData = CAltasibGeoBase::CodeJSON($arData);
			$APPLICATION->set_cookie("ALTASIB_LAST_IP", $ip, time() + 31104000); //60*60*24*30*12
			$APPLICATION->set_cookie("ALTASIB_GEOBASE_CODE", $strData, time() + 2592000, "/", self::GetCookieServerName(), false, true); //60*60*24*30
		}
		$_SESSION["ALTASIB_GEOBASE_CODE"] = $arData;

		unset($_SESSION["ALTASIB_GEOBASE_COUNTRY"]); //carrency & code of country
		CAltasibGeoBase::GetCurrency(false, true, (!empty($arData["COUNTRY_CODE"])? $arData["COUNTRY_CODE"]: ""));

		$events = GetModuleEvents(self::MID, "OnAfterSetSelectCity");
		while($arEvent = $events->Fetch()){
			ExecuteModuleEvent($arEvent, array($arData));
		}
		return true;
	}

	function SetCodeMM($cityName, $countryCode, $regionCode)
	{
		global $APPLICATION;

		$arData = CAltasibGeoBase::GetInfoMMByName($cityName, $countryCode, $regionCode);

		if(!$arData)
			return false;

		if(ToLower(LANG_CHARSET) != 'utf-8' && !empty($arData["REGION"]))
			$arData["REGION"] = iconv("UTF-8", LANG_CHARSET, $arData["REGION"]);

		if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
		{
			$strData = CAltasibGeoBase::CodeJSON($arData);
			$APPLICATION->set_cookie("ALTASIB_LAST_IP", $ip, time() + 31104000); //60*60*24*30*12
			$APPLICATION->set_cookie("ALTASIB_GEOBASE_CODE", $strData, time() + 2592000, "/", self::GetCookieServerName(), false, true); //60*60*24*30
		}
		$_SESSION["ALTASIB_GEOBASE_CODE"] = $arData;

		unset($_SESSION["ALTASIB_GEOBASE_COUNTRY"]); //currency & code of country
		CAltasibGeoBase::GetCurrency(false, true, (!empty($arData["COUNTRY_CODE"]) ? $arData["COUNTRY_CODE"] : ""));

		$events = GetModuleEvents(self::MID, "OnAfterSetSelectCity");
		while($arEvent = $events->Fetch()){
			ExecuteModuleEvent($arEvent, array($arData));
		}
		return true;
	}

	function GetCookieServerName()
	{
		if(!empty($_SERVER["SERVER_NAME"]))
		{
			$long = ip2long($_SERVER["SERVER_NAME"]);

			if($long == -1 || $long === FALSE)
			{
				return ".".$_SERVER["SERVER_NAME"];
			}
		}
		return false;
	}

	function GetInfoKladrByCode($city_ID, $region_code=false)
	{
		if((!$city_ID || $city_ID==$region_code) && !empty($region_code))
		{
			$arDataCode = CAltasibGeoBase::GetRegionByCode($region_code)->Fetch();
		}
		elseif($city_ID == '78000000000' || $city_ID == '77000000000'
			|| $city_ID == '92000000000' || $city_ID == '99000000000') //Moscow, Petersburg, Sevastopol, Baykonur
		{
			$arDataReg = CAltasibGeoBase::GetCityAsRegionOfKladrById(false, $city_ID/1000000000)->Fetch();
			$arDataCode = array(
				"R_NAME" => $arDataReg['R_NAME'],
				"R_FNAME" => $arDataReg['R_FNAME'],
				"R_SOCR" => $arDataReg['R_SOCR'],
				"R_CODE" => $arDataReg['R_CODE'],
				"D_CODE" => '',
				"D_NAME" => '',
				"D_SOCR" => '',
				"ID" => '',
				"C_NAME" => $arDataReg['R_NAME'],
				"C_SOCR" => $arDataReg['R_SOCR'],
				"C_PINDEX" => $arDataReg['R_PINDEX'],
				"ID_DISTRICT" => $arDataReg['R_CODE'].'000',
				"C_CODE" => $arDataReg['R_CODE'].'000000000',
			);
		}
		else
		{
			$arDataCode = CAltasibGeoBase::GetDataKladrByCode($city_ID)->Fetch();
		}

		if($arDataCode)
		{
			$arInfo["REGION"] = array(
				"CODE" => $arDataCode["R_CODE"],
				"NAME" => $arDataCode["R_NAME"],
				"FULL_NAME" => $arDataCode["R_FNAME"],
				"SOCR" => $arDataCode["R_SOCR"]
			);
			if(!empty($arDataCode["R_ID"]))
				$arInfo["REGION"]["ID"] = $arDataCode["R_ID"];
			if(!empty($arDataCode["R_PINDEX"]))
				$arInfo["REGION"]["POSTINDEX"] = $arDataCode["R_PINDEX"];

			$arInfo["DISTRICT"] = array(
				"CODE" => $arDataCode["D_CODE"],
				"NAME" => $arDataCode["D_NAME"],
				"SOCR" => $arDataCode["D_SOCR"]
			);
			$arInfo["CITY"] = array(
				"ID" => $arDataCode["ID"],
				"NAME" => $arDataCode["C_NAME"],
				"SOCR" => $arDataCode["C_SOCR"],
				"POSTINDEX" => $arDataCode["C_PINDEX"],
				"ID_DISTRICT" => $arDataCode["ID_DISTRICT"]
			);
			$arInfo["CODE"] = $arDataCode["C_CODE"];
		}
		return $arInfo;
	}

	function GetInfoMMByCode($city_ID)
	{
		$arDataCode = CAltasibGeoBase::GetDataMMByID($city_ID)->Fetch();
		if($arDataCode)
		{
			$arInfo = array(
				'CITY' => $arDataCode['CITY_EN'],
				'CITY_RU' => $arDataCode['CITY_RU'],
				'REGION_ID' => $arDataCode['REGION_ID'],
				'COUNTRY' => $arDataCode['COUNTRY_EN'],
				'COUNTRY_RU' => $arDataCode['COUNTRY_RU'],
				'latitude' => $arDataCode['latitude'],
				'longitude' => $arDataCode['longitude']
			);

			if(CAltasibGeoBase::CheckCountry($arDataCode['COUNTRY_CODE']))
			{
				$arRG = CAltasibGeoBase::GetRegionLang($arDataCode['COUNTRY_CODE'], $arDataCode['REGION_ID']);
				if(!empty($arRG['region_name']))
				{
					if(ToLower(LANG_CHARSET) == 'windows-1251')
						$arRG['region_name'] = iconv("UTF-8", "windows-1251", $arRG['region_name']);
					$arInfo['REGION'] = $arRG['region_name'];
				}
			}
			else
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].self::REG_VARS))
				{
					require_once($_SERVER["DOCUMENT_ROOT"].self::REG_VARS);
					$arInfo['REGION'] = $GEOIP_REGION_NAME[$arDataCode['COUNTRY_CODE']][$arDataCode['REGION_ID']];
				}
			}

			if(isset($arDataCode['id']))
				$arInfo['ID'] = $arDataCode['id'];

			$arInfo['C_CODE'] = $arDataCode['CITY_ID'];
			$arInfo['COUNTRY_CODE'] = $arDataCode['COUNTRY_CODE'];
			$arInfo['POST'] = $arDataCode['POST'];
		}
		return $arInfo;
	}

	function GetInfoMMByName($cityName, $countryCode, $regionCode)
	{
		global $DB;
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");
		$dataMM = $DB->Query('SELECT c1.id as CITY_ID, c1.country_id as COUNTRY_ID, c1.name_ru as CITY_RU,
				c1.name_en as CITY_EN, c1.region as REGION_ID, c1.postal_code as POST, c1.latitude, c1.longitude,
				c2.name_ru as COUNTRY_RU, c2.name_en as COUNTRY_EN, c2.code as COUNTRY_CODE
			FROM altasib_geobase_mm_city AS c1
			LEFT JOIN altasib_geobase_mm_country AS c2
			ON COUNTRY_ID = c2.id
			WHERE c1.country_id LIKE "'.$countryCode.'" OR c2.code LIKE "'.$countryCode.'" AND '
				.(!empty($regionCode) ? ' c1.region LIKE "'.$regionCode.'" AND ' : '')
			.' LOWER(c1.name_ru) LIKE "'.$cityName.'" OR LOWER(c1.name_en) LIKE "'.$cityName.'" '
			.' ORDER BY CITY_ID LIMIT 1'
		);

		$arDataCode = $dataMM->Fetch();

		if($arDataCode)
		{
			$arInfo = array(
				'CITY' => $arDataCode['CITY_EN'],
				'CITY_RU' => $arDataCode['CITY_RU'],
				'REGION_ID' => $arDataCode['REGION_ID'],
				'COUNTRY' => $arDataCode['COUNTRY_EN'],
				'COUNTRY_RU' => $arDataCode['COUNTRY_RU'],
				'latitude' => $arDataCode['latitude'],
				'longitude' => $arDataCode['longitude']
			);

			if(CAltasibGeoBase::CheckCountry($arDataCode['COUNTRY_CODE']))
			{
				$arRG = CAltasibGeoBase::GetRegionLang($arDataCode['COUNTRY_CODE'], $arDataCode['REGION_ID']);
				if(!empty($arRG['region_name']))
					$arInfo['REGION'] = $arRG['region_name'];
			}
			else
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].self::REG_VARS))
				{
					require_once($_SERVER["DOCUMENT_ROOT"].self::REG_VARS);
					$arInfo['REGION'] = $GEOIP_REGION_NAME[$arDataCode['COUNTRY_CODE']][$arDataCode['REGION_ID']];
				}
			}

			if(isset($arDataCode['id']))
				$arInfo['ID'] = $arDataCode['id'];
			$arInfo['C_CODE'] = $arDataCode['CITY_ID'];
			$arInfo['COUNTRY_CODE'] = $arDataCode['COUNTRY_CODE'];
			$arInfo['POST'] = $arDataCode['POST'];
		}
		else
			$arInfo = CAltasibGeoBase::GetAddres();
		return $arInfo;
	}

	function GetDataMMByID($id)
	{
		global $DB;
		$city_id = $DB->ForSql($id);
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");
		$dataMM = $DB->Query('SELECT c1.id as CITY_ID, c1.country_id as COUNTRY_ID, c1.name_ru as CITY_RU,
				c1.name_en as CITY_EN, c1.region as REGION_ID, c1.postal_code as POST, c1.latitude, c1.longitude,
				c2.name_ru as COUNTRY_RU, c2.name_en as COUNTRY_EN, c2.code as COUNTRY_CODE
			FROM altasib_geobase_mm_city AS c1
			LEFT JOIN altasib_geobase_mm_country AS c2
			ON COUNTRY_ID = c2.id
			WHERE LOWER(c1.id) LIKE "'.$city_id.'" ORDER BY CITY_ID LIMIT 1'
		);
		return $dataMM;
	}

	function GetDataKladrByCode($city)
	{
		global $DB;
		$city = $DB->ForSql($city);
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");
		$dtKLADR = $DB->Query('SELECT t1.ID, t1.NAME AS C_NAME, t1.CODE AS C_CODE, t1.ID_DISTRICT, t1.SOCR AS C_SOCR, t1.POSTINDEX AS C_PINDEX,
				t2.FULL_NAME AS R_FNAME, t2.CODE AS R_CODE, t2.NAME AS R_NAME, t2.SOCR AS R_SOCR,
				t3.NAME AS D_NAME, t3.SOCR AS D_SOCR, t3.CODE AS D_CODE
			FROM altasib_geobase_kladr_cities AS t1
			LEFT JOIN altasib_geobase_kladr_districts AS t3
				ON t1.ID_DISTRICT = t3.CODE
			LEFT JOIN altasib_geobase_kladr_region AS t2
				ON SUBSTRING(t1.ID_DISTRICT,1,2) = t2.CODE
			WHERE LOWER(t1.CODE) LIKE "'.$city.'" '
				.'AND t1.ACTIVE = "Y" ORDER BY C_NAME, R_CODE '
				.'LIMIT 1'
		);
		return $dtKLADR;
	}

	function GetTemplateProps($componentName, $templateName, $siteTemplate = "")
	{
		$componentName = trim($componentName);
		if(strlen($componentName) <= 0)
			return false;

		if(strlen($templateName) <= 0)
			$templateName = ".default";

		if(!preg_match("#[A-Za-z0-9_.-]#i", $templateName))
			return false;

		$path2Comp = CComponentEngine::MakeComponentPath($componentName);
		if(strlen($path2Comp) <= 0)
			return false;

		$componentPath = getLocalPath("components".$path2Comp);

		if(!CComponentUtil::isComponent($componentPath))
			return false;

		if($siteTemplate <> "")
			$siteTemplate = _normalizePath($siteTemplate);

		$folders = array();
		if($siteTemplate <> "")
		{
			$folders[] = "/local/templates/".$siteTemplate."/components".$path2Comp."/".$templateName;
		}
		$folders[] = "/local/templates/.default/components".$path2Comp."/".$templateName;
		$folders[] = "/local/components".$path2Comp."/templates/".$templateName;

		if($siteTemplate <> "")
		{
			$folders[] = BX_PERSONAL_ROOT."/templates/".$siteTemplate."/components".$path2Comp."/".$templateName;
		}
		$folders[] = BX_PERSONAL_ROOT."/templates/.default/components".$path2Comp."/".$templateName;
		$folders[] = "/bitrix/components".$path2Comp."/templates/".$templateName;
		global $APPLICATION;

		foreach($folders as $templateFolder)
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"].$templateFolder))
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].$templateFolder."/script.js")){
					$APPLICATION->AddHeadScript($templateFolder."/script.js");
				}
				if(file_exists($_SERVER["DOCUMENT_ROOT"].$templateFolder."/style.css")){
					$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");
				}
			}
		}
	}

	function UpOnBeforeEndBufferContent()
	{
		if(!IsModuleInstalled(self::MID))
			return;
		if(defined("ADMIN_SECTION") && ADMIN_SECTION === true)
			return;
		if(defined("NO_GEOBASE") && NO_GEOBASE === true)
			return;

		CAltasibGeoBaseTools::CheckUpdateSessionData();
		CAltasibGeoBaseTools::CheckForRedirect(); //redirects
		CAltasibGeoBaseTools::AddScriptYourCityOnSite();
		return true;
	}

	function GetTypeCurrency($country=false, $ip_addr="")
	{
		if(CModule::IncludeModule("currency"))
			$currency = CCurrency::GetBaseCurrency();

		if(!$country)
			$country = CAltasibGeoBase::GetCountry($ip_addr);

		if(!empty($country))
			$currency = "USD";

		if($country == "RU")
			$currency = "RUB";
		else
		{
			$arEU = array('AT','BE','BG','GB','HU','DE','GR','DK','IE','ES','IT','CY','LV','LT','LU','MT',
				'NL','PL','PT','RO','SK','SI','FI','FR','CZ','SE','EE');
			if(in_array($country, $arEU))
				$currency = "EUR";
		}
		return $currency;
	}

	function GetCurrency($use_func=false, $reload=false, $country="")
	{
		$reload = !$reload ? false : true;
		global $APPLICATION;

		if($country == "")
		{
			if(!is_array($_SESSION["ALTASIB_GEOBASE_COUNTRY"]) || $reload)
			{
				$ip = CAltasibGeoBaseIP::getUserHostIP();

				if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y"){
					$last_ip = $APPLICATION->get_cookie("ALTASIB_LAST_IP");
					$sCountry = $APPLICATION->get_cookie("ALTASIB_GEOBASE_COUNTRY");

					if(($ip == $last_ip) && $sCountry && !$reload)
						$arData = CAltasibGeoBase::deCodeJSON($sCountry);
				}

				if(empty($arData) || count($arData) == 0 || empty($arData["country"]))
				{
					$arCountry = CAltasibGeoBase::GetCountry("", $use_func);
					$arData = array("country" => $arCountry);

					if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y"){
						$strData = CAltasibGeoBase::CodeJSON($arData);
						$APPLICATION->set_cookie("ALTASIB_LAST_IP", $ip, time() + 31104000);
						$APPLICATION->set_cookie("ALTASIB_GEOBASE_COUNTRY", $strData, time() +2592000);
					}
				}
				$_SESSION["ALTASIB_GEOBASE_COUNTRY"] = $arData;
			}
		}
		else
		{
			$arData = array("country" => $country);

			if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y"){
				$strData = CAltasibGeoBase::CodeJSON($arData);
				$APPLICATION->set_cookie("ALTASIB_GEOBASE_COUNTRY", $strData, time() +2592000); //60*60*24*30
			}
			$_SESSION["ALTASIB_GEOBASE_COUNTRY"] = $arData;
		}
		return $_SESSION["ALTASIB_GEOBASE_COUNTRY"];
	}

	function GetCountry($ip_addr="", $use_func=true)
	{
		if($ip_addr == "")
		{
			global $APPLICATION;
			$CODE = "";
			$arDataC = $_SESSION["ALTASIB_GEOBASE_CODE"];
			if(!empty($arDataC["COUNTRY_CODE"]))
				$CODE = $arDataC["COUNTRY_CODE"];
			elseif(!empty($arDataC["REGION"]["CODE"]) && isset($arDataC["CITY"]["NAME"]))
				$CODE = "RU";
			else
			{
				$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));

				if(!empty($arDataC["COUNTRY_CODE"]))
					$CODE = $arDataC["COUNTRY_CODE"];
				elseif(!empty($arDataC["REGION"]["CODE"]) && isset($arDataC["CITY"]["NAME"]))
					$CODE = "RU";
			}

			if(!empty($arDataC) && isset($arDataC["CITY"]["NAME"]))
				$CODE = "RU";

			if(empty($CODE))
			{
				$arDataS = $_SESSION["ALTASIB_GEOBASE"];
				if(is_array($arDataS) && !empty($arDataS["COUNTRY_CODE"]))
				{
					$CODE = $arDataS["COUNTRY_CODE"];
				}
				else
				{
					if(COption::GetOptionString(self::MID, "set_cookie", "Y") == "Y")
					{
						$arDataS = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE"));
						if(is_array($arDataS) && !empty($arDataS["COUNTRY_CODE"])){
							$CODE = $arDataS["COUNTRY_CODE"];
						}
					}
				}

				if(is_array($arDataS) && isset($arDataS["COUNTRY_CODE"]))
					$CODE = $arDataS["COUNTRY_CODE"];
			}
		}
		else
		{
			if($use_func)
				$arDataC = CAltasibGeoBase::GetCodeByAddr($ip_addr);
			if(!empty($arDataC) && $arDataC["REGION"]["CODE"] != "01" && !empty($arDataC["CITY"]["CODE"])){
				$CODE = "RU";
			}
			else{
				if($use_func)
					$arDataS = CAltasibGeoBase::GetAddres($ip_addr, false);
				if(!empty($arDataS))
					if(isset($arDataS["COUNTRY_CODE"]))
						$CODE = $arDataS["COUNTRY_CODE"];
			}
		}
		unset($arDataC, $arDataS);
		return $CODE;
	}

	function DeviceIdentification()
	{
		$sType='';
		if(isset($_COOKIE['ALTASIB_SITETYPE']))
			$sType=$_COOKIE['ALTASIB_SITETYPE'];
		else
		{
			$detect = new CAltasibGeoBaseMobile_Detect();
			if($detect->isMobile() && !$detect->isTablet())
				$sType='mobile';
			elseif($detect->isMobile() && $detect->isTablet())
				$sType='pda';
			else
				$sType='original';
			setcookie('ALTASIB_SITETYPE', $sType, time()+2592000,'/'); //3600*24*30
			define('ALTASIB_SITETYPE',$sType);
		}
		return($sType);
	}

	function GetRedirectUri($url, $reload=false)
	{
		$sCityURL = "";

		$arSelCity = CAltasibGeoBaseSelected::GetCurrentCityFromSelected();
		// if(!empty($arSelCity) && count($arSelCity) > 0){
			$sCityURL = CAltasibGeoBaseSelected::GetUFValue($arSelCity["ID"]);
		// }

		if(!empty($sCityURL))
		{
			if(COption::GetOptionString(self::MID, 'redirect_save_curpage', 'N') == "Y")
			{
				if(!empty($url))
					$sCityURL .= $url;
				elseif(!empty($_SERVER["HTTP_REFERER"]))
				{
					$arURL = parse_url($_SERVER["HTTP_REFERER"]);
					if(!empty($arURL["path"]))
						$sCityURL .= $arURL["path"];
				}
			}
		}
		elseif($reload)
		{
			$sCityURL = "#reload";
		}

		return $sCityURL;
	}

	function GetTemplate($str)
	{
		$ar_template = explode(",", $str);
		foreach($ar_template as &$value){
			if($value == SITE_TEMPLATE_ID)
				return true;
		}
	}

	function CheckSite($str)
	{
		$ar_sites = explode(",", $str);
		foreach($ar_sites as &$value)
		{
			if($value == SITE_ID)
				return true;
		}
	}

	function GetCitiesIPGB($arCity)
	{
		global $DB;

		if(empty($arCity))
			return;
		elseif(is_array($arCity) && !empty($arCity["CITY"]))
		{
			$arCity = array($arCity);
		}

		$mmcExst = $DB->TableExists('altasib_geobase_mm_city');
		$agbExst = $DB->TableExists('altasib_geobase_cities');
		if(!$agbExst)
			return;
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");
		$b1 = true;
		foreach($arCity as $k => $v)
		{
			if($b1)
				$b1 = false;
			else
				$sSql .= ' UNION ';

			if($mmcExst && isset($v["MM"]) && $v["MM"]===true)
			{
				$sSql .= '(SELECT c.id as ID, c.name_ru as CITY_NAME, r.region_name as REGION_NAME, ctr.name_ru as COUNTY_NAME, c.latitude as BREADTH_CITY, c.longitude as LONGITUDE_CITY '
				.'FROM altasib_geobase_mm_city as c '
				.'LEFT JOIN altasib_geobase_mm_country AS ctr '
					.'ON c.country_id = ctr.id '
				.'LEFT JOIN altasib_geobase_mm_region AS r '
					.'ON ctr.code = r.country_code AND c.region = r.region_code '
				.'WHERE LOWER(c.name_ru) LIKE "'.$v["CITY"].'" and LOWER(ctr.code) LIKE "'.$v["CTR_CODE"].'" LIMIT 1 )';
			}
			else
			{
				if(isset($v["STRICT"]) && $v["STRICT"]===true)
					$sSql .= '(SELECT * from altasib_geobase_cities where LOWER(CITY_NAME) LIKE "'.$v["CITY"].'" and LOWER(REGION_NAME) LIKE "'.$v["REGION"].'" )';
				else
					$sSql .= '(SELECT * from altasib_geobase_cities where LOWER(CITY_NAME) LIKE "'.$v["CITY"].'" and LOWER(REGION_NAME) LIKE "%'.$v["REGION"].'%" )';
			}
		}

		$db_res = $DB->Query($sSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);

		return $db_res;
	}

	function SearchSaleLocations($search, $siteId, $country=false, $region=false, $strLang = LANGUAGE_ID)
	{
		$arResult = array();
		global $APPLICATION, $DB;
		if(\Bitrix\Main\Loader::includeModule('sale'))
		{
			if(!empty($search) && is_string($search))
			{
				$search = $APPLICATION->UnJSEscape($search);
				$siteId = $DB->ForSql($siteId);
				if($country || $region){
					$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("city", $siteId);
					$filter["~CITY_NAME"] = $search."%";
					$filter["LID"] = $strLang;

					if($country)
						$filter["~COUNTRY_NAME"] = $DB->ForSql($country);
					if($region)
						$filter["~REGION_NAME"] = $DB->ForSql($region);

					$rsLocsList = CSaleLocation::GetList(
						array(
							"CITY_NAME_LANG" => "ASC",
							"COUNTRY_NAME_LANG" => "ASC",
							"SORT" => "ASC",
						),
						$filter,
						false,
						array("nTopCount" => 10),
						array("ID", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG")
					);
					while($arCity = $rsLocsList->GetNext())
					{
						$arResult[] = array(
							"ID" => $arCity["ID"],
							"NAME" => $arCity["CITY_NAME"],
							"REGION_NAME" => $arCity["REGION_NAME_LANG"],
							"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
						);
					}
				}
				else
				{
					$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("city", $siteId);
					$filter["~CITY_NAME"] = $search."%";
					$filter["LID"] = $strLang;

					$rsLocsList = CSaleLocation::GetList(
						array(
							"CITY_NAME_LANG" => "ASC",
							"COUNTRY_NAME_LANG" => "ASC",
							"SORT" => "ASC",
						),
						$filter,
						false,
						array("nTopCount" => 10),
						array("ID", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG")
					);
					while($arCity = $rsLocsList->GetNext())
					{
						$arResult[] = array(
							"ID" => $arCity["ID"],
							"NAME" => $arCity["CITY_NAME"],
							"REGION_NAME" => $arCity["REGION_NAME_LANG"],
							"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
						);
					}

					$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("region", $siteId);
					$filter["~REGION_NAME"] = $search."%";
					$filter["LID"] = $strLang;
					$filter["CITY_ID"] = false;
					$rsLocsList = CSaleLocation::GetList(
						array(
							"CITY_NAME_LANG" => "ASC",
							"COUNTRY_NAME_LANG" => "ASC",
							"SORT" => "ASC",
						),
						$filter,
						false,
						array("nTopCount" => 10),
						array("ID", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG")
					);
					while($arCity = $rsLocsList->GetNext())
					{
						$arResult[] = array(
							"ID" => $arCity["ID"],
							"NAME" => "",
							"REGION_NAME" => $arCity["REGION_NAME_LANG"],
							"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
						);
					}

					$filter = \Bitrix\Sale\SalesZone::makeSearchFilter("country", $siteId);
					$filter["~COUNTRY_NAME"] = $search."%";
					$filter["LID"] = $strLang;
					$filter["CITY_ID"] = false;
					$filter["REGION_ID"] = false;
					$rsLocsList = CSaleLocation::GetList(
						array(
							"COUNTRY_NAME_LANG" => "ASC",
							"SORT" => "ASC",
						),
						$filter,
						false,
						array("nTopCount" => 10),
						array("ID", "COUNTRY_NAME_LANG")
					);
					while($arCity = $rsLocsList->GetNext())
					{
						$arResult[] = array(
							"ID" => $arCity["ID"],
							"NAME" => "",
							"REGION_NAME" => "",
							"COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
						);
					}
				}
			}
		}
		return $arResult;
	}

	function SearchLocation($search, $country=false, $region=false, $strLang = LANGUAGE_ID)
	{
		$arRes = array();
		if(!\Bitrix\Main\Loader::includeModule('sale'))
			return;

		if(empty($search) || !is_string($search))
			return false;

		$langId = strlen($strLang) ? $strLang : LANGUAGE_ID;
		$cityName = ToUpper($search);

		$filter = array(
			'=NAME.LANGUAGE_ID' => $langId,
			'NAME.NAME_UPPER' => $cityName,
		);

		if($region == $search) // region search
		{
			if($country)
			{
				$countryName = ToUpper($country);
				$filter['%PARENT.PARENT.NAME.NAME_UPPER'] = $countryName;
			}
		}
		else
		{
			if($region)
			{
				$regionName = ToUpper($region);
				$filter['%PARENT.NAME.NAME_UPPER'] = $regionName;
			}
			if($country)
			{
				$countryName = ToUpper($country);
				$filter['%PARENT.PARENT.PARENT.NAME.NAME_UPPER'] = $countryName;
			}
		}

		$select = array('CODE', 'ID', 'NAME', 'TYPE',// '*',
			'PARENT.*',
			'PARENT.NAME',
			'PARENT.PARENT.NAME',
			// 'PARENT.PARENT.PARENT.NAME',
		);
		$arLocs = \Bitrix\Sale\Location\LocationTable::getList(array(
			'select' => $select,
			'filter' => $filter,
		))->fetch();
		if(!empty($arLocs))
		{
			$curVal = $arLocs['CODE'];
			$i = 0;
			$arRes[$i] = array(
				"ID" => $arLocs["ID"],
				"CODE" => $arLocs["CODE"],
				"NAME" => $arLocs["SALE_LOCATION_LOCATION_NAME_NAME"],
			);

			$arTypes = array(
				"COUNTRY" => 1,
				"REGION" => 3,
				"SUBREGION" => 4,
				"CITY" => 5,
				"VILLAGE" => 6,
			);
			if($arRes)
			{
				$res = \Bitrix\Sale\Location\TypeTable::getList(array(
					'select' => array('*', 'NAME_RU' => 'NAME.NAME'),
					'filter' => array('=NAME.LANGUAGE_ID' => $langId)
				));
				while($item = $res->fetch())
				{
					switch($item["CODE"])
					{
						case "COUNTRY":
							$arTypes["COUNTRY"] = $item["ID"];
							break;
						case "REGION":
							$arTypes["REGION"] = $item["ID"];
							break;
						case "SUBREGION":
							$arTypes["SUBREGION"] = $item["ID"];
							break;
						case "CITY":
							$arTypes["CITY"] = $item["ID"];
							break;
						case "VILLAGE":
							$arTypes["VILLAGE"] = $item["ID"];
							break;
					}
				}
			}

			$curType = $arLocs["SALE_LOCATION_LOCATION_TYPE_ID"];
			$parentType = $arLocs["SALE_LOCATION_LOCATION_PARENT_TYPE_ID"];

			if($curType == $arTypes["CITY"])
			{
				if($parentType == $arTypes["REGION"])
				{
					$arRes[$i]["REGION_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_NAME_NAME"];
					if(!empty($arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_NAME_NAME"]))
						$arRes[$i]["COUNTRY_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_NAME_NAME"];
				}
				elseif($parentType == $arTypes["SUBREGION"])
				{
					$arRes[$i]["AREA_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_NAME_NAME"];
					if(!empty($arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_NAME_NAME"]))
						$arRes[$i]["REGION_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_NAME_NAME"];
					if(!empty($arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_PARENT_NAME_NAME"]))
						$arRes[$i]["COUNTRY_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_PARENT_NAME_NAME"];
				}
			}
			elseif($curType == $arTypes["SUBREGION"])
			{
				if($parentType == $arTypes["REGION"])
				{
					$arRes[$i]["REGION_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_NAME_NAME"];
					if(!empty($arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_NAME_NAME"]))
						$arRes[$i]["COUNTRY_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_PARENT_NAME_NAME"];
				}
			}
			elseif($curType == $arTypes["REGION"])
			{
				$arRes[$i]["REGION_NAME"] = $arLocs["SALE_LOCATION_LOCATION_NAME_NAME"];
				if(!empty($arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_NAME_NAME"]))
					$arRes[$i]["COUNTRY_NAME"] = $arLocs["SALE_LOCATION_LOCATION_PARENT_PARENT_NAME_NAME"];
			}
		}

		return $arRes;
	}

	function GetBXLocations($city = "", $country = "", $region = "")
	{
		if(!CModule::IncludeModule(self::MID))
			return;

		if(empty($city) && empty($country))
		{
			$usrChoice = CAltasibGeoBase::GetDataKladr();
			$auto = CAltasibGeoBase::GetAddres();

			if(!empty($usrChoice["CITY"]["SOCR"]))
			{
				$city = $usrChoice["CITY"]["NAME"];
				$region = $usrChoice["REGION"]["NAME"];
				$country = GetMessage("ALTASIB_GEOBASE_RUSSIA");
			}
			elseif(!empty($usrChoice["REGION"]["FULL_NAME"]))
			{
				$city = $usrChoice["REGION"]["FULL_NAME"];
				$cityScr = $usrChoice["REGION"]["NAME"];
				$region = $usrChoice["REGION"]["FULL_NAME"];
				$country = GetMessage("ALTASIB_GEOBASE_RUSSIA");
			}
			elseif(!empty($usrChoice["COUNTRY_RU"]) || !empty($usrChoice["CITY_RU"]))
			{
				$arCurr = CAltasibGeoBase::GetCurrency($use_func=true, $reload=false);
				$city = ((!empty($usrChoice["CITY_RU"]) && CAltasibGeoBase::CheckCountry($arCurr["country"])) ?
					$usrChoice["CITY_RU"] : (!empty($usrChoice["CITY"]) ? $usrChoice["CITY"] : ''));
				$country = ((!empty($usrChoice["COUNTRY_RU"]) && $arResult['RU_ENABLE'] == "Y") ?
				$usrChoice["COUNTRY_RU"] : (!empty($usrChoice["COUNTRY"]) ? $usrChoice["COUNTRY"] : ''));
				$city_ru = ((!empty($usrChoice["CITY_RU"])) ? $usrChoice["CITY_RU"] : '');
				$country_ru = ((!empty($usrChoice["COUNTRY_RU"])) ? $usrChoice["COUNTRY_RU"] : '');
				$region = ((!empty($usrChoice["region"])) ? $usrChoice["region"] : '');
			}
			elseif(!empty($auto["CITY_NAME"]))
			{
				$city = $auto["CITY_NAME"];
				$country = $auto["COUNTRY_NAME"];
				$region = $auto["REGION_NAME"];
			}
		}

		if(!empty($country))
		{
			$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID, $country);
			if(empty($arLocsTwo)){
				$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID, $country, false, "ru");
			}
			if(empty($arLocsTwo) && !empty($city_ru)){
				$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city_ru, SITE_ID, $country);
			}

			if(empty($arLocsTwo)){
				if($country_ru == GetMessage("ALTASIB_GEOBASE_RF") || $country == "Russian Federation")
					$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID, "Russia");
					if(empty($arLocsTwo))
						$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID, GetMessage("ALTASIB_GEOBASE_RUSSIA"));
					if(empty($arLocsTwo))
						$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city_ru, SITE_ID, GetMessage("ALTASIB_GEOBASE_RUSSIA"));
			}
		}
		if(empty($arLocsTwo) && !empty($country_ru)){
			$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID, $country_ru);
			if(empty($arLocsTwo) && !empty($city_ru))
				$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($city_ru, SITE_ID, $country_ru);
		}
		if(empty($arLocsTwo) && !empty($region)){
			$arLocsTwo = CAltasibGeoBase::SearchSaleLocations($region, SITE_ID, $country_ru);
		}
		//simplify
		if(empty($arLocsTwo)){
			$arLocs = CAltasibGeoBase::SearchSaleLocations($city, SITE_ID);
			if(!empty($city_ru))
				$arLocs[] = CAltasibGeoBase::SearchSaleLocations($city_ru, SITE_ID);

			if(empty($arLocs)){
				$arLocs = CAltasibGeoBase::SearchSaleLocations($country, SITE_ID);
				if(empty($arLocs) && !empty($country_ru)){
					$arLocs = CAltasibGeoBase::SearchSaleLocations($country_ru, SITE_ID);
				}
			}
		}

		if($city == $region)
		{

			$arRe = CAltasibGeoBaseSelected::GetArReplaceLocations();

			if(isset($arRe[$city]))
			{
				$city = $region = $arRe[$city];
			}
			else
			{
				$findR = GetMessage("ALTASIB_GEOBASE_RESPUBLIC");
				if(strpos($city, $findR)!==false && strpos($city, GetMessage("ALTASIB_GEOBASE_SKA_R"))==false)
				{
					$city = $region = $findR.' '.trim(str_replace($findR, '', $city));
				}
			}
		}

		if(empty($arLocsTwo))
		{
			if(!empty($country) && $country == GetMessage("ALTASIB_GEOBASE_RUSSIA"))
				$country = GetMessage("ALTASIB_GEOBASE_R_F");

			$arLocsTwo = CAltasibGeoBase::SearchLocation($city, $country, $region);
		}
		if(empty($arLocsTwo))
		{
			$arLocsTwo = CAltasibGeoBase::SearchLocation($city, false, $region);
		}
		if(empty($arLocsTwo))
		{
			$arLocsTwo = CAltasibGeoBase::SearchLocation($city, false, false);
		}
		if(empty($arLocsTwo) && !empty($cityScr))
		{
			$arLocsTwo = CAltasibGeoBase::SearchLocation('%'.$cityScr, false, false);
		}

		if(!empty($arLocsTwo))
		{
			foreach($arLocsTwo as $unit)
			{
				if(!empty($unit["NAME"]))
				{
					$rez = $unit["ID"]; break;
				}
			}
			if(empty($rez))
				foreach($arLocsTwo as $unit)
				{
					if(!empty($unit["ID"]))
					{
						$rez = $unit["ID"]; break;
					}
				}
		}
		if(!empty($arLocs) && empty($rez))
		{
			foreach($arLocs as $unit)
			{
				if(!empty($unit["NAME"]))
				{
					$rez = $unit["ID"]; break;
				}
			}
			if(empty($rez))
			{
				foreach($arLocs as $unit)
				{
					foreach($unit as $elem)
					{
						if(!empty($elem["ID"]))
						{
							$rez = $elem["ID"]; break;
						}
					}
				}
			}
			if(empty($rez))
				foreach($arLocs as $unit)
				{
					if(!empty($unit["ID"]))
					{
						$rez = $unit["ID"]; break;
					}
				}
		}
		if(!empty($rez))
			return $rez;
		else
			return false;
	}
}

Class CAltasibGeoBaseAllSelected extends CAltasibGeoBase
{
	const MID = "altasib.geobase";

	function GetCitySelected()	//altasib_geobase_selected.php
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
			return false;

		if(!check_bitrix_sessid('sessid') && !IsIE())
			return false;

		if(!isset($_POST['city_name']) && !isset($_POST['add_city'])
			&& !isset($_POST['delete_city']) && !isset($_POST['update']))
		{
			return false;
		}
		elseif(empty($_POST['city_name']) && empty($_POST['add_city'])
			&& empty($_POST['delete_city']) && empty($_POST['update']))
			die('pusto');
		elseif(isset($_POST['city_name'])) //search cities
		{
			return CAltasibGeoBase::CitySearch(true);
		}
		elseif(isset($_POST['add_city']) && $_POST['add_city'] == 'Y') //add city
		{
			if(isset($_POST['city_id']))
			{
				global $DB;
				$city_id = $DB->ForSql($_POST['city_id']);

				BXClearCache(true, "/altasib/geobase/");

				if(strlen($city_id) <= 2)
					return(CAltasibGeoBaseSelected::BeforeAddRegion($city_id));
				elseif(strlen($city_id) < 11)
					return(CAltasibGeoBaseSelected::BeforeAddMMCity($city_id));
				else
					return(CAltasibGeoBaseSelected::BeforeAddCity($city_id));
			}
		}
		elseif(isset($_POST['delete_city']) && $_POST['delete_city'] == 'Y')
		{
			if(isset($_POST['entry_id']))	//delete city from table altasib_geobase_selected
			{
				global $DB;
				$city_id = $DB->ForSql($_POST['entry_id']);

				BXClearCache(true, "/altasib/geobase/");

				return(CAltasibGeoBaseAllSelected::DeleteCity($city_id));
			}
		}
		elseif(isset($_POST['update']) && $_POST['update'] == 'Y') //restart html table
		{
			return(CAltasibGeoBaseSelected::UpdateCityRows());
		}
	}

	function AddCity($arFields)
	{
		global $DB;

		if(!CAltasibGeoBaseSelected::CheckFields($arFields))
			return false;

		$arSel = $DB->PrepareInsert("altasib_geobase_selected", $arFields);

		$strSql = "INSERT INTO altasib_geobase_selected(".$arSel[0].") " . "VALUES(".$arSel[1].")";
		$DB->Query($strSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);
		$ID = IntVal($DB->LastID());

		return $ID;
	}

	function ChangeSortCity($ID, $sort)
	{
		global $DB;
		$ID = IntVal($ID);

		if($ID<=0)
			return false;

		if(empty($sort) || $sort<=0)
			return false;

		$sort = $DB->ForSql($sort);

		$DB->Query("UPDATE altasib_geobase_selected SET SORT = '".$sort."' WHERE ID = ".$ID, true);
			return true;
	}

	function DeleteCity($ID)
	{
		global $DB;
		$ID = IntVal($ID);

		if($ID<=0)
			return false;

		$DB->Query("DELETE FROM altasib_geobase_selected WHERE ID = ".$ID, true);
			return true;
	}

	public static function GetByID($ID)
	{
		global $DB;
		$ID = intval($ID);

		$mm_exsts = $DB->TableExists('altasib_geobase_mm_country');
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");

		$strSql = 'SELECT t1.ID, t1.ACTIVE, t1.SORT, t1.NAME AS C_NAME, t1.NAME_EN AS C_NAME_EN, t1.CODE AS C_CODE, t1.ID_DISTRICT, '
			.'t1.SOCR AS C_SOCR, t2.FULL_NAME AS R_FNAME, t2.NAME AS R_NAME, t3.NAME AS D_NAME, t3.SOCR AS D_SOCR, t1.COUNTRY_CODE AS CTR_CODE, '
			.($mm_exsts ? ' t4.name_ru AS CTR_NAME_RU,' : ''). ' t1.ID_REGION AS R_ID '
			.'FROM altasib_geobase_selected AS t1 '
			.'LEFT JOIN altasib_geobase_kladr_districts AS t3 '
				.'ON t1.ID_DISTRICT = t3.CODE '
			.'LEFT JOIN altasib_geobase_kladr_region AS t2 '
				.'ON SUBSTRING(t1.ID_DISTRICT,1,2) = t2.CODE '
			.($mm_exsts ? ' LEFT JOIN altasib_geobase_mm_country AS t4 '
				.'ON t1.COUNTRY_CODE = t4.code ' : '')
			.'WHERE t1.ID = '.$ID.' ';

		$z = $DB->Query($strSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);
		return $z;
	}

	function GetMoreCities($active = false)
	{
		global $DB;
		$mm_exsts = $DB->TableExists('altasib_geobase_mm_country');
		if(COption::GetOptionString(self::MID, "set_sql", "Y") == "Y")
			$DB->Query("SET SQL_BIG_SELECTS=1");
		$strSql = 'SELECT t1.ID, t1.ACTIVE, t1.SORT, t1.NAME AS C_NAME, t1.NAME_EN AS C_NAME_EN, t1.CODE AS C_CODE, t1.ID_DISTRICT, t1.SOCR AS C_SOCR,
			t2.FULL_NAME AS R_FNAME, t2.NAME AS R_NAME, t3.NAME AS D_NAME, t3.SOCR AS D_SOCR, t1.COUNTRY_CODE AS CTR_CODE, '
			.($mm_exsts ? ' t4.name_ru AS CTR_NAME_RU,' : ''). ' t1.ID_REGION AS R_ID
			FROM altasib_geobase_selected AS t1
			LEFT JOIN altasib_geobase_kladr_districts AS t3
				ON t1.ID_DISTRICT = t3.CODE
			LEFT JOIN altasib_geobase_kladr_region AS t2
				ON SUBSTRING(t1.ID_DISTRICT,1,2) = t2.CODE '
			.($mm_exsts ? ' LEFT JOIN altasib_geobase_mm_country AS t4
				ON t1.COUNTRY_CODE = t4.code ' : '')
			.($active != false ? 'WHERE t1.ACTIVE = "Y" ' : '')
			.'ORDER BY t1.SORT, t1.ID ';

		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);

		return $db_res;
	}

	function GetMoreCacheCities()
	{
		$obCache = new CPHPCache();
		if($obCache->InitCache(86400, "GetMoreCacheCities", "/altasib/geobase/"))
		{
			$arCities = $obCache->GetVars();
		}
		elseif($obCache->StartDataCache())
		{
			$arCities = array();
			$rsCity = CAltasibGeoBaseAllSelected::GetMoreCities(true);
			while($arCity = $rsCity->Fetch()){
				$arCities[] = $arCity;
			}
			BXClearCache(true, "/altasib/geobase/");

			$obCache->EndDataCache($arCities);
		}
		return $arCities;
	}
}


Class CAltasibGeoBaseIP extends CAltasibGeoBase
{
	const MID = "altasib.geobase";
	const ipgeobase = "http://ipgeobase.ru:7020/geo/";
	const geoip_top = "http://geoip.elib.ru/cgi-bin/getdata.pl";

	function GetGeoDataIpgeobase_ru($ip)
	{
		if(empty($ip))
			return;
		if(!CAltasibGeoBaseIP::CheckServiceAccess(self::ipgeobase))
			return;
		if(!function_exists('curl_init'))
		{
			if(!$text = file_get_contents(self::ipgeobase.'?ip='.$ip))
				return false;
		}
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, self::ipgeobase."?ip=".$ip);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);

			$text = curl_exec($ch);
			$errno = curl_errno($ch);
			$errstr = curl_error($ch);
			curl_close($ch);

			if($errno)
				return false;
		}
		if(ToLower(SITE_CHARSET) != 'windows-1251')
			$text = iconv("windows-1251", SITE_CHARSET, $text);

		$arData_ = CAltasibGeoBaseIPTools::ParseXML($text);

		$arData = Array(
			"ID" => "",
			"BLOCK_BEGIN" => "",
			"BLOCK_END" => "",
			"BLOCK_ADDR" => $arData_["inetnum"],
			"COUNTRY_CODE" => $arData_["country"],
			"CITY_ID" => "",
			"CITY_NAME" => $arData_["city"],
			"REGION_NAME" => $arData_["region"],
			"COUNTY_NAME" => $arData_["district"],
			"BREADTH_CITY" => $arData_["lat"],
			"LONGITUDE_CITY" => $arData_["lng"]
		);

		return $arData;
	}

	function GetGeoDataGeoip_Elib_ru($ip)
	{
		if(empty($ip))
			return;

		if(!CAltasibGeoBaseIP::CheckServiceAccess(self::geoip_top))
			return;

		$siteCode = COption::GetOptionString(self::MID, "geoip_top_code_".SITE_ID, "");

		$strUrl = self::geoip_top.'?ip='.$ip.'&hex=3f'; //3ffd
		if(!empty($siteCode))
			$strUrl .= "&sid=".$siteCode;

		if(!function_exists('curl_init'))
		{
			if(!$text = file_get_contents($strUrl))
				return false;
		}
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $strUrl);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$text = curl_exec($ch);
			$errno = curl_errno($ch);
			$errstr = curl_error($ch);
			curl_close($ch);

			if($errno)
				return false;
		}

		if(ToLower(SITE_CHARSET) != 'utf-8')
			$text = iconv("UTF-8", SITE_CHARSET, $text);

		$arData_ = CAltasibGeoBaseIPTools::ParseXML($text);
		if(isset($arData_["Error"]) || empty($arData_))
			return false;

		$arData = Array(
			"IP_ADDR" => $ip,
			"COUNTRY" => $arData_["Country"],
			"CITY_NAME" => $arData_["Town"],
			"REGION_NAME" => $arData_["Region"],
			"BREADTH_CITY" => $arData_["Lat"],
			"LONGITUDE_CITY" => $arData_["Lon"],
			"TIME_ZONE" => $arData_["TZ"]
		);

		return $arData;
	}

	function CheckServiceAccess($address) //Check for availability of the service
	{
		stream_context_set_default(
			array(
				'http' => array(
					'method' => 'HEAD',
					'timeout' => 7
				)
			)
		);
		$headers = @get_headers($address);
		if($headers === false)
		{
			$headers = self::get_headers_curl($address);
			if($headers === false)
				return "Not connection";
		}

		if(preg_match("/200/", $headers[0]))
			return true;

		return false;
	}

	function get_headers_curl($url)
	{
		if(!function_exists('curl_init'))
			return false;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,			$url);
		curl_setopt($ch, CURLOPT_HEADER,		true);
		curl_setopt($ch, CURLOPT_NOBODY,		true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT,		15);

		$r = curl_exec($ch);
		$r = explode("\n", $r);
		return $r;
	}

	/**
	 * Get user ip address
	 * @return null|string
	 */
	public function getUserHostIP()
	{
		$sIP = null;
		$headers = array(
			'HTTP_X_REAL_IP',
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		);
		foreach($headers as $header)
		{
			if(isset($_SERVER[$header]))
			{
				foreach(explode(',', $_SERVER[$header]) as $ip)
				{
					$ip = trim($ip);
					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
					{
						$sIP = $ip;
						break;
					}
				}
			}
		}
		return $sIP;
	}
}

if(method_exists(CModule, "AddAutoloadClasses")){
	CModule::AddAutoloadClasses(
		"altasib.geobase",
		$arClassesList
	);
} else {
	foreach($arClassesList as $sClassName => $sClassFile){
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.geobase/".$sClassFile);
	}
}
?>