<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2017 ALTASIB
 */

IncludeModuleLangFile(__FILE__);
Class CAltasibGeoBaseSelected extends CAltasibGeoBaseAllSelected
{
	const UF_OBJECT_ENTITY_ID = "ALTASIB_GEOBASE";
	const UF_FIELD_URL = "UF_ALX_GB_URL";

	function UpdateCityRows()
	{
		global $USER_FIELD_MANAGER;

		$rsCity = CAltasibGeoBaseAllSelected::GetMoreCities(true);
		while($arCity = $rsCity->Fetch())
		{
			if(empty($arCity["R_FNAME"]))
			{
				$arRG = CAltasibGeoBase::GetRegionLang($arCity["CTR_CODE"], $arCity['R_ID']);
				if(!empty($arRG['region_name']))
				{
					if(ToLower(LANG_CHARSET) == 'windows-1251')
						$arRG['region_name'] = iconv("UTF-8", LANG_CHARSET, $arRG['region_name']);

					$arCity["R_FNAME"] = $arRG['region_name'];
				}
			}

			$entID = $arCity['ID'];
			$arUserFields = $USER_FIELD_MANAGER->GetUserFields(self::UF_OBJECT_ENTITY_ID, $entID, LANGUAGE_ID);

			$sUFtable = '';

			if(!empty($arUserFields) && is_array($arUserFields))
			{
				foreach($arUserFields as $aField)
				{
					if(!empty($aField["VALUE"]) || (!is_array($aField["VALUE"]) && strlen((string)$aField["VALUE"])>0))
					{
						$sUFname = ($aField["LIST_COLUMN_LABEL"] ? $aField["LIST_COLUMN_LABEL"]: $aField["FIELD_NAME"]);

						if(is_array($aField["VALUE"]))
						{
							$iUFcount = count($aField["VALUE"]);
							if(!$iUFcount)
								$iUFcount = 1;
							$sUFtable .= '<tr>
								<td rowspan="'.$iUFcount.'" class="adm-detail-content-cell-l">'.$sUFname.'</td>
								<td class="adm-detail-content-cell-r" width="50%">'.htmlspecialcharsEx($aField["VALUE"][0]).'</td>
							</tr>';

							foreach($aField["VALUE"] as $kn=>$val)
							{
								if($kn == 0)
									continue;
								$sUFtable .= '<tr>
									<td class="adm-detail-content-cell-r" width="50%">'
										.htmlspecialcharsEx($val)
									.'</td>
								</tr>';
							}
						}
						else
						{
							$sUFtable .= '<tr>
								<td class="adm-detail-content-cell-l">'.$sUFname.'</td>
								<td class="adm-detail-content-cell-r" width="50%">'.htmlspecialcharsEx($aField["VALUE"]).'</td>
							</tr>';
						}
					}
				}

				if(!empty($sUFtable))
					$sUFtable = '<table cellspacing="0" cellpadding="10" width="100%" border="0" align="center">'
						.$sUFtable.'</table>';
			}

			echo '<tr class="altasib_geobase_city_line">'
				.'<td>'.htmlspecialcharsEx($arCity["ID"]).'</td>'
				.'<td>'.htmlspecialcharsEx($arCity["SORT"]).'</td>'
				.'<td>'.htmlspecialcharsEx($arCity["C_SOCR"]).'</td>'
				.'<td width="16%">'.htmlspecialcharsEx($arCity["C_NAME"])
				.'<td width="16%">'.(!empty($arCity["C_NAME_EN"]) ? htmlspecialcharsEx($arCity["C_NAME_EN"]) : '').'</td>'
				.'<td>'.$arCity["C_CODE"].'</td>'
				.'<td>'.(htmlspecialcharsEx(!empty($arCity['D_NAME']) ? $arCity['D_NAME'].' '.$arCity['D_SOCR'] : $arCity['ID_DISTRICT'])).'</td>'
				.'<td>'.htmlspecialcharsEx($arCity["R_FNAME"]).'</td>'
				.'<td>'.htmlspecialcharsEx($arCity["CTR_CODE"]).'</td>'
				.'<td>'.htmlspecialcharsEx($arCity["CTR_NAME_RU"]).'</td>'
				.'<td>'.$sUFtable
				.'<input class="altasib_gb_uf_edit" name="altasib_geobase_uf_'.$arCity['ID'].'" onclick="altasib_geobase_uf('.$entID.');return false;" value="'.(empty($sUFtable) ? GetMessage("ALTASIB_TABLE_ADD") : GetMessage("ALTASIB_TABLE_EDIT")).'"></td>'
				.'<td><input type="submit" name="altasib_geobase_del_'.$arCity['ID'].'" value="'.GetMessage("ALTASIB_TABLE_CITY_DELETE").'" onclick="altasib_geobase_delete_click('.$arCity['ID'].');return false;"></td>'
			.'</tr>';
		}
	}

	function BeforeAddCity($cityId)
	{
		$arData = CAltasibGeoBase::GetInfoKladrByCode($cityId);
		if(!$arData)
			return false;
		$arField = array(
			'ACTIVE' => 'Y',
			'SORT' => 500,
			'NAME' => $arData['CITY']['NAME'],
			'NAME_EN' => "",
			'CODE' => $arData['CODE'],
			'ID_DISTRICT' => $arData['CITY']['ID_DISTRICT'],
			'ID_REGION' => $arData['REGION']['CODE'],
			'COUNTRY_CODE' => "RU",
			'SOCR' => $arData['CITY']['SOCR']
		);

		return(CAltasibGeoBaseAllSelected::AddCity($arField));
	}

	function BeforeAddRegion($regId)
	{
		$arData = CAltasibGeoBase::GetRegionByCode($regId)->Fetch();
		if(!$arData)
			return false;
		$arField = array(
			'ACTIVE' => 'Y',
			'SORT' => 500,
			'NAME' => $arData['R_NAME'],
			'NAME_EN' => "",
			'CODE' => $arData['R_CODE'],
			'ID_DISTRICT' => $arData['R_CODE']."000",
			'ID_REGION' => $arData['R_CODE'],
			'COUNTRY_CODE' => "RU",
			'SOCR' => $arData['R_SOCR']
		);

		return(CAltasibGeoBaseAllSelected::AddCity($arField));
	}

	function BeforeAddMMCity($cityId)
	{
		$arData = CAltasibGeoBase::GetDataMMByID($cityId)->Fetch();
		if(!$arData)
			return false;
		$arField = array(
			'ACTIVE' => 'Y',
			'SORT' => 500,
			'NAME' => $arData['CITY_RU'],
			'NAME_EN' => $arData['CITY_EN'], // new
			'CODE' => $arData['CITY_ID'],
			'ID_DISTRICT' => "",
			'ID_REGION' => $arData['REGION_ID'],
			'COUNTRY_CODE' => $arData['COUNTRY_CODE'],
			'SOCR' => ""
		);

		return(CAltasibGeoBaseAllSelected::AddCity($arField));
	}

	function CheckFields(&$arFields)
	{
		if(is_set($arFields, "NAME") && strlen($arFields["NAME"]) <= 0)
			return false;
		if(is_set($arFields, "CODE") && strlen($arFields["CODE"]) <= 0)
			return false;
		if(is_set($arFields, "ID_REGION") && strlen($arFields["ID_REGION"]) <= 0)
			return false;

		return true;
	}

	function GetCityByID($ID, $afields, $active = false)
	{
		global $DB;
		$ID = IntVal($ID);
		if($ID<=0) return false;

		if(empty($afields) || !is_array($afields))
			$sFields = "*";

		$strSql =
		"SELECT ".(!empty($sFields) ? $sFields : implode(',', $afields))." FROM altasib_geobase_selected ".
		"WHERE ID = ".$ID
		.($active != false ? 'AND ACTIVE = "Y"' : '')
		." ORDER BY ID";

		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);

		if($res = $db_res->Fetch())
		{
			return $res;
		}
		return false;
	}

	function GetAllCities($afields, $active = false)
	{
		global $DB;
		$strSql =
		"SELECT ".implode(',', $afields)." FROM altasib_geobase_selected "
		.($active != false ? 'WHERE ACTIVE = "Y"' : '')
		." ORDER BY `ID`, `SORT`";

		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br />Line: ".__LINE__);

		return $db_res;
	}

	/**
	 * form constructor
	 */
	public static function GetTabs()
	{
		if(intval($_REQUEST['ID'])>0){

			$entID = intval($_REQUEST['ID']);

			global $USER_FIELD_MANAGER;

			if(
				(count($USER_FIELD_MANAGER->GetUserFields(self::UF_OBJECT_ENTITY_ID)) > 0) ||
				($USER_FIELD_MANAGER->GetRights(self::UF_OBJECT_ENTITY_ID) >= "W")
			)
			{
				$USER_FIELD_MANAGER->EditFormShowTab(self::UF_OBJECT_ENTITY_ID, false, $entID);
			}
		}
	}

	/**
	 * Saving values of the form
	 */
	public static function SetValues($ID)
	{
		if(intval($ID) > 0)
		{
			global $USER_FIELD_MANAGER;

			$arFields = array();

			$USER_FIELD_MANAGER->EditFormAddFields(self::UF_OBJECT_ENTITY_ID, $arFields);

			CUtil::decodeURIComponent($arFields);

			if(!empty($arFields))
			{
				$USER_FIELD_MANAGER->Update(self::UF_OBJECT_ENTITY_ID, intval($ID), $arFields);
			}
		}
	}

	/**
	 * The output value of the custom property by ID
	 */
	public static function GetFieldsCity($ID, $lang)
	{
		$ID = intval($ID);
		$arUFields = array();
		if(isset($ID))
		{
			global $USER_FIELD_MANAGER;
			$arUFields = $USER_FIELD_MANAGER->GetUserFields(self::UF_OBJECT_ENTITY_ID, $ID, $lang);
		}
		return $arUFields;
	}

	/**
	 * Return list of IBlock elements
	 */
	public static function GetElementList($IBLOCK_ID, $arID)
	{
		if(CModule::IncludeModule('iblock'))
		{
			if(!empty($arID))
			{
				$arFilter = Array(
					"IBLOCK_ID"=>$IBLOCK_ID,
					"ID"=>$arID,
				);

				$rs = CIBlockElement::GetList(
					array("SORT"=>"ASC"),
					$arFilter,
					false,
					false,
					array("ID", "NAME", "CODE", "IBLOCK_ID")
				);
				$arElems = array();
				while($arElem = $rs->Fetch())
				{
					$arElems[] = array(
						"ID" => $arElem["ID"],
						"VALUE" => $arElem["NAME"],
						"SORT" => $arElem["SORT"],
						"CODE" => $arElem["CODE"],
					);
				}
			}
		}
		return $arElems;
	}

	/**
	 * Return list of IBlock sections
	 */
	public static function GetSectionList($IBLOCK_ID, $arID)
	{
		if(CModule::IncludeModule('iblock'))
		{
			if(!empty($arID))
			{
				$arFilter = Array(
					"IBLOCK_ID"=>$IBLOCK_ID,
					"ID"=>$arID,
				);

				$rs = CIBlockSection::GetList(
					array("SORT"=>"ASC"),
					$arFilter,
					false,
					array("ID", "NAME", "CODE", "IBLOCK_ID"),
					false
				);
				$arSects = array();
				while($arS = $rs->Fetch())
				{
					$arSects[] = array(
						"ID" => $arS["ID"],
						"VALUE" => $arS["NAME"],
						"SORT" => $arS["SORT"],
						"CODE" => $arS["CODE"],
					);
				}
			}
		}
		return $arSects;
	}

	/**
	 * Return list of enum values
	 */
	public static function GetEnumList($USER_FIELD_ID, $arID)
	{
		$obEnum = new CUserFieldEnum;

		$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $USER_FIELD_ID, "ID"=>$arID));
		$arValues = array();
		while($arEnum = $rsEnum->GetNext())
		{
			$arValues[] = $arEnum;
		}

		return $arValues;
	}

	protected static function HandlerUrl($url)
	{
		$url = ToLower(trim($url));
		if(strpos($url,"http://") === false)
		{
			$url = "http://".$url;
		}
		return $url;
	}

	function GetCurrentCityFromSelected()
	{
		global $APPLICATION;
		if(!empty($_SESSION["ALTASIB_GEOBASE_CODE"]))
		{
			$arResult['USER_CHOICE'] = $_SESSION["ALTASIB_GEOBASE_CODE"];
		}
		else
		{
			// Cookies
			$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));
			if(is_array($arDataC) && !empty($arDataC))
				$arResult['USER_CHOICE'] = $arDataC;
		}
		if(empty($arResult['USER_CHOICE']))
		{
			if(!empty($_SESSION["ALTASIB_GEOBASE"]))
			{
				$arDataO = $_SESSION["ALTASIB_GEOBASE"];
			}
			else
			{
				$arDataOCk = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE"));
				if(is_array($arDataOCk) && !empty($arDataOCk))
					$arDataO = $arDataOCk;
			}
			if(empty($arDataO))
				$arDataO = CAltasibGeoBase::GetCodeByAddr(); // On-line auto detection

			if($arDataO["CITY"]["NAME"] != GetMessage('ALTASIB_GEOBASE_KLADR_CITY_NAME'))
			{
				$arResult['AUTODETECT'] = $arDataO;
			}
		}

		$arCities = CAltasibGeoBaseSelected::GetMoreCacheCities();

		$arSelCity = array();

		foreach($arCities as $arCity)
		{
			if($arResult['USER_CHOICE']["CODE"] == $arCity['C_CODE'])
			{
				$arSelCity = $arCity;
			}
			elseif($arResult['USER_CHOICE']["C_CODE"] == $arCity['C_CODE'])
			{
				$arSelCity = $arCity;
			}
			elseif(isset($arResult['USER_CHOICE']["REGION"]) && isset($arResult['USER_CHOICE']["REGION"]["CODE"])
				&& $arResult['USER_CHOICE']["REGION"]["CODE"] == $arCity['C_CODE']
			)
			{
				$arSelCity = $arCity;
			}
			elseif($arResult['USER_CHOICE']["CITY_RU"] == $arCity['C_NAME'])
			{
				$arSelCity = $arCity;
			}
			elseif(!empty($arResult['USER_CHOICE']["CITY_EN"]) && strlen($arResult['USER_CHOICE']["CITY_EN"]) > 0
				&& !empty($arCity['C_NAME_EN']) && strlen($arCity['C_NAME_EN']) > 0
				&& $arResult['USER_CHOICE']["CITY_EN"] == $arCity['C_NAME_EN'])
			{
				$arSelCity = $arCity;
			}
			elseif($arResult['AUTODETECT']["CODE"] == $arCity['C_CODE'])
			{
				$arSelCity = $arCity;
			}
			elseif($arResult['AUTODETECT']["CITY_NAME"] == $arCity['C_NAME'])
			{
				$arSelCity = $arCity;
			}
			elseif($arResult['AUTODETECT']["CITY_NAME"] == $arCity['C_NAME'] && $arResult['AUTODETECT']["REGION_NAME"] == $arCity['R_FNAME'])
			{
				$arSelCity = $arCity;
			}
		}
		return $arSelCity;
	}

	/**
	 * Returns the nearest selected city by the coordinates or region,
	 * to the processing of the city selected by the user or automatically defined
	 */
	function GetNearestCityFromSelected($searchMode = "all", $userChoiceEn = true)
	{
		global $APPLICATION;
		if(!empty($_SESSION["ALTASIB_GEOBASE_CODE"]))
		{
			$arUsrCh = $_SESSION["ALTASIB_GEOBASE_CODE"];
		}
		else
		{
			// Cookies
			$arDataC = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE_CODE"));
			if(is_array($arDataC) && !empty($arDataC))
				$arUsrCh = $arDataC;
		}

		if(!empty($_SESSION["ALTASIB_GEOBASE"]))
		{
			$arDataO = $_SESSION["ALTASIB_GEOBASE"];
		}
		else
		{
			$arDataOCk = CAltasibGeoBase::deCodeJSON($APPLICATION->get_cookie("ALTASIB_GEOBASE"));
			if(is_array($arDataOCk) && !empty($arDataOCk))
				$arDataO = $arDataOCk;
		}
		if(empty($arDataO))
			$arDataO = CAltasibGeoBase::GetAddres(); // On-line auto detection

		if(!empty($arDataO["CITY_NAME"]) || (!empty($arDataO["CITY"]["NAME"])
			&& $arDataO["CITY"]["NAME"] != GetMessage('ALTASIB_GEOBASE_KLADR_CITY_NAME'))
		)
		{
			$arAutoDt = $arDataO;
		}

		$bread = '';
		$long = '';
		$curReg = '';
		$curRegKLADR = '';
		$curRegMM = '';
		$curCity = '';
		$curRegID = '';

		if(!empty($arUsrCh))
		{
			if(!empty($arUsrCh["REGION"]))
			{
				if(!empty($arUsrCh["REGION"]["CODE"]))
					$curRegID = $arUsrCh["REGION"]["CODE"];

				if(!empty($arUsrCh["REGION"]["FULL_NAME"]))
					$curRegKLADR = $arUsrCh["REGION"]["NAME"];
				elseif(is_string($arUsrCh["REGION"]))
				{
					$curRegMM = $arUsrCh["REGION"];
				}

			}
			if(!empty($arUsrCh["CITY"]))
			{
				if(!empty($arUsrCh["CITY"]["NAME"]))
					$curCity = $arUsrCh["CITY"]["NAME"];
				elseif(!empty($arUsrCh["CITY_RU"]))
					$curCity = $arUsrCh["CITY_RU"];
				elseif(is_string($arUsrCh["CITY"]))
					$curCity = $arUsrCh["CITY"];
			}
		}

		if(!empty($arAutoDt))
		{
			if(!empty($arAutoDt["BREADTH_CITY"]) && !empty($arAutoDt["LONGITUDE_CITY"]))
			{
				$bread = $arAutoDt["BREADTH_CITY"];
				$long = $arAutoDt["LONGITUDE_CITY"];
			}
			elseif(!empty($arAutoDt["latitude"]) && !empty($arAutoDt["longitude"]))
			{
				$bread = $arAutoDt["latitude"];
				$long = $arAutoDt["longitude"];
			}
			if(!empty($arAutoDt["REGION_NAME"]))
			{
				$curReg = $arAutoDt["REGION_NAME"];
			}
			if(empty($curReg) && !empty($arAutoDt["REGION"]) && !empty($arAutoDt["REGION"]["FULL_NAME"]))
			{
				$curReg = $arAutoDt["REGION"]["FULL_NAME"];
			}
		}

		if(empty($bread) && empty($long) && empty($curReg) && empty($curRegKLADR) && empty($curRegMM)
			&& empty($curCity) && empty($curRegID) && empty($arUsrCh["latitude"]) && empty($bread)
		)
			return false;

		$arRAcc = self::GetArReplace();
		$arCities = self::GetMoreCacheCities();

		if(!empty($arCities))
		{
			foreach($arCities as $i=>$city)
			{
				$arCt[$i]["CITY"] = $city["C_NAME"];
				$arCt[$i]["REGION"] = $city["R_NAME"];
				$arCt[$i]["STRICT"] = false;

				if(!empty($city["CTR_CODE"]) && $city["CTR_CODE"] != "RU")
				{
					$arCt[$i]["MM"] = true;
					$arCt[$i]["CTR_CODE"] = $city["CTR_CODE"];
				}
				if(isset($arRAcc[$city["R_NAME"]]))
				{
					$arCt[$i]["REGION"] = $arRAcc[$city["R_NAME"]];
					$arCt[$i]["STRICT"] = true;
				}
			}
		}

		if(!empty($arCt))
		{
			$rsCt = CAltasibGeoBase::GetCitiesIPGB($arCt);
			if($rsCt && !is_null($rsCt))
			{
				while($arCts = $rsCt->Fetch())
				{
					$arTwns[] = $arCts;
				}
			}
		}

		$nearId = false;

		if($userChoiceEn)
		{
			if($searchMode == "geo" || $searchMode == "all")
			{
				// Search by MaxMind coordinates USER_CHOICE
				if($nearId === false)
				{
					if(!empty($arTwns) && !empty($arUsrCh["latitude"]) && !empty($arUsrCh["longitude"]))
						$nearId = self::GetNearFromArr($arTwns, $arUsrCh["latitude"], $arUsrCh["longitude"]);
				}

				// Search by coordinates USER_CHOICE, correlated with ipgeobase
				if($nearId === false)
				{
					$arReg = array();
					$arReg["CITY"] = $curCity;
					$arReg["REGION"] = ($curRegKLADR ? $curRegKLADR : $curRegMM);
					$arReg["STRICT"] = false;

					if(isset($arRAcc[$curRegKLADR]))
					{
						$arReg["REGION"] = $arRAcc[$curRegKLADR];
						$arReg["STRICT"] = true;
					}
					elseif(isset($arRAcc[$curRegMM]))
					{
						$arReg["REGION"] = $arRAcc[$curRegMM];
						$arReg["STRICT"] = true;
					}

					$rsCtR = CAltasibGeoBase::GetCitiesIPGB($arReg);
					if($rsCtR && !is_null($rsCtR) && $arCits = $rsCtR->Fetch())
					{
						if(!empty($arTwns) && !empty($arCits["BREADTH_CITY"]) && !empty($arCits["LONGITUDE_CITY"]))
							$nearId = self::GetNearFromArr($arTwns, $arCits["BREADTH_CITY"], $arCits["LONGITUDE_CITY"]);
					}
					else
					{
						// Search by coordinates of region center for USER_CHOICE, corr. with ipgeobase
						$arAdm = self::GetRegionAdmCenter($curRegID);

						$arReg = array();
						$arReg["CITY"] = $arAdm["NAME"];
						$arReg["REGION"] = $arAdm["REGION_NAME"];
						$arReg["STRICT"] = false;

						if(isset($arRAcc[$arAdm["REGION_NAME"]]))
						{
							$arReg["REGION"] = $arRAcc[$arAdm["REGION_NAME"]];
							$arReg["STRICT"] = true;
						}

						$rsCt = CAltasibGeoBase::GetCitiesIPGB($arReg);
						if($rsCt && !is_null($rsCt) && $arCts = $rsCt->Fetch())
						{
							if(!empty($arTwns) && !empty($arCts["BREADTH_CITY"]) && !empty($arCts["LONGITUDE_CITY"]))
								$nearId = self::GetNearFromArr($arTwns, $arCts["BREADTH_CITY"], $arCts["LONGITUDE_CITY"]);
						}
					}
				}
			}

			if($searchMode == "region" || $searchMode == "all")
			{
				// Search by region USER_CHOICE
				if($nearId === false)
				{
					if(!empty($arCities) && count($arCities) > 0 && !empty($curRegID))
					{
						foreach($arCities as $i=>$c)
						{
							if(!empty($c["R_ID"]))
							{
								if($curRegID == $c["R_ID"])
								{
									$nearId = $i;
									break;
								}
							}
						}
					}
				}
			}
		}

		if($searchMode == "geo" || $searchMode == "all")
		{
			// Search by coordinates AUTO_DETECT
			if($nearId === false)
			{
				if(!empty($arTwns) && !empty($bread) && !empty($long))
				{
					$nearId = self::GetNearFromArr($arTwns, $bread, $long);
				}
			}
		}

		if($searchMode == "region" || $searchMode == "all")
		{
			// Search by region AUTO_DETECT
			if($nearId === false)
			{
				if(!empty($arTwns) && count($arTwns) > 0 && !empty($curReg))
				{
					$curRegLw = ToLower($curReg);

					foreach($arTwns as $k=>$w)
					{
						if(!empty($w["REGION_NAME"]))
						{
							if($curRegLw == ToLower($w["REGION_NAME"]))
							{
								$nearId = $k;
								break;
							}
						}
					}
				}
			}
		}

		if(!empty($arCities[$nearId]))
		{
			if(!empty($arTwns[$nearId]))
			{
				$arCities[$nearId]["COUNTY_NAME"] = $arTwns[$nearId]["COUNTY_NAME"];
				$arCities[$nearId]["BREADTH_CITY"] = $arTwns[$nearId]["BREADTH_CITY"];
				$arCities[$nearId]["LONGITUDE_CITY"] = $arTwns[$nearId]["LONGITUDE_CITY"];
			}
			return $arCities[$nearId];
		}
		else
			return false;

	}

	function GetNearFromArr($arTowns, $bread, $long)
	{
		$id = false;
		$dst = 0;
		if(!empty($arTowns) && count($arTowns) > 0)
		{
			foreach($arTowns as $k=>$w)
			{
				if(!empty($w["BREADTH_CITY"]) && !empty($w["LONGITUDE_CITY"]))
				{
					$cDst = sqrt(pow($w["BREADTH_CITY"]-$bread, 2) + pow($w["LONGITUDE_CITY"]-$long,2));
					if(($dst && $cDst <= $dst) || (!$dst && $id===false))
					{
						$dst = $cDst;
						$id = $k;
					}
				}
			}
		}
		return $id;
	}

	function GetRegionAdmCenter($regionCode)
	{
		if(empty($regionCode))
			return;

		$arAdm = array();
		$arRn = CAltasibGeoBase::GetRegionByCode($regionCode)->Fetch();
		if($arRn)
		{
			global $DB;

			if($arRn["R_CODE"] == 47) // Leningrad obl
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_SPB");
				$arAdm["CODE"] = "77";
				$arAdm["REGION_CODE"] = "77";
				$arAdm["REGION_NAME"] = GetMessage("ALTASIB_SPB");
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 50) // Mosqow obl
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_MSK");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["REGION_CODE"] = $arRn["R_CODE"];
				$arAdm["REGION_NAME"] = GetMessage("ALTASIB_MSK");
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 77) // Mosqow
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_MSK");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 78) // Saint Petersburg
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_SPB");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 91) // Krym
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_SMFP");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["REGION_CODE"] = $arRn["R_CODE"];
				$arAdm["REGION_NAME"] = GetMessage("ALTASIB_KRM");
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 92) // Sevastopol
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_SVTP");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			elseif($arRn["R_CODE"] == 99) // Baykonur
			{
				$arAdm["NAME"] = GetMessage("ALTASIB_BCNR");
				$arAdm["CODE"] = $arRn["R_CODE"];
				$arAdm["REGION_CODE"] = $arRn["R_CODE"];
				$arAdm["SOCR"] = GetMessage("ALTASIB_G");
			}
			else
			{
				if($DB->TableExists('altasib_geobase_kladr_cities'))
				{
					$rsData = $DB->Query('SELECT * '
						.'FROM altasib_geobase_kladr_cities '
						.'WHERE (LOWER(CODE) LIKE "'.$arRn["R_CODE"].'000001000" OR LOWER(ID_DISTRICT) LIKE "'.$arRn["R_CODE"].'000")'
						.' AND (STATUS = "2" OR STATUS = "3" OR SORTINDEX > 60) ORDER BY STATUS DESC, SORTINDEX DESC, ID ASC LIMIT 1'
					);
					$arAdm = $rsData->Fetch();
				}
			}

			if(empty($arAdm["REGION_CODE"]))
			{
				if($arRn["R_FNAME"])
					$arAdm["REGION_FULL_NAME"] = $arRn["R_FNAME"];
				if($arRn["R_NAME"])
					$arAdm["REGION_NAME"] = $arRn["R_NAME"];
				if($arRn["R_SOCR"])
					$arAdm["REGION_SOCR"] = $arRn["R_SOCR"];
				if($arRn["R_CODE"])
					$arAdm["REGION_CODE"] = $arRn["R_CODE"];
				if($arRn["R_PINDEX"])
					$arAdm["REGION_POSTINDEX"] = $arRn["R_PINDEX"];
			}
		}

		return $arAdm;
	}

	function GetArReplace($bInvert = false)
	{
		$arRepl = array();
		if($bInvert)
		{
			for($i=0, $strm = 'ALTASIB_R_'; $i<12; $i++)
				$arRepl[GetMessage($strm.$i."_V")] = GetMessage($strm.$i);
		}
		else
		{
			for($i=0, $strm = 'ALTASIB_R_'; $i<12; $i++)
				$arRepl[GetMessage($strm.$i)] = GetMessage($strm.$i."_V");
		}
		return $arRepl;
	}

	function GetArReplaceLocations($bInvert = false)
	{
		$arRpl = array();
		if($bInvert)
		{
			for($i=0, $sm = 'ALTASIB_RL_'; $i<5; $i++)
				$arRpl[GetMessage($sm.$i."_V")] = GetMessage($sm.$i);
		}
		else
		{
			for($i=0, $sm = 'ALTASIB_RL_'; $i<5; $i++)
				$arRpl[GetMessage($sm.$i)] = GetMessage($sm.$i."_V");
		}
		return $arRpl;
	}

	function GetUFValue($valueID, $fieldID = false)
	{
		global $USER_FIELD_MANAGER;

		if($fieldID)
			return $USER_FIELD_MANAGER->GetUserFieldValue(self::UF_OBJECT_ENTITY_ID, $fieldID, $valueID, false);
		else
		{	// URL as default
			$sURL = $USER_FIELD_MANAGER->GetUserFieldValue(self::UF_OBJECT_ENTITY_ID, self::UF_FIELD_URL, $valueID, false);
			if(empty($sURL))
			{
				$arUFs = $USER_FIELD_MANAGER->GetUserFields(self::UF_OBJECT_ENTITY_ID, $valueID, false);
				if(!empty($arUFs[self::UF_FIELD_URL]["SETTINGS"]["DEFAULT_VALUE"]))
					$sURL = $arUFs[self::UF_FIELD_URL]["SETTINGS"]["DEFAULT_VALUE"];
			}
			return $sURL;
		}
	}
}
