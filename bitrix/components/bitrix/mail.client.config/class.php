<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Mail;
use Bitrix\Mail\Helper\LicenseManager;
use Bitrix\Mail\Helper\MessageFolder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__DIR__ . '/../mail.client/class.php');

\Bitrix\Main\Loader::includeModule('mail');

class CMailClientConfigComponent extends CBitrixComponent implements Main\Engine\Contract\Controllerable, Main\Errorable
{

	public function configureActions()
	{
		$this->errorCollection = new Main\ErrorCollection();

		return array();
	}

	public function executeComponent()
	{
		global $USER, $APPLICATION;

		if (!is_object($USER) || !$USER->isAuthorized())
		{
			$APPLICATION->authForm('');
			return;
		}

		switch ($this->arParams['VARIABLES']['act'])
		{
			case 'new':
				$this->editAction(true);
				break;
			case 'edit':
				$this->editAction(false);
				break;
			default:
				$this->defaultAction();
		}
	}

	protected function defaultAction()
	{
		global $APPLICATION;

		$APPLICATION->setTitle(Loc::getMessage('MAIL_CLIENT_CONFIG_TITLE'));

		if (!$this->canConnectNewMailbox())
		{
			showError(Loc::getMessage('MAIL_CLIENT_DENIED'));
			return;
		}

		$res = Mail\MailServicesTable::getList(array(
			'filter' => array(
				'=ACTIVE' => 'Y',
				'=SITE_ID' => SITE_ID,
			),
			'order' => array(
				'SORT' => 'ASC',
				'NAME' => 'ASC',
			),
		));

		$this->arParams['SERVICES'] = array();
		while ($service = $res->fetch())
		{
			$this->arParams['SERVICES'][$service['ID']] = array(
				'id'         => $service['ID'],
				'type'       => $service['SERVICE_TYPE'],
				'name'       => $service['NAME'],
				'link'       => $service['LINK'],
				'icon'       => Mail\MailServicesTable::getIconSrc($service['NAME'], $service['ICON']),
				'server'     => $service['SERVER'],
				'port'       => $service['PORT'],
				'encryption' => $service['ENCRYPTION'],
				'token'      => $service['TOKEN'],
				'flags'      => $service['FLAGS'],
				'sort'       => $service['SORT']
			);
		}

		$this->includeComponentTemplate();
	}

