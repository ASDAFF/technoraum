<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class MainMailConfirmComponent extends CBitrixComponent
{

	public function executeComponent()
	{
		global $USER;

		if (!is_object($USER) || !$USER->isAuthorized())
			return array();

		if (!empty($this->arParams['CONFIRM_CODE']))
		{
			$this->includeComponentTemplate('confirm_code');
			return;
		}

		$this->arParams['USER_FULL_NAME'] = static::getUserNameFormated();
		$this->arParams['MAILBOXES'] = static::prepareMailboxes();

		$this->arParams['IS_SMTP_AVAILABLE'] = Main\ModuleManager::isModuleInstalled('bitrix24');
		$this->arParams['IS_ADMIN'] = Main\Loader::includeModule('bitrix24')
			? \CBitrix24::isPortalAdmin($USER->getId())
			: $USER->isAdmin();

		$this->includeComponentTemplate();

		return $this->arParams['MAILBOXES'];
	}

	public static function prepareMailboxes()
	{
		global $USER;

		static $mailboxes;

		if (!is_null($mailboxes))
			return $mailboxes;

		$mailboxes = array();

		if (!is_object($USER) || !$USER->isAuthorized())
			return $mailboxes;

		if (\CModule::includeModule('mail'))
		{
			foreach (\Bitrix\Mail\MailboxTable::getUserMailboxes() as $mailbox)
			{
				if (!empty($mailbox['EMAIL']))
				{
					$mailboxName = trim($mailbox['OPTIONS']['name']) ?: static::getUserNameFormated();

					$key = hash('crc32b', strtolower($mailboxName) . $mailbox['EMAIL']);
					$mailboxes[$key] = array(
						'name'  => $mailboxName,
						'email' => $mailbox['EMAIL'],
					);
				}
			}
		}

		// @TODO: query
		$crmEmail = static::extractEmail(\COption::getOptionString('crm', 'mail', ''));
		if (check_email($crmEmail, true))
		{
			$crmEmail = strtolower($crmEmail);
			$key = hash('crc32b', strtolower(static::getUserNameFormated()) . $crmEmail);

			$mailboxes[$key] = array(
				'name'  => static::getUserNameFormated(),
				'email' => $crmEmail,
			);
		}

		$userEmail = $USER->getEmail();
		if (check_email($userEmail, true))
		{
			$userEmail = strtolower($userEmail);
			$key = hash('crc32b', strtolower(static::getUserNameFormated()) . $userEmail);

			$mailboxes[$key] = array(
				'name'  => static::getUserNameFormated(),
				'email' => $userEmail,
			);
		}

		$res = \Bitrix\Main\Mail\Internal\SenderTable::getList(array(
			'filter' => array(
				'IS_CONFIRMED' => true,
				array(
					'LOGIC' => 'OR',
					'=USER_ID' => $USER->getId(),
					'IS_PUBLIC' => true,
				),
			),
			'order' => array(
				'ID' => 'ASC',
			),
		));
		while ($item = $res->fetch())
		{
			$item['NAME']  = trim($item['NAME']) ?: static::getUserNameFormated();
			$item['EMAIL'] = strtolower($item['EMAIL']);
			$key = hash('crc32b', strtolower($item['NAME']) . $item['EMAIL']);

			if(!isset($mailboxes[$key]))
			{
				$mailboxes[$key] = array(
					'id'    => $item['ID'],
					'name'  => $item['NAME'],
					'email' => $item['EMAIL'],
				);
			}
		}

		$mailboxes = array_values($mailboxes);

		foreach ($mailboxes as $k => $item)
		{
			$mailboxes[$k]['formated'] = sprintf(
				$item['name'] ? '%s <%s>' : '%s%s',
				$item['name'], $item['email']
			);
		}

		return $mailboxes;
	}

	public static function prepareMailboxesFormated()
	{
		static $mailboxesFormated;

		if (!is_null($mailboxesFormated))
			return $mailboxesFormated;

		$mailboxesFormated = array();

		foreach (static::prepareMailboxes() as $item)
			$mailboxesFormated[] = $item['formated'];

		return $mailboxesFormated;
	}

	protected static function getUserNameFormated()
	{
		global $USER;

		static $userNameFormated;

		if (!is_null($userNameFormated))
			return $userNameFormated;

		$userNameFormated = '';

		if (!is_object($USER) || !$USER->isAuthorized())
			return $userNameFormated;

		$userNameFormated = \CUser::formatName(
			\CSite::getNameFormat(),
			\Bitrix\Main\UserTable::getList(array(
				'select' => array('ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'LOGIN', 'PERSONAL_PHOTO'),
				'filter' => array('=ID' => $USER->getId()),
			))->fetch(),
			true, false
		);

		return $userNameFormated;
	}

	protected static function extractEmail($email)
	{
		$email = trim($email);
		if (preg_match('/.*?[<\[\(](.+?)[>\]\)].*/i', $email, $matches))
			$email = $matches[1];

		return $email;
	}

}
