<?php
IncludeModuleLangFile(__FILE__);

class modulpos_cashboxv4 extends CModule
{
	var $MODULE_ID = 'modulpos.cashboxv4';

	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;

	var $PARTNER_NAME = 'modulpos';
	var $PARTNER_URI = 'https://modulbank.ru/';

	/**
	 * Конструктор класса
	 */
	public function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__ .'/version.php');

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

		$this->MODULE_NAME = GetMessage($this->MODULE_ID .'_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage($this->MODULE_ID .'_MODULE_DESCRIPTION');

		$this->PARTNER_NAME = 'modulpos';
		$this->PARTNER_URI = 'https://modulbank.ru/';
	}

	/**
	 * Подписка на события системы модулем
	 */
	public function InstallEvents()
	{
		foreach($this->getEvents() as $arEvent) {

			\Bitrix\Main\EventManager::getInstance()->registerEventHandler(
				$arEvent['module'],
				$arEvent['name'],
				$this->MODULE_ID,
				$arEvent['callback'] ? $arEvent['callback'][0] : '',
				$arEvent['callback'] ? $arEvent['callback'][1] : '',
				$arEvent['sort']     ?: 100,
				$arEvent['path']     ?: '',
				$arEvent['args']     ?: array()
			);
		}

		return true;
	}
	
	/**
	 * Удаление подписчиков модуля на события системы
	 */
	public function UnInstallEvents()
	{
		foreach ($this->getEvents() as $arEvent) {
			\Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler(
				$arEvent['module'],
				$arEvent['name'],
				$this->MODULE_ID,
				$arEvent['callback'] ? $arEvent['callback'][0] : '',
				$arEvent['callback'] ? $arEvent['callback'][1] : '',
				$arEvent['path']     ?: '',
				$arEvent['args']     ?: array()
			);
		}

		return true;
	}
	
	/**
	 * Процесс установки модуля
	 */
	public function DoInstall()
	{
	
		if (($error = $this->checkDependences()) !== true) {
			
			$GLOBALS['modulpos.cashboxv4_INSTALL_ERROR'] = $error;
			$GLOBALS['APPLICATION']->IncludeAdminFile(GetMessage($this->MODULE_ID . '_INSTALL_ERROR_TITLE'), __DIR__ .'/error.php');

			return;
		}
	
		RegisterModule($this->MODULE_ID);
		
		$this->InstallEvents();

	}

	/**
	 * Процесс удаления модуля
	 */
	public function DoUninstall()
	{
		global $DOCUMENT_ROOT, $APPLICATION, $step;
		
		$step = intval($step);
		if($step < 2) {
			
			$APPLICATION->IncludeAdminFile(GetMessage("modulpos.cashboxv4_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/modulpos.cashboxv4/install/unstep1.php");
			
		}
		elseif($step == 2) {
			
			if (\Bitrix\Main\Loader::includeModule('sale')) {
				
				if ($_SESSION["CASHBOX_DELETE_ID"]) {
					
					foreach ($_SESSION["CASHBOX_DELETE_ID"] as $cashboxID)
						$cashboxDeleteResult = \Bitrix\Sale\Cashbox\Internals\CashboxTable::delete($cashboxID);
					
				}
				
				$cacheManager = \Bitrix\Main\Application::getInstance()->getManagedCache();
				$cacheManager->clean(\Bitrix\Sale\Cashbox\Manager::CACHE_ID);
				
			}
			
			unset($_SESSION["CASHBOX_DELETE_ID"]);
			
			$this->UnInstallEvents();
			UnRegisterModule($this->MODULE_ID);
			$APPLICATION->IncludeAdminFile(GetMessage("modulpos.cashboxv4_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/modulpos.cashboxv4/install/unstep2.php");
			
		}
		
	}

	/**
	 * Загружает список событий модуля из файлов
	 * 
	 * @return array
	 */
	protected function getEvents($version = 0)
	{
		$events = array();
		foreach (glob(__DIR__ .'/events/*.php') as $file) {
			$events = array_merge($events, include($file));
		}

		return $events;
	}

	/**
	 * Проверяет зависимости модуля
	 * 
	 * @return string|true
	 */
	protected function checkDependences()
	{
		if (!CModule::IncludeModule('sale') || !class_exists('\Bitrix\Sale\Cashbox\CashboxAtolFarm')) {
			return GetMessage($this->MODULE_ID .'_INSTALL_ERROR_SALE_NOT_VERSION');
		}

		return true;
	}
	
}