	protected function editAction($new = true)
	{
		global $APPLICATION, $USER;

		$APPLICATION->setTitle(Loc::getMessage($new ? 'MAIL_CLIENT_CONFIG_TITLE' : 'MAIL_CLIENT_CONFIG_EDIT_TITLE'));

		$this->arParams['IS_SMTP_AVAILABLE'] = \Bitrix\Main\Loader::includeModule('bitrix24');

		if ($new)
		{
			if (!$this->canConnectNewMailbox())
			{
				showError(Loc::getMessage('MAIL_CLIENT_DENIED'));
				return;
			}

			$serviceId = $_REQUEST['id'];
		}
		else
		{
			$mailbox = Mail\MailboxTable::getList(array(
				'filter' => array(
					'=ID' => $_REQUEST['id'],
					'=ACTIVE' => 'Y',
					'=USER_ID' => $USER->getId(),
					'=SERVER_TYPE' => 'imap',
				),
			))->fetch();

			if (empty($mailbox))
			{
				showError(Loc::getMessage('MAIL_CLIENT_ELEMENT_NOT_FOUND'));
				return;
			}

			if (empty($mailbox['OPTIONS']['imap']['dirs']) || !is_array($mailbox['OPTIONS']['imap']['dirs']))
			{
				$mailboxHelper = Mail\Helper\Mailbox::createInstance($mailbox['ID']);
				$mailboxHelper->cacheDirs();

				$mailbox['OPTIONS']['imap']['dirs'] = $mailboxHelper->getMailbox()['OPTIONS']['imap']['dirs'];
			}

			foreach (array($mailbox['EMAIL'], $mailbox['NAME'], $mailbox['LOGIN']) as $item)
			{
				$address = new \Bitrix\Main\Mail\Address($item);
				if ($address->validate())
				{
					$mailbox['EMAIL'] = $address->getEmail();
					break;
				}
			}

			if ($this->arParams['IS_SMTP_AVAILABLE'])
			{
				$res = Main\Mail\Internal\SenderTable::getList(array(
					'filter' => array(
						'IS_CONFIRMED' => true,
						'=EMAIL' => $mailbox['EMAIL'],
						//'=USER_ID' => $USER->getId(),
					),
					'order' => array(
						'ID' => 'DESC',
					),
				));
				while ($item = $res->fetch())
				{
					if (!empty($item['OPTIONS']['smtp']['server']) && empty($item['OPTIONS']['smtp']['encrypted']))
					{
						$mailbox['__smtp'] = $item['OPTIONS']['smtp'];
						break;
					}
				}
			}

			if (in_array('crm_connect', (array) $mailbox['OPTIONS']['flags']))
			{
				$mailbox['__crm'] = true;
			}

			$this->arParams['MAILBOX'] = $mailbox;

			$serviceId = $mailbox['SERVICE_ID'];
		}

		$res = Mail\MailServicesTable::getList(array(
			'filter' => array(
				'=ID' => $serviceId,
				'=ACTIVE' => 'Y',
				'=SITE_ID' => SITE_ID,
			),
		));

		$this->arParams['SERVICE'] = array();
		if ($service = $res->fetch())
		{
			$this->arParams['SERVICE'] = array(
				'id'         => $service['ID'],
				'type'       => $service['SERVICE_TYPE'],
				'name'       => $service['NAME'],
				'link'       => $service['LINK'],
				'icon'       => Mail\MailServicesTable::getIconSrc($service['NAME'], $service['ICON']),
				'server'     => $service['SERVER'],
				'port'       => $service['PORT'],
				'encryption' => $service['ENCRYPTION'],
			);
			$serviceSmtp = [];
			if(!empty($service['SMTP_SERVER']))
			{
				$serviceSmtp['server'] = $service['SMTP_SERVER'];
			}
			if(!empty($service['SMTP_PORT']))
			{
				$serviceSmtp['port'] = $service['SMTP_PORT'];
			}
			$serviceSmtp['login'] = ($service['SMTP_LOGIN_AS_IMAP'] === 'Y');
			$serviceSmtp['password'] = ($service['SMTP_PASSWORD_AS_IMAP'] === 'Y');
			$this->arParams['SERVICE']['smtp'] = $serviceSmtp;

		}
		else if ($new)
		{
			showError(Loc::getMessage('MAIL_CLIENT_ELEMENT_NOT_FOUND'));
			return;
		}

		if (!$new)
		{
			$this->arParams['SERVICE']['oauth'] = Mail\Helper\OAuth::getInstanceByMeta($mailbox['PASSWORD']);
			$this->arParams['SERVICE']['oauth_user'] = Mail\Helper\OAuth::getUserDataByMeta($mailbox['PASSWORD']);
		}

		if (empty($this->arParams['SERVICE']['oauth']))
		{
			if ($new || empty($mailbox['PASSWORD']))
			{
				$this->arParams['SERVICE']['oauth'] = Mail\MailServicesTable::getOAuthHelper($service);
			}
		}

		$access = array(
			'users' => array(),
		);

		if (!$new)
		{
			$res = Mail\Internals\MailboxAccessTable::getList(array(
				'filter' => array(
					'=MAILBOX_ID' => $mailbox['ID'],
					'TASK_ID' => 0,
				)
			));

			while ($item = $res->fetch())
			{
				if (preg_match('/^(U)(\d+)$/', $item['ACCESS_CODE'], $matches))
				{
					$access['users'][$item['ACCESS_CODE']] = $matches[2];
				}
			}

			if (!empty($access['users']))
			{
				$res = Main\UserTable::getList(array(
					'filter' => array(
						'@ID' => array_values($access['users']),
					),
				));

				while ($item = $res->fetch())
				{
					if ($USER->getId() == $item['ID'])
					{
						continue;
					}

					$id = sprintf('U%u', $item['ID']);
					$access['users'][$id] = array(
						'id'       => $id,
						'entityId' => $item['ID'],
						'name'     => \CUser::formatName(\CSite::getNameFormat(), $item, true),
						'avatar'   => '',
						'desc'     => $item['WORK_POSITION'] ?: $item['PERSONAL_PROFESSION'] ?: '&nbsp;'
					);
				}
			}

			$this->arParams['PASSWORD_PLACEHOLDER'] = '000000000000';
		}

		$this->arParams['ACCESS_LIST'] = array_map(
			function ($list)
			{
				return array_filter($list, 'is_array');
			},
			$access
		);

		if (\Bitrix\Main\Loader::includeModule('socialnetwork'))
		{
			$this->arParams['COMPANY_STRUCTURE'] = \CSocNetLogDestination::getStucture();
		}

		$this->arParams['CRM_AVAILABLE'] = false;
		if (Main\Loader::includeModule('crm') && \CCrmPerms::isAccessEnabled())
		{
			$this->arParams['CRM_AVAILABLE'] = $USER->isAdmin() || $USER->canDoOperation('bitrix24_config')
				|| \COption::getOptionString('intranet', 'allow_external_mail_crm', 'Y', SITE_ID) == 'Y';

			if ($this->arParams['CRM_AVAILABLE'])
			{
				$this->arParams['NEW_ENTITY_LIST'] = array(
					\CCrmOwnerType::LeadName    => \CCrmOwnerType::getDescription(\CCrmOwnerType::Lead),
					\CCrmOwnerType::ContactName => \CCrmOwnerType::getDescription(\CCrmOwnerType::Contact),
				);
				$this->arParams['DEFAULT_NEW_ENTITY_IN']  = \CCrmOwnerType::LeadName;
				$this->arParams['DEFAULT_NEW_ENTITY_OUT'] = \CCrmOwnerType::ContactName;

				$this->arParams['LEAD_SOURCE_LIST'] = \CCrmStatus::getStatusList('SOURCE');
				reset($this->arParams['LEAD_SOURCE_LIST']);
				$this->arParams['DEFAULT_LEAD_SOURCE'] = key($this->arParams['LEAD_SOURCE_LIST']);
				if (is_set($this->arParams['LEAD_SOURCE_LIST'], 'EMAIL'))
				{
					$this->arParams['DEFAULT_LEAD_SOURCE'] = 'EMAIL';
				}
				else if (is_set($this->arParams['LEAD_SOURCE_LIST'], 'OTHER'))
				{
					$this->arParams['DEFAULT_LEAD_SOURCE'] = 'OTHER';
				}

				if (!$new)
				{
					$options = $mailbox['OPTIONS'];

					if (!array_key_exists('flags', $options) || !is_array($options['flags']))
					{
						$options['flags'] = array();
					}

					if ($mailbox['__crm'])
					{
						// backward compatibility
						if (!array_intersect(array('crm_deny_new_lead', 'crm_deny_entity_in', 'crm_deny_entity_out'), $options['flags']))
						{
							$this->arParams['DEFAULT_NEW_ENTITY_IN'] = \CCrmOwnerType::LeadName;
							$this->arParams['DEFAULT_NEW_ENTITY_OUT'] = \CCrmOwnerType::LeadName;
						}
					}

					if (!empty($options['crm_new_entity_in']) && array_key_exists($options['crm_new_entity_in'], $this->arParams['NEW_ENTITY_LIST']))
					{
						$this->arParams['DEFAULT_NEW_ENTITY_IN'] = $options['crm_new_entity_in'];
					}
					if (!empty($options['crm_new_entity_out']) && array_key_exists($options['crm_new_entity_out'], $this->arParams['NEW_ENTITY_LIST']))
					{
						$this->arParams['DEFAULT_NEW_ENTITY_OUT'] = $options['crm_new_entity_out'];
					}

					if (!empty($options['crm_lead_source']) && array_key_exists($options['crm_lead_source'], $this->arParams['LEAD_SOURCE_LIST']))
					{
						$this->arParams['DEFAULT_LEAD_SOURCE'] = $options['crm_lead_source'];
					}

					if (!empty($options['crm_lead_resp']))
					{
						$this->arParams['CRM_QUEUE'] = \Bitrix\Main\UserTable::getList(array(
							'filter' => array(
								'ID' => $options['crm_lead_resp'],
							),
						))->fetchAll();

						$order = array_flip(array_values(array_unique($options['crm_lead_resp'])));
						usort($this->arParams['CRM_QUEUE'], function ($a, $b) use (&$order)
						{
							return isset($order[$a['ID']], $order[$b['ID']]) ? $order[$a['ID']]-$order[$b['ID']] : 0;
						});
					}

					$this->arParams['NEW_LEAD_FOR'] = is_array($options['crm_new_lead_for']) ? $options['crm_new_lead_for'] : array();
				}

				if (empty($this->arParams['CRM_QUEUE']))
				{
					$this->arParams['CRM_QUEUE'] = \Bitrix\Main\UserTable::getList(array(
						'filter' => array(
							'ID' => $USER->getId(),
						),
					))->fetchAll();
				}
			}
		}
		$this->arResult['FORBIDDEN_TO_SHARE_MAILBOX'] = false;
		$sharedMailboxesLimit = LicenseManager::getSharedMailboxesLimit();
		if ($sharedMailboxesLimit >= 0)
		{
			$sharedMailboxesIds = Mail\Helper\Mailbox\SharedMailboxesManager::getSharedMailboxesIds();
			if (count($sharedMailboxesIds) >= $sharedMailboxesLimit
				&& (!empty($mailbox) ? (!in_array((int)$mailbox['ID'], $sharedMailboxesIds, true)) : true))
			{
				$this->arResult['FORBIDDEN_TO_SHARE_MAILBOX'] = true;
			}
		}
		if (!empty($mailbox))
		{
			$mailboxSyncManager = new Mail\Helper\Mailbox\MailboxSyncManager(Main\Engine\CurrentUser::get()->getId());
			$this->arResult['LAST_MAIL_CHECK_DATE'] = $mailboxSyncManager->getLastMailboxSyncTime($mailbox['ID']);
			if ($this->arResult['LAST_MAIL_CHECK_DATE'] !== null)
			{
				$this->arResult['LAST_MAIL_CHECK_DATE'] =  formatDate(
					array('s' => 'sago', 'i' => 'iago', 'H' => 'Hago', 'd' => 'dago', 'm' => 'mago', 'Y' => 'Yago'),
					(int) $this->arResult['LAST_MAIL_CHECK_DATE']
				);
			}
			$this->arResult['LAST_MAIL_CHECK_STATUS'] = $mailboxSyncManager->getLastMailboxSyncIsSuccessStatus($mailbox['ID']);
		}

		$this->includeComponentTemplate('edit');
	}

