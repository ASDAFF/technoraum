<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-18);
@include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
IncludeModuleLangFile($strPath2Lang."/install/index.php");

Class directcredit_frame extends CModule
{
	const MODULE_ID = 'directcredit.frame';
	var $MODULE_ID = 'directcredit.frame'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("DC_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("DC_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("DC_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("DC_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;
		
		$this->errors = false;
        
		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}
		
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;
		
		if(array_key_exists("savedata", $arParams) && $arParams["savedata"] != "Y")
		{			
			if($this->errors !== false)
			{
				$APPLICATION->ThrowException(implode("", $this->errors));
				return false;
			}
		}

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.self::MODULE_ID.'/install/payment/', $_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/include/sale_payment/'.self::MODULE_ID.'/', true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.self::MODULE_ID.'/install/admin', $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/include/sale_payment/'.self::MODULE_ID.'/');
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
		$APPLICATION->IncludeAdminFile(GetMessage('INSTALL_MODULE'), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/".self::MODULE_ID."/install/step.php");
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>