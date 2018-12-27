<?php
IncludeModuleLangFile(__FILE__);


class nbrains_slider extends CModule {

    var $MODULE_ID = 'nbrains.slider';

    function __construct(){

        $arModuleVersion = array();
        include(__DIR__.'/version.php');

        $this->MODULE_ID = 'nbrains.slider';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = GetMessage('THEBRAINSE_SLIDER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('THEBRAINSE_SLIDER_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('THEBRAINSE_SLIDER_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('THEBRAINSE_SLIDER_PARTNER_URL');

    }

    function DoInstall(){
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        $APPLICATION->IncludeAdminFile(GetMessage('THEBRAINSE_SLIDER_INSTALL'),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
    }

    function DoUninstall(){
        global $APPLICATION;
        UnRegisterModule($this->MODULE_ID);
        DeleteDirFilesEx("/bitrix/components/nbrains/slider");
        $APPLICATION->IncludeAdminFile(GetMessage('THEBRAINSE_SLIDER_UNINSTALL'),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
    }



}