	public function saveAction($fields)
	{
		global $USER;

		if (!empty($fields['site_id']))
		{
			$currentSite = \CSite::getById($fields['site_id'])->fetch();
		}

		if (empty($currentSite))
		{
			return $this->error(Loc::getMessage('MAIL_CLIENT_FORM_ERROR'));
		}

		if (!empty($fields['service_id']))
		{
			$service = Mail\MailServicesTable::getList(array(
				'filter' => array(
					'=ID'          => $fields['service_id'],
					'ACTIVE'       => 'Y',
					'SERVICE_TYPE' => 'imap',
				),
			))->fetch();
		}

		if (empty($service) || $service['SITE_ID'] != $currentSite['LID'])
		{
			return $this->error(Loc::getMessage('MAIL_CLIENT_FORM_ERROR'));
		}

		if ($fields['mailbox_id'] > 0)
		{
			$mailbox = Mail\MailboxTable::getList(array(
				'filter' => array(
					'=ID' => $fields['mailbox_id'],
					'=ACTIVE' => 'Y',
					'=USER_ID' => $USER->getId(),
					'=SERVER_TYPE' => 'imap',
				),
			))->fetch();

			if (!empty($mailbox))
			{
				if ($mailbox['SERVICE_ID'] != $service['ID'])
				{
					return $this->error(Loc::getMessage('MAIL_CLIENT_FORM_ERROR'));
				}

				foreach (array($mailbox['EMAIL'], $mailbox['NAME'], $mailbox['LOGIN']) as $item)
				{
					$address = new \Bitrix\Main\Mail\Address($item);
					if ($address->validate())
					{
						$mailbox['EMAIL'] = $address->getEmail();
						break;
					}
				}
			}
		}

		if (empty($mailbox))
		{
			if (!$this->canConnectNewMailbox())
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_DENIED'));
			}

			$mailboxData = array(
				'SERVER'   => $service['SERVER'] ?: trim($fields['server_imap']),
				'PORT'     => $service['PORT'] ?: (int) $fields['port_imap'],
				'USE_TLS'  => $service['ENCRYPTION'] ?: $fields['ssl_imap'],
				'LINK'     => $service['LINK'] ?: trim($fields['link']),
				'EMAIL'    => trim($fields['email']),
				'NAME'     => trim($fields['name']),
				'USERNAME' => trim($fields['sender']),
				'LOGIN'    => $fields['login_imap'],
				'PASSWORD' => $fields['pass_imap'],
				'PERIOD_CHECK' => 60 * 24,
				'OPTIONS'  => array(
					'flags'     => array(),
					'sync_from' => time(),
					'imap'      => array(
						'income' => array('INBOX'),
					),
					'crm_sync_from' => time(),
				),
			);
		}
		else
		{
			$mailboxData = array(
				'SERVER'   => $service['SERVER'] ? $mailbox['SERVER'] : trim($fields['server_imap']),
				'PORT'     => $service['PORT'] ? $mailbox['PORT'] : (int) $fields['port_imap'],
				'USE_TLS'  => $service['ENCRYPTION'] ? $mailbox['USE_TLS'] : $fields['ssl_imap'],
				'LINK'     => $service['LINK'] ? $mailbox['LINK'] : trim($fields['link']),
				'EMAIL'    => $mailbox['EMAIL'] ?: trim($fields['email']),
				'NAME'     => trim($fields['name']),
				'USERNAME' => trim($fields['sender']),
				'LOGIN'    => $mailbox['LOGIN'],
				'PASSWORD' => $mailbox['PASSWORD'],
				'OPTIONS'  => (array) $mailbox['OPTIONS'],
			);

			if (strlen($fields['pass_imap']) > 0 && $fields['pass_imap'] != $fields['pass_placeholder'])
			{
				$mailboxData['PASSWORD'] = $fields['pass_imap'];
			}

			$mailboxData['OPTIONS']['flags'] = array_diff(
				(array) $mailboxData['OPTIONS']['flags'],
				array(
					'crm_preconnect', 'crm_connect', 'crm_public_bind',
					'crm_deny_new_lead', 'crm_deny_entity_in', 'crm_deny_entity_out', 'crm_deny_new_contact',
				)
			);

			$mailboxData['OPTIONS']['imap'] = (array) $mailboxData['OPTIONS']['imap'];

			if (!empty($_REQUEST['imap_dirs']))
			{
				$imapDirs = $_REQUEST['imap_dirs'];

				$mailboxData['OPTIONS']['imap']['ignore'] = array_values(array_diff(
					array_keys($mailboxData['OPTIONS']['imap']['dirs']),
					$imapDirs['sync']
				));

				$mailboxData['OPTIONS']['imap'][MessageFolder::OUTCOME] = array_values((array) $imapDirs[MessageFolder::OUTCOME]);
				$mailboxData['OPTIONS']['imap'][MessageFolder::TRASH] = array_values((array) $imapDirs[MessageFolder::TRASH]);
				$mailboxData['OPTIONS']['imap'][MessageFolder::SPAM] = array_values((array) $imapDirs[MessageFolder::SPAM]);
			}
		}

