<?php

return array(
	array(
		'module'   => 'sale',
		'name'     => 'OnGetCustomCashboxHandlers',
		'callback' => array('CashboxHandlers', 'AddModuleCashboxHandlerV3'),
		'sort'     => 100,
		'path'     => '',
		'args'     => array(),
	),
	array(
		'module'   => 'sale',
		'name'     => 'OnGetCustomCashboxHandlers',
		'callback' => array('CashboxHandlers', 'AddModuleCashboxHandlerV4'),
		'sort'     => 100,
		'path'     => '',
		'args'     => array(),
	),
	array(
		'module'   => 'main',
		'name'     => 'OnProlog',
		'callback' => array('CashboxHandlers', 'AddModuleOnAjaxSettings'),
		'sort'     => 100,
		'path'     => '',
		'args'     => array(),
	)
);