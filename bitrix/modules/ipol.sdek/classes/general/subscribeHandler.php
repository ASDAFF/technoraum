<?
namespace Ipolh\SDEK;

IncludeModuleLangFile(__FILE__);

class subscribeHandler
{
    public static $link = true;
    private static $MODULE_ID  = IPOLH_SDEK;

    public static function getAjaxAction($action,$subaction){
        if(method_exists('sdekHelper',$action))
            \sdekHelper::$action($_POST);
        elseif(method_exists('sdekdriver',$action))
            \sdekdriver::$action($_POST);
        elseif(method_exists('CDeliverySDEK',$action))
            \CDeliverySDEK::$action($_POST);
        elseif(method_exists('sdekExport',$action))
            \sdekExport::$action($_POST);
        elseif(method_exists('sdekOption',$action))
            \sdekOption::$action($_POST);
        else{
            if(method_exists('sdekHelper',$subaction))
                \sdekHelper::$subaction($_POST);
            elseif(method_exists('CDeliverySDEK',$subaction))
                \CDeliverySDEK::$subaction($_POST);
        }
    }

    // RegisterModuleDependences

    protected static function getDependences($PVZ = false){

		$arDependences = array(
				// sending
			array("main", "OnEpilog", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onEpilog"),
            array("sale", "OnSaleComponentOrderOneStepComplete", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onOrderCreate"),
            array("sale", "OnSaleStatusOrder", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onStatusOrder"),
				// print
            array("main", "OnAdminListDisplay", self::$MODULE_ID, "Ipolh\\SDEK\subscribeHandler", "displayActPrint"),
            array("main", "OnBeforeProlog", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "OnBeforePrologHandler"),
				// widjet
            array("main", "OnEndBufferContent", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "OnEndBufferContent"),
            array("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "prepareWidjetData",900),
            array("sale", "OnSaleComponentOrderOneStepProcess",  self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "loadComponent",900),
            array("sale", "OnSaleComponentOrderShowAjaxAnswer", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onComponentAjaxAnswer"),
				// delivery
            array("sale", "OnSaleComponentOrderOneStepPaySystem", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "checkNalD2P"),
            array("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "checkNalP2D"),
        );

		if($PVZ){
			$arDependences []= array("sale", "OnSaleComponentOrderOneStepProcess", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "noPVZOldTemplate");
			$arDependences []= array("sale", "OnSaleOrderBeforeSaved", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "noPVZNewTemplate");
		}
		
		return $arDependences;
    }

    public static function register($PVZ = false){
        foreach (self::getDependences($PVZ) as $regArray){
            RegisterModuleDependences($regArray[0],$regArray[1],$regArray[2],$regArray[3],$regArray[4],(isset($regArray[5]) ? $regArray[5] : 100));
        }
    }

    public static function unRegister(){
        foreach(self::getDependences(true) as $regArray){
            UnRegisterModuleDependences($regArray[0],$regArray[1],$regArray[2],$regArray[3],$regArray[4]);
        }
    }

    // Events
		// loading export form
    public static function onEpilog(){
        \sdekdriver::onEpilog();
    }
		// loading widjet data
    public static function OnEndBufferContent(&$content){
        \CDeliverySDEK::onBufferContent($content);
    }
		// prepare data for component
    public static function prepareWidjetData($arResult,$arUserResult){
        \CDeliverySDEK::pickupLoader($arResult,$arUserResult);
    }
		// including component
    public static function loadComponent(){
        \CDeliverySDEK::loadComponent();
    }
		// adding properties to order & autoloads
    public static function onOrderCreate($oId,$arFields){
        \sdekdriver::orderCreate($oId,$arFields);
    }
		// autoloads via status
    public static function onStatusOrder($oId,$status){
        \sdekdriver::statusAutoLoad($oId,$status);
    }
		// checking paysystems while delivery => paysystems
    public static function checkNalD2P(&$arResult,&$arUserResult,$arParams){
        \CDeliverySDEK::checkNalD2P($arResult,$arUserResult,$arParams);
    }
		// checking paysystems while paysystems => delivery
    public static function checkNalP2D(&$arResult,$arUserResult,$arParams){
        \CDeliverySDEK::checkNalP2D($arResult,$arUserResult,$arParams);
    }
		// adding widjet data (new)
    public static function onComponentAjaxAnswer(&$result){
        \CDeliverySDEK::onAjaxAnswer($result);
    }
		// blocking order create if no PVZ
	public static function noPVZOldTemplate(&$arResult,&$arUserResult){
        \CDeliverySDEK::noPVZOldTemplate($arResult,$arUserResult);
    }
	public static function noPVZNewTemplate($entity,$values){
        return \CDeliverySDEK::noPVZNewTemplate($entity,$values);
    }
		// show print form
    public static function displayActPrint(&$list){
        \sdekOption::displayActPrint($list);
    }
		// show variants 4 mass print
    public static function OnBeforePrologHandler(){
        \sdekOption::OnBeforePrologHandler();
    }
	
	public static function consolidate(){
		if(\sdekHelper::isLogged()){
			UnRegisterModuleDependences("main", "OnEpilog", self::$MODULE_ID, "sdekdriver", "onEpilog");
			UnRegisterModuleDependences("main", "OnEndBufferContent", self::$MODULE_ID, "CDeliverySDEK", "onBufferContent");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "CDeliverySDEK", "pickupLoader");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepProcess", self::$MODULE_ID, "CDeliverySDEK", "loadComponent");
			UnRegisterModuleDependences("sale", "OnSaleOrderBeforeSaved", self::$MODULE_ID, "CDeliverySDEK", "noPVZNotConverted");
			UnRegisterModuleDependences("sale", "OnSaleOrderBeforeSaved", self::$MODULE_ID, "CDeliverySDEK", "noPVZConverted");
			UnRegisterModuleDependences("main", "OnAdminListDisplay", self::$MODULE_ID, "sdekOption", "displayActPrint");
			UnRegisterModuleDependences("main", "OnBeforeProlog", self::$MODULE_ID, "sdekOption", "OnBeforePrologHandler");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepComplete", self::$MODULE_ID, "sdekdriver", "orderCreate");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepPaySystem", self::$MODULE_ID, "CDeliverySDEK", "checkNalD2P");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "CDeliverySDEK", "checkNalP2D");
			UnRegisterModuleDependences("main", "OnBeforeProlog", self::$MODULE_ID, "CDeliverySDEK", "OnSaleComponentOrderShowAjaxAnswer");

			RegisterModuleDependences("main", "OnEpilog", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onEpilog");
			RegisterModuleDependences("main", "OnEndBufferContent", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "OnEndBufferContent");
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "prepareWidjetData",900);
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepProcess", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "loadComponent",900);
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepComplete", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onOrderCreate");
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepPaySystem", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "checkNalD2P");
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "checkNalP2D");
			RegisterModuleDependences("sale", "OnSaleComponentOrderShowAjaxAnswer", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "onComponentAjaxAnswer");
			RegisterModuleDependences("main", "OnAdminListDisplay", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "displayActPrint");
			RegisterModuleDependences("main", "OnBeforeProlog", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "OnBeforePrologHandler");
			if(\COption::GetOptionString(self::$MODULE_ID,'noPVZnoOrder','N') == 'Y'){
				RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepProcess", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "noPVZOldTemplate");
				if((\COption::GetOptionString("main","~sale_converted_15",'N') == 'Y')){
					RegisterModuleDependences("sale", "OnSaleOrderBeforeSaved", self::$MODULE_ID, "Ipolh\\SDEK\\subscribeHandler", "noPVZNewTemplate");
				}
			}
		}
	}
}