		$mailboxData['OPTIONS']['name'] = $mailboxData['USERNAME'];

		if ($fields['oauth_uid'])
		{
			if (!empty($mailbox) && 'S' == $fields['oauth_mode'])
			{
				$userdata = Mail\Helper\OAuth::getUserDataByMeta($mailbox['PASSWORD']);
			}
			else
			{
				if ($oauthHelper = Mail\MailServicesTable::getOAuthHelper($service))
				{
					$oauthHelper->getStoredToken($fields['oauth_uid']);

					$userdata = $oauthHelper->getUserData();

					$mailboxData['PASSWORD'] = $oauthHelper->buildMeta();
				}
			}

			if (empty($userdata['email']))
			{
				$this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_OAUTH_ACC_ERROR'));
				if (!empty($userdata['error']))
				{
					$this->error($userdata['error']);
				}

				return;
			}
			else
			{
				if (!empty($mailbox['EMAIL']) && $mailbox['EMAIL'] != strtolower(trim($userdata['email'])))
				{
					return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_OAUTH_ACC_DIFF'));
				}

				$mailboxData['EMAIL'] = strtolower(trim($userdata['email']));
				$mailboxData['LOGIN'] = strtolower(trim($userdata['email']));
			}
		}

		if (empty($mailbox['EMAIL']))
		{
			$address = new Main\Mail\Address($mailboxData['EMAIL']);
			if (!$address->validate())
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_EMAIL_BAD'));
			}

