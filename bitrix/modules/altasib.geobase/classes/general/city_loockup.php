<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2017 ALTASIB
 */

IncludeModuleLangFile(__FILE__);


class CAltasibGeoBaseCityLookup extends CCityLookup
{
	var $country_avail = false;
	var $city_avail = false;
	const MID = "altasib.geobase";

	public static function OnCityLookup($arDBRecord = false)
	{
		return new CAltasibGeoBaseCityLookup($arDBRecord);
	}

	function __construct($arDBRecord = false)
	{
		parent::__construct($arDBRecord);
		global $DB;

		if(!$arDBRecord)
		{
			$country_recs = COption::GetOptionString(self::MID, "COUNTRY_INDEX_LOADED", "N");
			if($country_recs !== "Y")
			{
				$rs = $DB->Query("SELECT COUNTRY_CODE FROM altasib_geobase_codeip LIMIT 1");
				if($rs->Fetch())
				{
					$country_recs = "Y";
					COption::SetOptionString(self::MID, "COUNTRY_INDEX_LOADED", "Y");
				}
			}

			if($country_recs !== "Y")
			{
				$rs = $DB->Query("SELECT * FROM altasib_geobase_mm_country LIMIT 1");
				if($rs->Fetch())
				{
					$country_recs = "Y";
					COption::SetOptionString(self::MID, "COUNTRY_INDEX_LOADED", "Y");
				}
			}

			$this->country_avail = $country_recs === "Y";


			if($this->country_avail)
			{
				$city_recs = COption::GetOptionString("statistic", "CITY_INDEX_LOADED", "N");
				if($city_recs !== "Y")
				{
					$rs = $DB->Query("SELECT CITY_NAME FROM altasib_geobase_cities WHERE CITY_NAME != '' LIMIT 1");
					if($rs->Fetch())
					{
						$city_recs = "Y";
						COption::SetOptionString("statistic", "CITY_INDEX_LOADED", "Y");
					}
				}

				if($city_recs !== "Y")
				{
					$rs = $DB->Query("SELECT name_en, name_ru FROM altasib_geobase_mm_city WHERE name_ru != '' LIMIT 1");
					if($rs->Fetch())
					{
						$city_recs = "Y";
						COption::SetOptionString("statistic", "CITY_INDEX_LOADED", "Y");
					}
				}
				$this->city_avail = $city_recs === "Y";
			}

			$this->is_installed = $this->country_avail;
		}
	}

