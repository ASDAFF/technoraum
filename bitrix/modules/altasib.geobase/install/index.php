<?
/**
 * Company developer: ALTASIB
 * Developer: adumnov
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */

global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall) - strlen("/index.php"));
IncludeModuleLangFile(__FILE__);

Class altasib_geobase extends CModule{

	var $MODULE_ID = "altasib.geobase";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;


	function altasib_geobase() {
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		} else {
			$this->MODULE_VERSION = "1.0.0";
			$this->MODULE_VERSION_DATE = "2014-08-08 12:50:00";
		}

		$this->MODULE_NAME = GetMessage("ALTASIB_GEOBASE_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("ALTASIB_GEOBASE_MODULE_DESCRIPTION");

		$this->PARTNER_NAME = "ALTASIB";
		$this->PARTNER_URI = "http://www.altasib.ru/";
	}

	function DoInstall(){
		global $DB, $APPLICATION, $step;

		$step = IntVal($step);
		if ($step == 2){
			$this->InstallFiles();
			if ($_REQUEST["LOAD_DATA"] != "Y"){
				if ($_REQUEST["LOAD_DATA_MM"] == "Y")
					$step = 3;
				else
					$step = 4;
			}
		}
		if ($step == 3){
			if($_REQUEST["LOAD_DATA_MM"] != "Y")
				$step = 4;
		}

		if ($step < 2) {
			$GLOBALS["install_step"] = 1;
			$APPLICATION->IncludeAdminFile(
				GetMessage("ALTASIB_GEOBASE_INSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/step1.php"
			);
		} elseif ($step == 2) { // ipgeobase
			$GLOBALS["errors"]			= $this->errors;
			$GLOBALS["install_step"]	= 2;
			$APPLICATION->IncludeAdminFile(
				GetMessage("ALTASIB_GEOBASE_INSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/step2.php");
		} elseif ($step == 3) { // maxmind
			$GLOBALS["errors"]			= $this->errors;
			$GLOBALS["install_step"]	= 3;
			$APPLICATION->IncludeAdminFile(
				GetMessage("ALTASIB_GEOBASE_INSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/step3.php");
		} elseif ($step == 4) { // kladr
			$GLOBALS["errors"]			= $this->errors;
			$GLOBALS["install_step"]	= 4;
			$APPLICATION->IncludeAdminFile(
				GetMessage("ALTASIB_GEOBASE_INSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/step4.php");
		} elseif ($step == 5) { // end
			if ($this->InstallDB()) {
				$GLOBALS["errors"]		 = $this->errors;
				$GLOBALS["install_step"] = 5;
				$APPLICATION->IncludeAdminFile(
					GetMessage("ALTASIB_GEOBASE_INSTALL_TITLE"),
					$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/step5.php"
				);
			}
		}
	}

	function DoUninstall(){
		global $DB, $APPLICATION, $step;
		$step = IntVal($step);

		if ($step < 2) {
			$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_GEOBASE_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/unstep1.php");
		} elseif ($step == 2) {
			$this->UnInstallDB(array(
				"savedata" => $_REQUEST["savedata"],
			));
			$this->UnInstallFiles();
			$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_GEOBASE_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/unstep2.php");
		}
	}

	function InstallDB(){
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;

		if($this->errors !== false){
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		RegisterModule("altasib.geobase");
		RegisterModuleDependences("main", "OnBeforeEndBufferContent", "altasib.geobase", "CAltasibGeoBase", "UPOnBeforeEndBufferContent", "100");
		RegisterModuleDependences("main", "OnProlog", "altasib.geobase", "CAltasibGeoBase", "OnPrologHandler", "100");
		RegisterModuleDependences("main", "OnBeforeUserAdd", "altasib.geobase", "CAltasibGeoBase", "OnBeforeUserAddHandler", "100");

		RegisterModuleDependences("statistic", "OnCityLookup", "altasib.geobase", "CAltasibGeoBaseCityLookup", "OnCityLookup", "100");

		if ($_REQUEST['GET_UPDATE'] == "Y" || $_REQUEST['GET_UPDATE'] == "N"){
			COption::SetOptionString("altasib.geobase", "get_update", $_REQUEST['GET_UPDATE']);
		}
		if ($_REQUEST['MM_GET_UPDATE'] == "Y" || $_REQUEST['MM_GET_UPDATE'] == "N"){
			COption::SetOptionString("altasib.geobase", "mm_get_update", $_REQUEST['MM_GET_UPDATE']);
		}

		$res = $this->InstallUserFields();
		if ($res)
		{
			$this->errors[] = $res;
		}

		return true;
	}

	function UnInstallDB($arParams = array()){
		global $DB, $DBType, $APPLICATION;

		$this->errors = false;
		$arSQLErrors = array();

		if(is_array($this->errors))
			$arSQLErrors = array_merge($arSQLErrors, $this->errors);

		if(!empty($arSQLErrors))
		{
			$this->errors = $arSQLErrors;
			$APPLICATION->ThrowException(implode("", $arSQLErrors));
			return false;
		}

		if(array_key_exists("savedata", $arParams) && $arParams["savedata"] != "Y")
		{
			$this->UnInstallUserFields();
		}

		if (!$arParams['savedata']){
			if ($DB->TableExists('altasib_geobase_codeip'))
				$DB->Query("DROP TABLE `altasib_geobase_codeip`");
			if ($DB->TableExists('altasib_geobase_cities'))
				$DB->Query("DROP TABLE `altasib_geobase_cities`");

			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/altasib.geobase/install/db/".$DBType."/uninstall.sql");
		}
		COption::RemoveOption("altasib.geobase");
		UnRegisterModuleDependences("main", "OnBeforeEndBufferContent", "altasib.geobase", "CAltasibGeoBase", "UPOnBeforeEndBufferContent");
		UnRegisterModuleDependences("main", "OnProlog", "altasib.geobase", "CAltasibGeoBase", "OnPrologHandler");
		UnRegisterModuleDependences("main", "OnBeforeUserAdd", "altasib.geobase", "CAltasibGeoBase", "OnBeforeUserAddHandler");
		UnRegisterModuleDependences("statistic", "OnCityLookup", "altasib.geobase", "CAltasibGeoBaseCityLookup", "OnCityLookup");
		UnRegisterModule("altasib.geobase");

		return true;
	}

	function InstallFiles(){
		$this->errors = false;
		$arSQLErrors = array();

		if (!is_dir($_SERVER["DOCUMENT_ROOT"] . "/upload/altasib/geobase/"))
		{
			if(!defined("BX_DIR_PERMISSIONS"))
				mkdir($_SERVER["DOCUMENT_ROOT"] . "/upload/altasib/geobase/", 0755, true);
			else
				mkdir($_SERVER["DOCUMENT_ROOT"] . "/upload/altasib/geobase/", BX_DIR_PERMISSIONS, true);
		}
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/tools", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tools/altasib.geobase", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/js", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/altasib/geobase", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/altasib.geobase/install/images", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/images/altasib.geobase", true, true);

		$arPackFrom = array(
			"/bitrix/modules/altasib.geobase/base/kladr_tables.zip",
			"/bitrix/modules/altasib.geobase/base/mm_base.zip"
		);
		$pack_to = "/upload/altasib/geobase/";

		if (class_exists('ZipArchive'))
		{
			$zip = new ZipArchive;
			foreach($arPackFrom as $archive){
				if ($zip->open($_SERVER['DOCUMENT_ROOT'].$archive) === TRUE){
					$zip->extractTo($_SERVER['DOCUMENT_ROOT'].$pack_to);
					$zip->close();
				}
			}
		}
		else
		{
			global $USER;
			$bReplaceFiles = false;
			$arOption = array(
				"REMOVE_PATH"		=> $_SERVER["DOCUMENT_ROOT"],
				"UNPACK_REPLACE"	=> $bReplaceFiles,
				"CHECK_PERMISSIONS" => $USER->IsAdmin() ? false : true
			);
			foreach($arPackFrom as $archive){
				$arc = CBXArchive::GetArchive($_SERVER["DOCUMENT_ROOT"].$archive);
				if ($arc instanceof IBXArchive)
				{
					$arc->SetOptions($arOption);
					$uRes = $arc->Unpack($_SERVER["DOCUMENT_ROOT"].$pack_to);
					if (!$uRes)
						$this->errors = $arc->GetErrors();
				}
			}
		}

		$arr = getdate();
		$nDate = mktime(0, 1, 0, $arr["mon"], $arr["mday"], $arr["year"]);
		CAgent::AddAgent("CAltasibGeoBase::SetUpdateAgent();", "altasib.geobase", "Y", 86400, "", "Y", ConvertTimeStamp($nDate + CTimeZone::GetOffset(), "FULL"), 200);

		if(is_array($this->errors))
			$arSQLErrors = array_merge($arSQLErrors, $this->errors);

		if(!empty($arSQLErrors)){
			$this->errors = $arSQLErrors;
			$APPLICATION->ThrowException(implode("", $arSQLErrors));
			return false;
		}
		return true;
	}

	function UnInstallFiles(){
		DeleteDirFilesEx("/bitrix/components/altasib/geobase");
		DeleteDirFilesEx("/bitrix/components/altasib/geobase.select.city");
		DeleteDirFilesEx("/bitrix/components/altasib/geobase.your.city");
		DeleteDirFilesEx("/bitrix/tools/altasib.geobase");
		DeleteDirFilesEx("/bitrix/js/altasib/geobase");
		DeleteDirFilesEx("/upload/altasib/geobase");
		DeleteDirFilesEx("/bitrix/images/altasib.geobase");
		CAgent::RemoveAgent("CAltasibGeoBase::SetUpdateAgent();", "altasib.geobase");
		@unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/altasib_geobase_import_db.php");
		@unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/altasib_geobase_selected.php");
		@unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/altasib_geobase_update.php");
		@unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/altasib_geobase_file_check.php");
		return true;
	}

	function InstallUserFields()
	{
		global $APPLICATION, $USER_FIELD_MANAGER;
		$errors = null;

		$USER_FIELD_MANAGER->CleanCache();

		$arFields = array();

		$oUserTypeEntity = new CUserTypeEntity();

		$arLang = array(
			'ru'	=> GetMessage("INSTALL_GB_PHONE_RU"),
			'en'	=> GetMessage("INSTALL_GB_PHONE_EN"),
		);
		$arFields[] = array(
			'ENTITY_ID'		=> 'ALTASIB_GEOBASE',
			'FIELD_NAME'	=> 'UF_ALX_GB_PHONE',
			'USER_TYPE_ID'	=> 'string',
			'XML_ID'		=> 'XML_ID_ALX_GB_PHONE',
			'SORT'			=> 100,
			'MULTIPLE'		=> 'Y',
			'MANDATORY'		=> 'N',
			'SHOW_FILTER'	=> 'N',
			'SHOW_IN_LIST'	=> '',
			'EDIT_IN_LIST'	=> '',
			'IS_SEARCHABLE'	=> 'N',
			'SETTINGS'		=> array(
				'DEFAULT_VALUE' => '',
				'SIZE'			=> '20',
				'ROWS'			=> '1',
				'MIN_LENGTH'	=> '0',
				'MAX_LENGTH'	=> '0',
				'REGEXP'		=> '',
			),
			'EDIT_FORM_LABEL'	=> $arLang,
			'LIST_COLUMN_LABEL' => $arLang,
			'LIST_FILTER_LABEL' => $arLang,
			'ERROR_MESSAGE'	 => array(
				'ru'	=> GetMessage("INSTALL_GEOBASE_ERROR_RU", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_PHONE_RU"))),
				'en'	=> GetMessage("INSTALL_GEOBASE_ERROR_EN", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_PHONE_EN"))),
			),
		);

		$arLang = array(
			'ru'	=> GetMessage("INSTALL_GB_URL_RU"),
			'en'	=> GetMessage("INSTALL_GB_URL_EN"),
		);
		$arFields[] = array(
			'ENTITY_ID'		=> 'ALTASIB_GEOBASE',
			'FIELD_NAME'	=> 'UF_ALX_GB_URL',
			'USER_TYPE_ID'	=> 'string',
			'XML_ID'		=> 'XML_ID_ALX_GB_URL',
			'SORT'			=> 110,
			'MULTIPLE'		=> 'N',
			'MANDATORY'		=> 'N',
			'SHOW_FILTER'	=> 'N',
			'SHOW_IN_LIST'	=> '',
			'EDIT_IN_LIST'	=> '',
			'IS_SEARCHABLE'	=> 'N',
			'SETTINGS'		=> array(
				'DEFAULT_VALUE' => '',
				'SIZE'			=> '50',
				'ROWS'			=> '1',
				'MIN_LENGTH'	=> '0',
				'MAX_LENGTH'	=> '0',
				'REGEXP'		=> '',
			),
			'EDIT_FORM_LABEL'	=> $arLang,
			'LIST_COLUMN_LABEL' => $arLang,
			'LIST_FILTER_LABEL' => $arLang,
			'ERROR_MESSAGE'	 => array(
				'ru'	=> GetMessage("INSTALL_GEOBASE_ERROR_RU", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_URL_RU"))),
				'en'	=> GetMessage("INSTALL_GEOBASE_ERROR_EN", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_URL_EN"))),
			),
			'HELP_MESSAGE'	 => array(
				'ru'	=> GetMessage("INSTALL_GB_URL_TIP_RU"),
				'en'	=> GetMessage("INSTALL_GB_URL_TIP_EN"),
			),
		);

		$arLang = array(
			'ru'	=> GetMessage("INSTALL_GB_INFO_RU"),
			'en'	=> GetMessage("INSTALL_GB_INFO_EN"),
		);
		$arFields[] = array(
			'ENTITY_ID'		=> 'ALTASIB_GEOBASE',
			'FIELD_NAME'	=> 'UF_ALX_GB_INFO',
			'USER_TYPE_ID'	=> 'string',
			'XML_ID'		=> 'XML_ID_ALX_GB_INFO',
			'SORT'			=> 120,
			'MULTIPLE'		=> 'N',
			'MANDATORY'		=> 'N',
			'SHOW_FILTER'	=> 'N',
			'SHOW_IN_LIST'	=> '',
			'EDIT_IN_LIST'	=> '',
			'IS_SEARCHABLE'	=> 'N',
			'SETTINGS'		=> array(
				'DEFAULT_VALUE' => '',
				'SIZE'			=> '70',
				'ROWS'			=> '3',
				'MIN_LENGTH'	=> '0',
				'MAX_LENGTH'	=> '0',
				'REGEXP'		=> '',
			),
			'EDIT_FORM_LABEL'	=> $arLang,
			'LIST_COLUMN_LABEL' => $arLang,
			'LIST_FILTER_LABEL' => $arLang,
			'ERROR_MESSAGE'	 => array(
				'ru'	=> GetMessage("INSTALL_GEOBASE_ERROR_RU", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_INFO_RU"))),
				'en'	=> GetMessage("INSTALL_GEOBASE_ERROR_EN", Array ("#USER_FIELD#" => GetMessage("INSTALL_GB_INFO_EN"))),
			),
			'HELP_MESSAGE'	 => array(
				'ru'	=> GetMessage("INSTALL_GB_INFO_TIP_RU"),
				'en'	=> GetMessage("INSTALL_GB_INFO_TIP_EN"),
			),
		);

		$obUserField = new CUserTypeEntity;
		foreach ($arFields as $arField)
		{
			$rsData = CUserTypeEntity::GetList(array("ID" => "ASC"), $arField);
			if (!($rsData && ($arRes = $rsData->Fetch()) && !!$arRes))
			{
				$intID = $obUserField->Add($arField, false);
				if (false == $intID && ($strEx = $APPLICATION->GetException()))
				{
					$errors = $strEx->GetString();
				}
			}
		}

		return $errors;
	}

	function UnInstallUserFields()
	{
		$arFields = array(
			array(
				"ENTITY_ID" => "ALTASIB_GEOBASE",
				"FIELD_NAME" => "UF_ALX_GB_PHONE",
				"XML_ID" => "XML_ID_ALX_GB_PHONE"
			),
			array(
				"ENTITY_ID" => "ALTASIB_GEOBASE",
				"FIELD_NAME" => "UF_ALX_GB_URL",
				"XML_ID" => "XML_ID_ALX_GB_URL"
			),
			array(
				"ENTITY_ID" => "ALTASIB_GEOBASE",
				"FIELD_NAME" => "UF_ALX_GB_INFO",
				"XML_ID" => "XML_ID_ALX_GB_INFO"
			),
		);

		foreach ($arFields as $arField)
		{
			$rsData = CUserTypeEntity::GetList(array("ID" => "ASC"), $arField);
			if ($arRes = $rsData->Fetch())
			{
				$ent = new CUserTypeEntity;
				$ent->Delete($arRes['ID']);
			}
		}
		return true;
	}
}
