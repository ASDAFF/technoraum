<?php

use \Bitrix\Main\Loader,
	\Bitrix\Main\Context,
	\Bitrix\Main\EventResult;

Loader::includeModule('sale');
Loader::registerAutoLoadClasses('modulpos.cashboxv4', array(
	'ModuleCashboxCustomV3' => 'classes/modulecashbox.php',
));

if (CashboxVersionControl::checkCashboxVersionIsV4()) {
	
	Loader::registerAutoLoadClasses('modulpos.cashboxv4', array(
		'ModuleCashboxCustomV4' => 'classes/modulecashbox_v4.php',
	));
	
}

class CashboxHandlers {
	
	public static function AddModuleOnAjaxSettings() {
		
		$request = Context::getCurrent()->getRequest()->getRequestedPage();

		if ($request == '/bitrix/admin/sale_cashbox_ajax.php') {
			
			Loader::includeModule('modulpos.cashboxv4');
		
		}
		
	}
	
	public static function AddModuleCashboxHandlerV3()
	{
		
		return new EventResult(
		   EventResult::SUCCESS,
		   array(
			   'ModuleCashboxCustomV3' => '/bitrix/modules/modulpos.cashboxv4/classes/modulecashbox.php',
		   )
	    );
	}
	
	public static function AddModuleCashboxHandlerV4()
	{
		if (CashboxVersionControl::checkCashboxVersionIsV4()) {
			
			return new EventResult(
			   EventResult::SUCCESS,
			   array(
				   'ModuleCashboxCustomV4' => '/bitrix/modules/modulpos.cashboxv4/classes/modulecashbox_v4.php',
			   )
		    );
		   
		}
	}
	
}

class CashboxVersionControl {
	
	public static function checkCashboxVersionIsV4()
	{
		$version = (class_exists('\Bitrix\Sale\Cashbox\CashboxAtolFarmV4')) ? true : false;
		
		return $version;
	}
		
}