	function GetFullInfo()
	{
		$this->GetBaseInfo();

		if(!$this->region_name && !$this->city_name)
		{
			if($this->city_id > 0)
			{
				$DB = CDatabase::GetModuleConnection('statistic');
				$rs = $DB->Query("
					SELECT
						C.NAME COUNTRY_NAME,
						CITY.REGION REGION_NAME,
						CITY.NAME CITY_NAME
					from
						b_stat_city CITY
						INNER JOIN b_stat_country C on C.ID = CITY.COUNTRY_ID
					WHERE
						CITY.ID = ".intval($this->city_id));
				$ar = $rs->Fetch();
				if($ar)
				{
					$this->country_full_name = $ar["COUNTRY_NAME"];
					$this->region_name = $ar["REGION_NAME"];
					$this->city_name = $ar["CITY_NAME"];
				}
			}
		}
		return parent::GetFullInfo();
	}

	// set city, region, country
	function GetBaseInfo()
	{
		if(COption::GetOptionString(self::MID, "loockup_user", "Y") == "Y")
		{
			$arRes = CAltasibGeoBase::GetDataKladr();
			if($arRes)
			{
				if(!empty($arRes["CODE"]))
					$this->city_id = $arRes["CODE"];

				if(!empty($arRes["REGION"]) && is_array($arRes["REGION"]) && !empty($arRes["REGION"]["FULL_NAME"]))
					$this->region_name = $arRes["REGION"]["FULL_NAME"];
				elseif(!empty($arRes["REGION"]) && !is_array($arRes["REGION"]))
					$this->region_name = $arRes["REGION"];

				if(!empty($arRes["CITY"]) && is_array($arRes["CITY"]) && !empty($arRes["CITY"]["NAME"]))
				{
					$this->city_name = $arRes["CITY"]["NAME"];
					$this->country_full_name = GetMessage("ALTASIB_GEOBASE_RF");
				}

				if(!empty($arRes["CITY_RU"]))
					$this->city_name = $arRes["CITY_RU"];
				elseif(!empty($arRes["CITY"]) && !is_array($arRes["CITY"]))
					$this->city_name = $arRes["CITY"];

				if(!empty($arRes["COUNTRY_RU"]))
					$this->country_full_name = $arRes["COUNTRY_RU"];
				elseif(!empty($arRes["COUNTRY"]) && !is_array($arRes["COUNTRY"]))
					$this->country_full_name = $arRes["COUNTRY"];

				if(!empty($arRes["COUNTRY_CODE"]))
					$this->country_code = $arRes["COUNTRY_CODE"];
				if(!empty($arRes["C_CODE"]))
					$this->city_id = $arRes["C_CODE"];

				if(empty($this->country_code))
					$this->country_code = "RU";
			}
		}

		if(empty($arRes))
		{
			$arRsAdr = CAltasibGeoBase::GetAddres();
			if($arRsAdr)
			{
				if(!empty($arRsAdr["COUNTRY_CODE"]))
					$this->country_code = $arRsAdr["COUNTRY_CODE"];

				$this->city_id = $arRsAdr["ID"];

				if(!empty($arRsAdr["REGION_NAME"]))
					$this->region_name = $arRsAdr["REGION_NAME"];

				if(!empty($arRsAdr["CITY_NAME"]))
					$this->city_name = $arRsAdr["CITY_NAME"];

				if(!empty($arRsAdr["COUNTRY_NAME"]))
					$this->country_full_name = $arRsAdr["COUNTRY_NAME"];

				if(empty($this->country_code))
					$this->country_code = "RU";
			}
		}
		return (!empty($arRes) || !empty($arRsAdr));
	}

	function GetDescription()
	{
		return array(
			"CLASS" => "CAltasibGeoBaseCityLookup",
			"DESCRIPTION" => GetMessage("ALTASIB_GEOBASE_LUP_DESC"),
			"IS_INSTALLED" => true,
			"CAN_LOOKUP_COUNTRY" => $this->country_avail,
			"CAN_LOOKUP_CITY" => $this->city_avail,
		);
	}

	function IsInstalled()
	{
		return true;
	}

	function Lookup()
	{
		$this->GetBaseInfo();

		if(!$this->region_name && !$this->city_name)
		{
			$DB = CDatabase::GetModuleConnection('statistic');

			if($this->city_avail && $this->ip_number)
			{
				$rs = $DB->Query("
					SELECT *
					FROM b_stat_city_ip
					WHERE START_IP = (
						SELECT MAX(START_IP)
						FROM b_stat_city_ip
						WHERE START_IP <= ".$this->ip_number."
					)
					AND END_IP >= ".$this->ip_number."
				", true);

				if($rs)
				{
					$ar = $rs->Fetch();
					if($ar)
					{
						$this->country_code = $ar["COUNTRY_ID"];
						$this->city_id = $ar["CITY_ID"];
					}
				}
				else
				{
					//Here is mysql 4.0 version which does not supports subqueries
					//and not smart to optimeze query
					$rs = $DB->Query("
						SELECT START_IP
						FROM b_stat_city_ip
						WHERE START_IP <= ".$this->ip_number."
						ORDER BY START_IP DESC
						LIMIT 1
					");
					$ar = $rs->Fetch();
					if($ar && strlen($ar["START_IP"]) > 0)
					{
						$rs = $DB->Query("
							SELECT *
							FROM b_stat_city_ip
							WHERE START_IP = ".$ar["START_IP"]."
							AND END_IP >= ".$this->ip_number."
						");
						$ar = $rs->Fetch();
						if($ar)
						{
							$this->country_code = $ar["COUNTRY_ID"];
							$this->city_id = $ar["CITY_ID"];
						}
					}
				}
			}
		}

		if(!$this->country_code && $this->country_avail)
		{
			$this->country_code = i2c_get_country();
		}
	}
}
