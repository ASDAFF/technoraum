<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$buttons = \Bitrix\Landing\Hook\Page\B24button::getButtons();
$buttons = array_keys($buttons);

return array(
	'name' => Loc::getMessage('LANDING_DEMO_EASTER2_TITLE'),
	'description' => Loc::getMessage('LANDING_DEMO_EASTER2_DESCRIPTION'),
	'fields' => array(
		'ADDITIONAL_FIELDS' => array(
			'THEME_CODE' => 'spa',
			'THEME_CODE_TYPO' => 'spa',
			'B24BUTTON_CODE' => $buttons[0],
			'UP_SHOW' => 'Y',
		)
	),
	'items' => array (
	),
	'sort' => \LandingSiteDemoComponent::checkActivePeriod(3,25,5,6) ? 61 : -151,
	'available' => true,
	'active' => \LandingSiteDemoComponent::checkActive(array(
		'ONLY_IN' => array('ru', 'by', 'kz', 'ua'),
		'EXCEPT' => array()
	))
);