			$mailboxData['EMAIL'] = $address->getEmail();

			$mailbox = Mail\MailboxTable::getList(array(
				'filter' => array(
					'=EMAIL' => $mailboxData['EMAIL'],
					'=USER_ID' => $USER->getId(),
					'=ACTIVE' => 'Y',
					'=LID' => $currentSite['LID'],
				),
			))->fetch();

			if (!empty($mailbox))
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_EMAIL_EXISTS'));
			}
		}

		if (empty($mailboxData['NAME']))
		{
			$mailboxData['NAME'] = $mailboxData['EMAIL'];
		}

		if (!$service['SERVER'])
		{
			$regex = '/^(?:(?:http|https|ssl|tls|imap):\/\/)?((?:[a-z0-9](?:-*[a-z0-9])*\.?)+)$/i';
			if (!preg_match($regex, $mailboxData['SERVER'], $matches) && strlen($matches[1]) > 0)
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_SERVER_BAD'));
			}

			$mailboxData['SERVER'] = $matches[1];
		}

		if (!$service['PORT'])
		{
			if ($mailboxData['PORT'] <= 0 || $mailboxData['PORT'] > 65535)
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_PORT_BAD'));
			}
		}

		if (!in_array($mailboxData['USE_TLS'], array('Y', 'S')))
		{
			$mailboxData['USE_TLS'] = 'N';
		}

		if (!$service['LINK'] && $mailboxData['LINK'])
		{
			$regex = '/^(https?:\/\/)?((?:[a-z0-9](?:-*[a-z0-9])*\.?)+)(:[0-9]+)?\/?(.*)/i';
			if (!(preg_match($regex, $mailboxData['LINK'], $matches) && strlen($matches[2]) > 0))
			{
				return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_LINK_BAD'));
			}

			$mailboxData['LINK'] = $matches[0];
			if (strlen($matches[1]) == 0)
			{
				$mailboxData['LINK'] = 'http://' . $mailboxData['LINK'];
			}
		}

		if (empty($mailbox))
		{
			if (array_key_exists('mail_connect_import_messages', $fields) && $fields['mail_connect_import_messages'] === 'Y' && array_key_exists('msg_max_age', $fields))
			{
				$maxAge = (int) $fields['msg_max_age'];
				$maxAgeLimit = LicenseManager::getSyncOldLimit();

				if ($maxAgeLimit >= 0 && $maxAge > $maxAgeLimit)
				{
					return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_MAX_AGE_ERROR'));
				}

				if ($maxAge < 0)
				{
					unset($mailboxData['OPTIONS']['sync_from']);
				}
				else
				{
					$mailboxData['OPTIONS']['sync_from'] = strtotime(sprintf('-%u days', $maxAge));
				}
			}
		}

		$unseen = Mail\Helper::getImapUnseen($mailboxData, 'inbox', $error, $errors);
		if ($unseen === false)
		{
			return $this->error($errors instanceof Main\ErrorCollection ? $errors : $error);
		}

		if (!empty($fields['use_smtp']) && !empty($mailboxData['EMAIL']))
		{
			$smtpConfig = array();

			$senderFields = array(
				'NAME' => $mailboxData['USERNAME'],
				'EMAIL' => $mailboxData['EMAIL'],
				'USER_ID' => $USER->getId(),
				'IS_CONFIRMED' => false,
				'IS_PUBLIC' => false,
				'OPTIONS' => array(
					'source' => 'mail.client.config',
				),
			);

			$res = Main\Mail\Internal\SenderTable::getList(array(
				'filter' => array(
					'IS_CONFIRMED' => true,
					'=EMAIL' => $mailboxData['EMAIL'],
				),
				'order' => array(
					'ID' => 'DESC',
				),
			));
			while ($item = $res->fetch())
			{
				if (!empty($item['OPTIONS']['smtp']['server']) && empty($item['OPTIONS']['smtp']['encrypted']))
				{
					$smtpConfig = $smtpConfirmed = $item['OPTIONS']['smtp'];

					$senderFields = $item;
					$senderFields['OPTIONS']['__replaces'] = $item['ID'];

					unset($senderFields['ID']);

					break;
				}
			}

			$smtpConfig = array(
				'server'   => $service['SMTP_SERVER'] ?: trim($fields['server_smtp']),
				'port'     => $service['SMTP_PORT'] ?: (int) $fields['port_smtp'],
				'login'    => $service['SMTP_LOGIN_AS_IMAP'] == 'Y' ? $mailboxData['LOGIN'] : $fields['login_smtp'],
				'password' => $smtpConfig['password'],
			);

			if ($service['SMTP_PASSWORD_AS_IMAP'] == 'Y' && !$fields['oauth_uid'])
			{
				$smtpConfig['password'] = $mailboxData['PASSWORD'];
			}
			else if (strlen($fields['pass_smtp']) > 0 && $fields['pass_smtp'] != $fields['pass_placeholder'])
			{
				$smtpConfig['password'] = $fields['pass_smtp'];
			}

			if (!$service['SMTP_SERVER'])
			{
				$regex = '/^(?:(?:http|https|ssl|tls|smtp):\/\/)?((?:[a-z0-9](?:-*[a-z0-9])*\.?)+)$/i';
				if (!preg_match($regex, $smtpConfig['server'], $matches) && strlen($matches[1]) > 0)
				{
					return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_SERVER_BAD'));
				}

				$smtpConfig['server'] = $matches[1];
			}

			if (!$service['SMTP_PORT'])
			{
				if ($smtpConfig['port'] <= 0 || $smtpConfig['port'] > 65535)
				{
					return $this->error(Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_PORT_BAD'));
				}
			}

			$senderFields['OPTIONS']['smtp'] = $smtpConfig;

			if (!empty($smtpConfirmed))
			{
				$senderFields['IS_CONFIRMED'] = !array_diff(
					array('server', 'port', 'login', 'password'),
					array_keys(array_intersect_assoc($smtpConfig, $smtpConfirmed))
				);
			}
		}

		if ($fields['use_crm'] == 'Y')
		{
			$crmAvailable = false;
			if (Main\Loader::includeModule('crm') && \CCrmPerms::isAccessEnabled())
			{
				$crmAvailable = $USER->isAdmin() || $USER->canDoOperation('bitrix24_config')
					|| \COption::getOptionString('intranet', 'allow_external_mail_crm', 'Y', SITE_ID) == 'Y';

				if ($crmAvailable)
				{
					$mailboxData['OPTIONS']['flags'][] = 'crm_connect';

					if ($fields['crm_sync_old'] == 'Y')
					{
						$maxAge = (int) $fields['crm_max_age'];
						if ($maxAge < 0)
						{
							unset($mailboxData['OPTIONS']['crm_sync_from']);
						}
						else
						{
							$mailboxData['OPTIONS']['crm_sync_from'] = strtotime(sprintf('-%u days', $maxAge));
						}
					}

					if ($fields['crm_public'] == 'Y')
					{
						$mailboxData['OPTIONS']['flags'][] = 'crm_public_bind';
					}

					if ($fields['crm_allow_entity_in'] != 'Y')
					{
						$mailboxData['OPTIONS']['flags'][] = 'crm_deny_entity_in';
					}
					if ($fields['crm_allow_entity_out'] != 'Y')
					{
						$mailboxData['OPTIONS']['flags'][] = 'crm_deny_entity_out';
					}

					$newEntityList = array(\CCrmOwnerType::LeadName, \CCrmOwnerType::ContactName);
					if (!empty($fields['crm_entity_in']) && in_array($fields['crm_entity_in'], $newEntityList))
					{
						$mailboxData['OPTIONS']['crm_new_entity_in'] = $fields['crm_entity_in'];
					}
					if (!empty($fields['crm_entity_out']) && in_array($fields['crm_entity_out'], $newEntityList))
					{
						$mailboxData['OPTIONS']['crm_new_entity_out'] = $fields['crm_entity_out'];
					}

					if ($fields['crm_vcf'] != 'Y')
					{
						$mailboxData['OPTIONS']['flags'][] = 'crm_deny_new_contact';
					}

					$leadSourceList = \CCrmStatus::getStatusList('SOURCE');
					if (is_set($leadSourceList, $fields['crm_lead_source']))
					{
						$mailboxData['OPTIONS']['crm_lead_source'] = $fields['crm_lead_source'];
					}

					$mailboxData['OPTIONS']['crm_new_lead_for'] = array();
					if (!empty($fields['crm_new_lead_for']))
					{
						$newLeadFor = preg_split('/[\r\n,;]+/', $fields['crm_new_lead_for']);
						foreach ($newLeadFor as $i => $item)
						{
							$address = new Main\Mail\Address($item);

							$newLeadFor[$i] = $address->validate() ? $address->getEmail() : null;
						}

						$mailboxData['OPTIONS']['crm_new_lead_for'] = array_values(array_unique(array_filter($newLeadFor)));
					}

					$mailboxData['OPTIONS']['crm_lead_resp'] = array();
					if (!empty($fields['crm_queue']['U']))
					{
						foreach ((array) $fields['crm_queue']['U'] as $item)
						{
							if (preg_match('/^U(\d+)$/i', trim($item), $matches))
								$mailboxData['OPTIONS']['crm_lead_resp'][] = (int) $matches[1];
						}
					}
					if (empty($mailboxData['OPTIONS']['crm_lead_resp']))
					{
						$mailboxData['OPTIONS']['crm_lead_resp'] = array($USER->getId());
					}
				}
			}
		}

		if (!empty($senderFields))
		{
			Main\Mail\Sender::add($senderFields);

			if (empty($senderFields['IS_CONFIRMED']))
			{
				return $this->error('MAIL_CLIENT_CONFIG_SMTP_CONFIRM');
			}
		}

		$mailboxData['OPTIONS']['version'] = 5;

		if (empty($mailbox))
		{
			$mailboxData = array_merge(array(
				'LID'         => $currentSite['LID'],
				'ACTIVE'      => 'Y',
				'SERVICE_ID'  => $service['ID'],
				'SERVER_TYPE' => $service['SERVICE_TYPE'],
				'CHARSET'     => $currentSite['CHARSET'],
				'USER_ID'     => $USER->getId(),
			), $mailboxData);

			$result = $mailboxId = \CMailbox::add($mailboxData);
		}
		else
		{
			$result = \CMailbox::update($mailboxId = $mailbox['ID'], $mailboxData);
		}

		if (!($result > 0))
		{
			return $this->error(Loc::getMessage('MAIL_CLIENT_SAVE_ERROR'));
		}

		$entity = Mail\Internals\MailboxAccessTable::getEntity();
		$entity->getConnection()->query(sprintf(
			'DELETE FROM %s WHERE %s',
			$entity->getConnection()->getSqlHelper()->quote($entity->getDbTableName()),
			Main\Entity\Query::buildFilterSql(
				$entity,
				array(
					'=MAILBOX_ID' => $mailboxId,
				)
			)
		));

		$ownerAccessCode = 'U' . $USER->getId(); // @TODO: mailbox.USER_ID
		$access = array($ownerAccessCode);
		if (!empty($fields['access']) && is_array($fields['access']))
		{
			foreach ($fields['access'] as $code => $list)
			{
				if (in_array($code, array('U')) && is_array($list))
				{
					$access = array_merge(
						$access,
						array_filter(
							$list,
							function ($item) use (&$code)
							{
								return preg_match(sprintf('/^%s\d+$/i', preg_quote($code, '/')), trim($item));
							}
						)
					);
				}
			}
		}

		$sharedMailboxesLimit = LicenseManager::getSharedMailboxesLimit();
		if (count(array_unique($access)) > 1 && $sharedMailboxesLimit >= 0)
		{
			$alreadySharedMailboxesIds = Mail\Helper\Mailbox\SharedMailboxesManager::getSharedMailboxesIds();
			if (count($alreadySharedMailboxesIds) >= $sharedMailboxesLimit && !in_array($mailboxId, $alreadySharedMailboxesIds))
			{
				$access = array($ownerAccessCode);
			}
		}

		foreach (array_unique($access) as $item)
		{
			Mail\Internals\MailboxAccessTable::add(array(
				'MAILBOX_ID' => $mailboxId,
				'TASK_ID' => 0,
				'ACCESS_CODE' => $item,
			));
		}

		$mailboxHelper = Mail\Helper\Mailbox::createInstance($mailboxId);
		$mailboxHelper->cacheDirs();

		$res = Mail\MailFilterTable::getList(array(
			'select' => array(
				'ID',
			),
			'filter' => array(
				'=MAILBOX_ID'  => $mailboxId,
				'=ACTION_TYPE' => 'crm_imap'
			)
		));
		while ($filter = $res->fetch())
		{
			\CMailFilter::delete($filter['ID']);
		}

		if ($fields['use_crm'] == 'Y' && $crmAvailable)
		{
			$filterFields = array(
				'MAILBOX_ID'         => $mailboxId,
				'NAME'               => sprintf('CRM IMAP %u', $mailboxId),
				'ACTION_TYPE'        => 'crm_imap',
				'WHEN_MAIL_RECEIVED' => 'Y',
				'WHEN_MANUALLY_RUN'  => 'Y',
			);

			\CMailFilter::add($filterFields);

			// @TODO: process old messages
		}

		return array('id' => $mailboxId);
	}

	public function deleteAction($id)
	{
		global $USER;

		$mailbox = Mail\MailboxTable::getList(array(
			'filter' => array(
				'=ID' => $id,
				'=ACTIVE' => 'Y',
				'=USER_ID' => $USER->getId(),
				'=SERVER_TYPE' => 'imap',
			),
		))->fetch();

		if (empty($mailbox))
		{
			showError(Loc::getMessage('MAIL_CLIENT_ELEMENT_NOT_FOUND'));
			return;
		}

		\CMailbox::delete($mailbox['ID']);

		\CUserCounter::clear($USER->getId(), 'mail_unseen', $mailbox['LID']);
		$mailboxSyncManager = new \Bitrix\Mail\Helper\Mailbox\MailboxSyncManager($mailbox['USER_ID']);
		$mailboxSyncManager->deleteSyncData($mailbox['ID']);
	}

	protected function canConnectNewMailbox()
	{
		$userMailboxesLimit = LicenseManager::getUserMailboxesLimit();
		if ($userMailboxesLimit >= 0)
		{
			if ($this->getUserOwnedMailboxCount() >= $userMailboxesLimit)
			{
				return false;
			}
		}

		return true;
	}

	protected function getUserOwnedMailboxCount()
	{
		global $USER;

		$res = Mail\MailboxTable::getList(array(
			'select' => array(
				new Main\Entity\ExpressionField('OWNED', 'COUNT(%s)', 'ID'),
			),
			'filter' => array(
				'=ACTIVE' => 'Y',
				'=USER_ID' => $USER->getId(),
				'=SERVER_TYPE' => 'imap',
			),
		))->fetch();

		return $res['OWNED'];
	}

	protected function error($error)
	{
		if ($error instanceof Main\ErrorCollection)
		{
			$messages = array();
			$details  = array();

			foreach ($error as $item)
			{
				${$item->getCode() < 0 ? 'details' : 'messages'}[] = $item;
			}

			if (count($messages) == 1 && reset($messages)->getCode() == Mail\Imap::ERR_AUTH)
			{
				$messages = array(
					new Main\Error(getMessage('MAIL_CLIENT_CONFIG_IMAP_AUTH_ERR_EXT'), Mail\Imap::ERR_AUTH),
				);
			}

			$reduce = function($error)
			{
				return $error->getMessage();
			};

			$this->errorCollection[] = new Main\Error(
				join(': ', array_map($reduce, $messages)),
				0,
				join(': ', array_map($reduce, $details))
			);
		}
		else
		{
			$this->errorCollection[] = new Main\Error($error);
		}
	}

	/**
	 * Getting array of errors.
	 * @return Error[]
	 */
	final public function getErrors()
	{
		return $this->errorCollection->toArray();
	}

	/**
	 * Getting once error with the necessary code.
	 * @param string $code Code of error.
	 * @return Error
	 */
	final public function getErrorByCode($code)
	{
		return $this->errorCollection->getErrorByCode($code);
	}

}
