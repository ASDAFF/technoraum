<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2013 Bitrix
 */

namespace Bitrix\Main\ORM\Data;

use Bitrix\Main;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\EntityError;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\FieldError;
use Bitrix\Main\ORM\Fields\FieldTypeMask;
use Bitrix\Main\ORM\Fields\ScalarField;
use Bitrix\Main\ORM\Objectify\Values;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\ORM\Query\Result as QueryResult;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Filter\ConditionTree as Filter;
use Bitrix\Main\ORM\Objectify\EntityObject;
use Bitrix\Main\ORM\Objectify\Collection;

Loc::loadMessages(__FILE__);

/**
 * Base entity data manager
 */
abstract class DataManager
{
	const EVENT_ON_BEFORE_ADD = "OnBeforeAdd";
	const EVENT_ON_ADD = "OnAdd";
	const EVENT_ON_AFTER_ADD = "OnAfterAdd";
	const EVENT_ON_BEFORE_UPDATE = "OnBeforeUpdate";
	const EVENT_ON_UPDATE = "OnUpdate";
	const EVENT_ON_AFTER_UPDATE = "OnAfterUpdate";
	const EVENT_ON_BEFORE_DELETE = "OnBeforeDelete";
	const EVENT_ON_DELETE = "OnDelete";
	const EVENT_ON_AFTER_DELETE = "OnAfterDelete";

	/** @var Entity[] */
	protected static $entity;

	/** @var EntityObject[] Cache of class names */
	protected static $objectClass;

	/** @var Collection[] Cache of class names */
	protected static $collectionClass;

	/** @var array Restricted words for object class name */
	protected static $reservedWords = [
		// keywords
		'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue',
		'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach',
		'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'finally', 'for', 'foreach', 'function',
		'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset',
		'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return',
		'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield',
		// classes
		'self', 'parent',
		// others
		'int', 'float', 'bool', 'string', 'true', 'false', 'null', 'void', 'iterable', 'object', 'resource', 'mixed', 'numeric',
	];

	/**
	 * Returns entity object
	 *
	 * @return Entity
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	public static function getEntity()
	{
		$class = get_called_class();
		$class = Entity::normalizeEntityClass($class);

		if (!isset(static::$entity[$class]))
		{
			static::$entity[$class] = Entity::getInstance($class);
		}

		return static::$entity[$class];
	}

	public static function unsetEntity($class)
	{
		$class = Entity::normalizeEntityClass($class);

		if (isset(static::$entity[$class]))
		{
			unset(static::$entity[$class]);
		}
	}

	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return null;
	}

	/**
	 * Returns connection name for entity
	 *
	 * @return string
	 */
	public static function getConnectionName()
	{
		return 'default';
	}

	/**
	 * Returns class of Object for current entity.
	 *
	 * @return string|EntityObject
	 */
	public static function getObjectClass()
	{
		if (!isset(static::$objectClass[get_called_class()]))
		{
			$objectClass = Entity::normalizeName(get_called_class());

			// make class name more unique
			$namespace = substr($objectClass, 0, strrpos($objectClass, '\\')+1);
			$className = substr($objectClass, strrpos($objectClass, '\\') + 1);

			$className = Entity::getDefaultObjectClassName($className);



			// with prefix EO_ it's not actual anymore
			/*if (in_array(strtolower($className), static::$reservedWords))
			{
				// add postfix to reserved word
				$className .= 'Object';
			}*/

			// the same reason
			/*if (class_exists($objectClass) && !is_subclass_of($objectClass, EntityObject::class))
			{
				// add unique postfix to existing class
				$className .= substr(md5($objectClass), 0, 6);
			}*/

			static::$objectClass[get_called_class()] = $namespace.$className;
		}

		return static::$objectClass[get_called_class()];
	}

	/**
	 * Returns class name (without namespace) of Object for current entity.
	 *
	 * @return string
	 */
	final public static function getObjectClassName()
	{
		$class = static::getObjectClass();
		return substr($class, strrpos($class, '\\')+1);
	}

	/**
	 * Returns class of Object collection for current entity.
	 *
	 * @return string|Collection
	 */
	public static function getCollectionClass()
	{
		if (!isset(static::$collectionClass[get_called_class()]))
		{
			$objectClass = Entity::normalizeName(get_called_class());

			// make class name more unique
			$namespace = substr($objectClass, 0, strrpos($objectClass, '\\')+1);
			$className = substr($objectClass, strrpos($objectClass, '\\') + 1);

			$className = Entity::getDefaultCollectionClassName($className);

			static::$collectionClass[get_called_class()] = $namespace.$className;
		}

		return static::$collectionClass[get_called_class()];
	}

	/**
	 * Returns class name (without namespace) of Object collection for current entity.
	 *
	 * @return string
	 */
	final public static function getCollectionClassName()
	{
		$class = static::getCollectionClass();
		return substr($class, strrpos($class, '\\')+1);
	}

	/**
	 * @param bool $setDefaultValues
	 *
	 * @return null Actual type should be annotated by orm:annotate
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	final public function createObject($setDefaultValues = true)
	{
		return static::getEntity()->createObject($setDefaultValues);
	}

	/**
	 * @return null Actual type should be annotated by orm:annotate
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	final public function createCollection()
	{
		return static::getEntity()->createCollection();
	}

	/**
	 * @see EntityObject::wakeUp()
	 *
	 * @param $row
	 *
	 * @return null Actual type should be annotated by orm:annotate
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	final public function wakeUpObject($row)
	{
		return static::getEntity()->wakeUpObject($row);
	}

	/**
	 * @see Collection::wakeUp()
	 *
	 * @param $rows
	 *
	 * @return null Actual type should be annotated by orm:annotate
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	final public function wakeUpCollection($rows)
	{
		return static::getEntity()->wakeUpCollection($rows);
	}

	/**
	 * Returns entity map definition.
	 * To get initialized fields @see \Bitrix\Main\ORM\Entity::getFields() and \Bitrix\Main\ORM\Base::getField()
	 */
	public static function getMap()
	{
		return array();
	}

	public static function getUfId()
	{
		return null;
	}

	public static function isUts()
	{
		return false;
	}

	public static function isUtm()
	{
		return false;
	}

	/**
	 * @param Entity $entity
	 *
	 * @return null
	 */
	public static function postInitialize(Entity $entity)
	{
		return null;
	}

	/**
	 * Returns selection by entity's primary key and optional parameters for getList()
	 *
	 * @param mixed $primary    Primary key of the entity
	 * @param array $parameters Additional parameters for getList()
	 *
	 * @return QueryResult
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getByPrimary($primary, array $parameters = array())
	{
		static::normalizePrimary($primary);
		static::validatePrimary($primary);

		$primaryFilter = array();

		foreach ($primary as $k => $v)
		{
			$primaryFilter['='.$k] = $v;
		}

		if (isset($parameters['filter']))
		{
			$parameters['filter'] = array($primaryFilter, $parameters['filter']);
		}
		else
		{
			$parameters['filter'] = $primaryFilter;
		}

		return static::getList($parameters);
	}

	/**
	 * Returns selection by entity's primary key
	 *
	 * @param mixed $id Primary key of the entity
	 *
	 * @return QueryResult
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getById($id)
	{
		return static::getByPrimary($id);
	}

	/**
	 * Returns one row (or null) by entity's primary key
	 *
	 * @param mixed $id Primary key of the entity
	 *
	 * @return array|null
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getRowById($id)
	{
		$result = static::getByPrimary($id);
		$row = $result->fetch();

		return (is_array($row)? $row : null);
	}

	/**
	 * Returns one row (or null) by parameters for getList()
	 *
	 * @param array $parameters Primary key of the entity
	 *
	 * @return array|null
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getRow(array $parameters)
	{
		$parameters['limit'] = 1;
		$result = static::getList($parameters);
		$row = $result->fetch();

		return (is_array($row)? $row : null);
	}

	/**
	 * Executes the query and returns selection by parameters of the query. This function is an alias to the Query object functions
	 *
	 * @param array $parameters An array of query parameters, available keys are:<br>
	 * 		"select" => array of fields in the SELECT part of the query, aliases are possible in the form of "alias"=>"field";<br>
	 * 		"filter" => array of filters in the WHERE/HAVING part of the query in the form of "(condition)field"=>"value";
	 * 			also could be an instance of Filter;<br>
	 * 		"group" => array of fields in the GROUP BY part of the query;<br>
	 * 		"order" => array of fields in the ORDER BY part of the query in the form of "field"=>"asc|desc";<br>
	 * 		"limit" => integer indicating maximum number of rows in the selection (like LIMIT n in MySql);<br>
	 * 		"offset" => integer indicating first row number in the selection (like LIMIT n, 100 in MySql);<br>
	 *		"runtime" => array of entity fields created dynamically;<br>
	 * 		"cache => array of cache options:<br>
	 * 			"ttl" => integer indicating cache TTL;<br>
	 * 			"cache_joins" => boolean enabling to cache joins, false by default.
	 * @see Query::filter()
	 *
	 * @return QueryResult
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getList(array $parameters = array())
	{
		$query = static::query();

		if(!isset($parameters['select']))
		{
			$query->setSelect(array('*'));
		}

		foreach($parameters as $param => $value)
		{
			switch($param)
			{
				case 'select':
					$query->setSelect($value);
					break;
				case 'filter':
					$value instanceof Filter ? $query->where($value) : $query->setFilter($value);
					break;
				case 'group':
					$query->setGroup($value);
					break;
				case 'order';
					$query->setOrder($value);
					break;
				case 'limit':
					$query->setLimit($value);
					break;
				case 'offset':
					$query->setOffset($value);
					break;
				case 'count_total':
					$query->countTotal($value);
					break;
				case 'runtime':
					foreach ($value as $name => $fieldInfo)
					{
						$query->registerRuntimeField($name, $fieldInfo);
					}
					break;
				case 'data_doubling':
					if($value)
					{
						$query->enableDataDoubling();
					}
					else
					{
						$query->disableDataDoubling();
					}
					break;
				case 'cache':
					$query->setCacheTtl($value["ttl"]);
					if(isset($value["cache_joins"]))
					{
						$query->cacheJoins($value["cache_joins"]);
					}
					break;
				default:
					throw new Main\ArgumentException("Unknown parameter: ".$param, $param);
			}
		}

		return $query->exec();
	}

	/**
	 * Performs COUNT query on entity and returns the result.
	 *
	 * @param array|Filter $filter
	 * @param array $cache An array of cache options
	 * 		"ttl" => integer indicating cache TTL
	 * @return int
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public static function getCount($filter = array(), array $cache = array())
	{
		$query = static::query();

		// new filter
		$query->addSelect(new ExpressionField('CNT', 'COUNT(1)'));

		if ($filter instanceof Filter)
		{
			$query->where($filter);
		}
		else
		{
			$query->setFilter($filter);
		}

		if(isset($cache["ttl"]))
		{
			$query->setCacheTtl($cache["ttl"]);
		}

		$result = $query->exec()->fetch();

		return $result['CNT'];
	}

	/**
	 * Creates and returns the Query object for the entity
	 *
	 * @return Query
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	public static function query()
	{
		return new Query(static::getEntity());
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	protected static function replaceFieldName($data = array())
	{
		$entity = static::getEntity();
		foreach ($data as $fieldName => $value)
		{
			/** @var ScalarField $field */
			$field = $entity->getField($fieldName);
			$columnName = $field->getColumnName();
			if($columnName != $fieldName)
			{
				$data[$columnName] = $data[$fieldName];
				unset($data[$fieldName]);
			}
		}

		return $data;
	}

	/**
	 * @param       $primary
	 * @param array $data
	 *
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	protected static function normalizePrimary(&$primary, $data = array())
	{
		$entity = static::getEntity();
		$entity_primary = $entity->getPrimaryArray();

		if ($primary === null)
		{
			$primary = array();

			// extract primary from data array
			foreach ($entity_primary as $key)
			{
				/** @var ScalarField $field  */
				$field = $entity->getField($key);
				if ($field->isAutocomplete())
				{
					continue;
				}

				if (!isset($data[$key]))
				{
					throw new Main\ArgumentException(sprintf(
						'Primary `%s` was not found when trying to query %s row.', $key, $entity->getName()
					));
				}

				$primary[$key] = $data[$key];
			}
		}
		elseif (is_scalar($primary))
		{
			if (count($entity_primary) > 1)
			{
				throw new Main\ArgumentException(sprintf(
					'Require multi primary {`%s`}, but one scalar value "%s" found when trying to query %s row.',
					join('`, `', $entity_primary), $primary, $entity->getName()
				));
			}

			$primary = array($entity_primary[0] => $primary);
		}
	}

	/**
	 * @param $primary
	 *
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	protected static function validatePrimary($primary)
	{
		$entity = static::getEntity();
		if (is_array($primary))
		{
			if(empty($primary))
			{
				throw new Main\ArgumentException(sprintf(
					'Empty primary found when trying to query %s row.', $entity->getName()
				));
			}

			$entity_primary = $entity->getPrimaryArray();

			foreach (array_keys($primary) as $key)
			{
				if (!in_array($key, $entity_primary, true))
				{
					throw new Main\ArgumentException(sprintf(
						'Unknown primary `%s` found when trying to query %s row.',
						$key, $entity->getName()
					));
				}
			}
		}
		else
		{
			throw new Main\ArgumentException(sprintf(
				'Unknown type of primary "%s" found when trying to query %s row.', gettype($primary), $entity->getName()
			));
		}

		// primary values validation
		foreach ($primary as $key => $value)
		{
			if (!is_scalar($value) && !($value instanceof Main\Type\Date))
			{
				throw new Main\ArgumentException(sprintf(
					'Unknown value type "%s" for primary "%s" found when trying to query %s row.',
					gettype($value), $key, $entity->getName()
				));
			}
		}
	}

	/**
	 * Checks the data fields before saving to DB. Result stores in the $result object
	 *
	 * @param Result $result
	 * @param mixed  $primary
	 * @param array  $data
	 *
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	public static function checkFields(Result $result, $primary, array $data)
	{
		$entity = static::getEntity();
		//checks required fields
		foreach ($entity->getFields() as $field)
		{
			if ($field instanceof ScalarField && $field->isRequired())
			{
				$fieldName = $field->getName();
				if (
					(empty($primary) && (!isset($data[$fieldName]) || $field->isValueEmpty($data[$fieldName])))
					|| (!empty($primary) && isset($data[$fieldName]) && $field->isValueEmpty($data[$fieldName]))
				)
				{
					$result->addError(new FieldError(
						$field,
						Loc::getMessage("MAIN_ENTITY_FIELD_REQUIRED", array("#FIELD#"=>$field->getTitle())),
						FieldError::EMPTY_REQUIRED
					));
				}
			}
		}

		// checks data - fieldname & type & strlen etc.
		foreach ($data as $k => $v)
		{
			if ($entity->hasField($k))
			{
				$field = $entity->getField($k);

			}
			else
			{
				throw new Main\ArgumentException(sprintf(
					'Field `%s` not found in entity when trying to query %s row.',
					$k, $entity->getName()
				));
			}

			$field->validateValue($v, $primary, $data, $result);
		}
	}

	/**
	 * @param array $fields
	 * @param bool  $setDefaultValues
	 * @param array $primary
	 *
	 * @return EntityObject
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	protected static function convertArrayToObject(&$fields, $setDefaultValues = false, $primary = null)
	{
		// extended data format
		$data = null;

		if (isset($fields["fields"]) && is_array($fields["fields"]))
		{
			$data = $fields;
			$fields = $data["fields"];
		}

		// convert to object
		if (isset($fields['__object']))
		{
			$object = $fields['__object'];
			unset($fields['__object']);
		}
		else
		{
			$entity = static::getEntity();

			/** @var EntityObject $object */
			if ($primary === null)
			{
				$object = $entity->createObject($setDefaultValues);

				foreach ($fields as $fieldName => $value)
				{
					// sometimes data array can be used for storing non-entity data
					if ($entity->hasField($fieldName))
					{
						$object->sysSetValue($fieldName, $value);
					}
				}
			}
			else
			{
				$object = $entity->wakeUpObject($primary);

				foreach ($fields as $fieldName => $value)
				{
					// sometimes data array can be used for storing non-entity data
					if ($entity->hasField($fieldName))
					{
						if ($entity->getField($fieldName) instanceof ScalarField && $entity->getField($fieldName)->isPrimary())
						{
							// and ignore primary
							continue;
						}

						$object->sysSetValue($fieldName, $value);
					}
				}
			}
		}

		// auth context
		if (isset($data['auth_context']))
		{
			$object->authContext = $data['auth_context'];
		}

		return $object;
	}

	/**
	 * @param EntityObject                 $object
	 * @param                              $ufdata
	 * @param \Bitrix\Main\ORM\Data\Result $result
	 */
	protected static function checkUfFields($object, $ufdata, $result)
	{
		global $USER_FIELD_MANAGER, $APPLICATION;

		$userId = ($object->authContext && $object->authContext->getUserId())
			? $object->authContext->getUserId()
			: false;

		$ufPrimary = ($object->sysGetState() === Main\ORM\Objectify\State::RAW)
			? false
			: end($object->primary);

		if (!$USER_FIELD_MANAGER->CheckFields($object->entity->getUfId(), $ufPrimary, $ufdata, $userId))
		{
			if (is_object($APPLICATION) && $APPLICATION->getException())
			{
				$e = $APPLICATION->getException();
				$result->addError(new EntityError($e->getString()));
				$APPLICATION->resetException();
			}
			else
			{
				$result->addError(new EntityError("Unknown error while checking userfields"));
			}
		}
	}

	/**
	 * Adds row to entity table
	 *
	 * @param array $data An array with fields like
	 * 	array(
	 * 		"fields" => array(
	 * 			"FIELD1" => "value1",
	 * 			"FIELD2" => "value2",
	 * 		),
	 * 		"auth_context" => \Bitrix\Main\Authentication\Context object
	 *	)
	 *	or just a plain array of fields.
	 *
	 * @return AddResult Contains ID of inserted row
	 *
	 * @throws \Exception
	 */
	public static function add(array $data)
	{
		global $USER_FIELD_MANAGER;

		// compatibility
		$fields = $data;

		// prepare entity object for compatibility with new code
		$object = static::convertArrayToObject($fields, true);

		$entity = static::getEntity();
		$result = new AddResult();

		try
		{
			static::callOnBeforeAddEvent($object, $fields, $result);

			// actualize old-style fields array from object
			$fields = $object->collectValues(Values::CURRENT, FieldTypeMask::SCALAR);

			// uf values
			$ufdata = $object->collectValues(Values::CURRENT, FieldTypeMask::USERTYPE);

			// check data
			static::checkFields($result, null, $fields);

			// check uf data
			if (!empty($ufdata))
			{
				static::checkUfFields($object, $ufdata, $result);
			}

			// check if there is still some data
			if (!count($fields + $ufdata))
			{
				$result->addError(new EntityError("There is no data to add."));
			}

			// return if any error
			if (!$result->isSuccess(true))
			{
				return $result;
			}

			//event on adding
			self::callOnAddEvent($object, $fields, $ufdata);

			// use save modifiers
			$fieldsToDb = $fields;

			foreach ($fieldsToDb as $fieldName => $value)
			{
				$field = $entity->getField($fieldName);
				$fieldsToDb[$fieldName] = $field->modifyValueBeforeSave($value, $fields);
			}

			// save data
			$connection = $entity->getConnection();

			$tableName = $entity->getDBTableName();
			$identity = $entity->getAutoIncrement();

			$dataReplacedColumn = static::replaceFieldName($fieldsToDb);
			$id = $connection->add($tableName, $dataReplacedColumn, $identity);

			// build standard primary
			$primary = null;

			if (!empty($id))
			{
				if (strlen($entity->getAutoIncrement()))
				{
					$primary = array($entity->getAutoIncrement() => $id);
					static::normalizePrimary($primary);
				}
				else
				{
					// for those who did not set 'autocomplete' flag but wants to get id from result
					$primary = array('ID' => $id);
				}
			}
			else
			{
				static::normalizePrimary($primary, $fields);
			}

			// fill result
			$result->setPrimary($primary);
			$result->setData($fields);
			$result->setObject($object);

			foreach ($primary as $primaryName => $primaryValue)
			{
				$object->sysSetActual($primaryName, $primaryValue);
			}

			// save uf data
			if (!empty($ufdata))
			{
				$USER_FIELD_MANAGER->update($entity->getUfId(), end($primary), $ufdata);
			}

			$entity->cleanCache();

			static::callOnAfterAddEvent($object, $fields, $id);
		}
		catch (\Exception $e)
		{
			// check result to avoid warning
			$result->isSuccess();

			throw $e;
		}

		return $result;
	}

	/**
	 * @param      $rows
	 * @param bool $ignoreEvents
	 *
	 * @return AddResult
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	public static function addMulti($rows, $ignoreEvents = false)
	{
		global $USER_FIELD_MANAGER;

		$rows = array_values($rows);

		if (!$ignoreEvents && count($rows) > 1 && strlen(static::getEntity()->getAutoIncrement()))
		{
			throw new Main\SystemException(
				'Multi-insert doesn\'t work with events as far as we can not get last inserted IDs that we need for the events. '.
				'If you need event calling, use single add with foreach.'
			);
		}

		// prepare objects
		$objects = [];

		foreach ($rows as $k => &$row)
		{
			$objects[$k] = static::convertArrayToObject($row, true);
		}

		$entity = static::getEntity();
		$result = new AddResult();

		try
		{
			// call onBeforeEvent
			if (!$ignoreEvents)
			{
				foreach ($objects as $k => $object)
				{
					static::callOnBeforeAddEvent($object, $rows[$k], $result);
				}
			}

			// collect array data
			$allFields = [];
			$allUfData = [];

			foreach ($objects as $k => $object)
			{
				// actualize old-style fields array from object
				$allFields[$k] = $object->collectValues(Values::CURRENT, FieldTypeMask::SCALAR);

				// uf values
				$allUfData[$k] = $object->collectValues(Values::CURRENT, FieldTypeMask::USERTYPE);
			}

			// check data and uf
			foreach ($objects as $k => $object)
			{
				$fields = $allFields[$k];
				$ufdata = $allUfData[$k];

				// check data
				static::checkFields($result, null, $fields);

				// check uf data
				if (!empty($ufdata))
				{
					static::checkUfFields($object, $ufdata, $result);
				}

				// check if there is still some data
				if (!count($fields + $ufdata))
				{
					$result->addError(new EntityError("There is no data to add."));
				}
			}

			// return if any error in any row
			if (!$result->isSuccess(true))
			{
				return $result;
			}

			//event on adding
			if (!$ignoreEvents)
			{
				foreach ($objects as $k => $object)
				{
					$fields = $allFields[$k];
					$ufdata = $allUfData[$k];

					self::callOnAddEvent($object, $fields, $ufdata);
				}
			}

			// prepare sql
			$allSqlData = [];

			foreach ($allFields as $fields)
			{
				// use save modifiers
				$fieldsToDb = $fields;

				foreach ($fieldsToDb as $fieldName => $value)
				{
					$field = $entity->getField($fieldName);
					$fieldsToDb[$fieldName] = $field->modifyValueBeforeSave($value, $fields);
				}

				$dataReplacedColumn = static::replaceFieldName($fieldsToDb);

				$allSqlData[] = $dataReplacedColumn;
			}

			// save data
			$connection = $entity->getConnection();

			$tableName = $entity->getDBTableName();
			$identity = $entity->getAutoIncrement();

			// multi insert on db level
			$id = $connection->addMulti($tableName, $allSqlData, $identity);

			if (count($allSqlData) > 1)
			{
				// id doesn't make sense when multiple inserts
				$id = null;
			}
			else
			{
				$object = $objects[0];
				$fields = $allFields[0];

				// build standard primary
				$primary = null;

				if (!empty($id))
				{
					if (strlen($entity->getAutoIncrement()))
					{
						$primary = array($entity->getAutoIncrement() => $id);
						static::normalizePrimary($primary);
					}
					else
					{
						// for those who did not set 'autocomplete' flag but want to get id from result
						$primary = array('ID' => $id);
					}
				}
				else
				{
					static::normalizePrimary($primary, $fields);
				}

				// fill result
				$result->setPrimary($primary);
				$result->setData($fields);
				$result->setObject($object);
			}

			// save uf data
			foreach ($allUfData as $ufdata)
			{
				if (!empty($ufdata))
				{
					$USER_FIELD_MANAGER->update($entity->getUfId(), end($primary), $ufdata);
				}
			}

			$entity->cleanCache();

			// after event
			if (!$ignoreEvents)
			{
				foreach ($objects as $k => $object)
				{
					$fields = $allFields[$k];

					static::callOnAfterAddEvent($object, $fields, $id);
				}
			}
		}
		catch (\Exception $e)
		{
			// check result to avoid warning
			$result->isSuccess();

			throw $e;
		}

		return $result;
	}

	/**
	 * Updates row in entity table by primary key
	 *
	 * @param mixed $primary
	 * @param array $data An array with fields like
	 * 	array(
	 * 		"fields" => array(
	 * 			"FIELD1" => "value1",
	 * 			"FIELD2" => "value2",
	 * 		),
	 * 		"auth_context" => \Bitrix\Main\Authentication\Context object
	 *	)
	 *	or just a plain array of fields.
	 *
	 * @return UpdateResult
	 *
	 * @throws \Exception
	 */
	public static function update($primary, array $data)
	{
		global $USER_FIELD_MANAGER;

		// check primary
		static::normalizePrimary(
			$primary, isset($fields["fields"]) && is_array($data["fields"]) ? $data["fields"] : $data
		);
		static::validatePrimary($primary);

		// compatibility
		$fields = $data;

		/** @var EntityObject $object prepare entity object for compatibility with new code */
		$object = static::convertArrayToObject($fields, false, $primary);

		$entity = static::getEntity();
		$result = new UpdateResult();

		try
		{
			static::callOnBeforeUpdateEvent($object, $fields, $result);

			// actualize old-style fields array from object
			$fields = $object->collectValues(Values::CURRENT, FieldTypeMask::SCALAR);

			// uf values
			$ufdata = $object->collectValues(Values::CURRENT, FieldTypeMask::USERTYPE);

			// check data
			static::checkFields($result, $primary, $fields);

			// check uf data
			if (!empty($ufdata))
			{
				static::checkUfFields($object, $ufdata, $result);
			}

			// check if there is still some data
			if (!count($fields + $ufdata))
			{
				$result->addError(new EntityError("There is no data to update."));
			}

			// return if any error
			if (!$result->isSuccess(true))
			{
				return $result;
			}

			static::callOnUpdateEvent($object, $fields, $ufdata);

			// use save modifiers
			$fieldsToDb = $fields;

			foreach ($fieldsToDb as $fieldName => $value)
			{
				$field = $entity->getField($fieldName);
				$fieldsToDb[$fieldName] = $field->modifyValueBeforeSave($value, $fields);
			}

			// save data
			if (!empty($fieldsToDb))
			{
				$connection = $entity->getConnection();
				$helper = $connection->getSqlHelper();

				$tableName = $entity->getDBTableName();

				$dataReplacedColumn = static::replaceFieldName($fieldsToDb);
				$update = $helper->prepareUpdate($tableName, $dataReplacedColumn);

				$replacedPrimary = static::replaceFieldName($primary);
				$id = array();
				foreach ($replacedPrimary as $k => $v)
				{
					$id[] = $helper->prepareAssignment($tableName, $k, $v);
				}
				$where = implode(' AND ', $id);

				$sql = "UPDATE ".$helper->quote($tableName)." SET ".$update[0]." WHERE ".$where;
				$connection->queryExecute($sql, $update[1]);

				$result->setAffectedRowsCount($connection);
			}

			$result->setData($fields);
			$result->setPrimary($primary);
			$result->setObject($object);

			// save uf data
			if (!empty($ufdata))
			{
				$USER_FIELD_MANAGER->update($entity->getUfId(), end($primary), $ufdata);
			}

			$entity->cleanCache();

			// event after update
			static::callOnAfterUpdateEvent($object, $fields);
		}
		catch (\Exception $e)
		{
			// check result to avoid warning
			$result->isSuccess();

			throw $e;
		}

		return $result;
	}

	/**
	 * @param array $primaries
	 * @param array $data
	 * @param bool  $ignoreEvents
	 *
	 * @return UpdateResult
	 * @throws Main\ArgumentException
	 * @throws Main\SystemException
	 */
	public static function updateMulti($primaries, $data, $ignoreEvents = false)
	{
		$entity = static::getEntity();
		$primaries = array_values($primaries);

		/** @var EntityObject[] $objects */
		$objects = [];

		foreach ($primaries as &$primary)
		{
			static::normalizePrimary($primary, $data);
			static::validatePrimary($primary);

			/** @var EntityObject $object */
			$object = $entity->wakeUpObject($primary);

			foreach ($data as $k => $v)
			{
				$object->set($k, $v);
			}

			$objects[] = $object;
		}

		$result = new UpdateResult;

		try
		{
			// before event
			if (!$ignoreEvents)
			{
				foreach ($objects as $object)
				{
					static::callOnBeforeUpdateEvent($object, $data, $result);
				}
			}

			// collect array data
			$allFields = [];
			$allUfData = [];

			foreach ($objects as $k => $object)
			{
				// actualize old-style fields array from object
				$allFields[$k] = $object->collectValues(Values::CURRENT, FieldTypeMask::SCALAR);

				// uf values
				$allUfData[$k] = $object->collectValues(Values::CURRENT, FieldTypeMask::USERTYPE);
			}

			// check data and uf
			foreach ($objects as $k => $object)
			{
				$fields = $allFields[$k];
				$ufdata = $allUfData[$k];

				// check data
				static::checkFields($result, $object->primary, $fields);

				// check uf data
				if (!empty($ufdata))
				{
					static::checkUfFields($object, $ufdata, $result);
				}

				// check if there is still some data
				if (!count($fields + $ufdata))
				{
					$result->addError(new EntityError("There is no data to add."));
				}
			}

			// return if any error in any row
			if (!$result->isSuccess(true))
			{
				return $result;
			}

			//event on adding
			if (!$ignoreEvents)
			{
				foreach ($objects as $k => $object)
				{
					$fields = $allFields[$k];
					$ufdata = $allUfData[$k];

					static::callOnUpdateEvent($object, $fields, $ufdata);
				}
			}

			// prepare sql
			$allSqlData = [];

			foreach ($allFields as $k => $fields)
			{
				// use save modifiers
				$fieldsToDb = $fields;

				foreach ($fieldsToDb as $fieldName => $value)
				{
					$field = $entity->getField($fieldName);
					$fieldsToDb[$fieldName] = $field->modifyValueBeforeSave($value, $fields);
				}

				$dataReplacedColumn = static::replaceFieldName($fieldsToDb);

				$allSqlData[$k] = $dataReplacedColumn;
			}

			// check if rows data are equal
			$areEqual = true;

			$dataSample = $allSqlData[0];
			asort($dataSample);

			foreach ($allSqlData as $data)
			{
				asort($data);

				if ($data !== $dataSample)
				{
					$areEqual = false;
					break;
				}
			}

			// save data
			$connection = $entity->getConnection();
			$helper = $connection->getSqlHelper();
			$tableName = $entity->getDBTableName();

			// save data
			if ($areEqual)
			{
				// one query
				$update = $helper->prepareUpdate($tableName, $dataSample);
				$where = [];

				// for single primary IN is better
				foreach ($allSqlData as $k => $data)
				{
					$replacedPrimary = static::replaceFieldName($objects[$k]->primary);

					$id = [];

					foreach ($replacedPrimary as $primaryName => $primaryValue)
					{
						$id[] = $helper->prepareAssignment($tableName, $primaryName, $primaryValue);
					}
					$where[] = implode(' AND ', $id);
				}

				$where = '('.join(') OR (', $where).')';

				$sql = "UPDATE ".$helper->quote($tableName)." SET ".$update[0]." WHERE ".$where;
				$connection->queryExecute($sql, $update[1]);

				$result->setAffectedRowsCount($connection);
			}
			else
			{
				// query for each row
				foreach ($allSqlData as $k => $dataReplacedColumn)
				{
					$update = $helper->prepareUpdate($tableName, $dataReplacedColumn);

					$replacedPrimary = static::replaceFieldName($objects[$k]->primary);

					$id = [];

					foreach ($replacedPrimary as $primaryName => $primaryValue)
					{
						$id[] = $helper->prepareAssignment($tableName, $primaryName, $primaryValue);
					}
					$where = implode(' AND ', $id);

					$sql = "UPDATE ".$helper->quote($tableName)." SET ".$update[0]." WHERE ".$where;
					$connection->queryExecute($sql, $update[1]);

					$result->setAffectedRowsCount($connection);
				}
			}

			// doesn't make sense for multiple rows
			$result->setData($dataSample);

			if (count($allSqlData) == 1)
			{
				$result->setPrimary($objects[0]->primary);
				$result->setObject($objects[0]);
			}

			// save uf data
			foreach ($allUfData as $ufdata)
			{
				if (!empty($ufdata))
				{
					global $USER_FIELD_MANAGER;
					$USER_FIELD_MANAGER->update($entity->getUfId(), end($primary), $ufdata);
				}
			}

			$entity->cleanCache();

			// event after update
			if (!$ignoreEvents)
			{
				foreach ($objects as $k => $object)
				{
					$fields = $allFields[$k];

					static::callOnAfterUpdateEvent($object, $fields);
				}
			}
		}
		catch (\Exception $e)
		{
			// check result to avoid warning
			$result->isSuccess();

			throw $e;
		}

		return $result;
	}

	/**
	 * Deletes row in entity table by primary key
	 *
	 * @param mixed $primary
	 *
	 * @return DeleteResult
	 *
	 * @throws \Exception
	 */
	public static function delete($primary)
	{
		global $USER_FIELD_MANAGER;

		// check primary
		static::normalizePrimary($primary);
		static::validatePrimary($primary);

		$entity = static::getEntity();
		$result = new DeleteResult();

		try
		{
			//event before delete
			static::callOnBeforeDeleteEvent($primary, $entity, $result);

			// return if any error
			if (!$result->isSuccess(true))
			{
				return $result;
			}

			//event on delete
			static::callOnDeleteEvent($primary, $entity);

			// delete
			$connection = $entity->getConnection();
			$helper = $connection->getSqlHelper();

			$tableName = $entity->getDBTableName();

			$replacedPrimary = static::replaceFieldName($primary);
			$id = array();
			foreach ($replacedPrimary as $k => $v)
			{
				$id[] = $helper->prepareAssignment($tableName, $k, $v);
			}
			$where = implode(' AND ', $id);

			$sql = "DELETE FROM ".$helper->quote($tableName)." WHERE ".$where;
			$connection->queryExecute($sql);

			// delete uf data
			if ($entity->getUfId())
			{
				$USER_FIELD_MANAGER->delete($entity->getUfId(), end($primary));
			}

			$entity->cleanCache();

			//event after delete
			static::callOnAfterDeleteEvent($primary, $entity);
		}
		catch (\Exception $e)
		{
			// check result to avoid warning
			$result->isSuccess();

			throw $e;
		}

		return $result;
	}

	/**
	 * @param EntityObject $object
	 * @param              $fields
	 * @param              $result
	 */
	protected static function callOnBeforeAddEvent($object, $fields, $result)
	{
		//event before adding
		$event = new Event($object->entity, self::EVENT_ON_BEFORE_ADD, [
			'fields' => $fields,
			'object' => $object
		]);

		$event->send();
		$event->getErrors($result);
		$event->mergeObjectFields($object);

		//event before adding (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_BEFORE_ADD, [
			'fields' => $fields,
			'object' => $object
		], true);

		$event->send();
		$event->getErrors($result);
		$event->mergeObjectFields($object);
	}

	/**
	 * @param $object
	 * @param $fields
	 * @param $ufdata
	 */
	protected static function callOnAddEvent($object, $fields, $ufdata)
	{
		$event = new Event($object->entity, self::EVENT_ON_ADD, [
			'fields' => $fields + $ufdata,
			'object' => clone $object
		]);
		$event->send();

		//event on adding (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_ADD, [
			'fields' => $fields + $ufdata,
			'object' => clone $object
		], true);
		$event->send();
	}

	/**
	 * @param EntityObject $object
	 * @param array        $fields
	 * @param int          $id
	 */
	protected static function callOnAfterAddEvent($object, $fields, $id)
	{
		//event after adding
		$event = new Event($object->entity, self::EVENT_ON_AFTER_ADD, [
			'id' => $id,
			'fields' => $fields,
			'object' => clone $object
		]);
		$event->send();

		//event after adding (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_AFTER_ADD, [
			'id' => $id,
			'primary' => $object->primary,
			'fields' => $fields,
			'object' => clone $object
		], true);
		$event->send();
	}

	/**
	 * @param EntityObject $object
	 * @param              $fields
	 * @param              $result
	 */
	protected static function callOnBeforeUpdateEvent($object, $fields, $result)
	{
		$event = new Event($object->entity, self::EVENT_ON_BEFORE_UPDATE, [
			'id' => $object->primary,
			'fields' => $fields,
			'object' => $object
		]);

		$event->send();
		$event->getErrors($result);
		$event->mergeObjectFields($object);

		//event before update (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_BEFORE_UPDATE, [
			'id' => $object->primary,
			'primary' => $object->primary,
			'fields' => $fields,
			'object' => $object
		], true);

		$event->send();
		$event->getErrors($result);
		$event->mergeObjectFields($object);
	}

	/**
	 * @param EntityObject $object
	 * @param              $fields
	 * @param              $ufdata
	 */
	protected static function callOnUpdateEvent($object, $fields, $ufdata)
	{
		$event = new Event($object->entity, self::EVENT_ON_UPDATE, [
			'id' => $object->primary,
			'fields' => $fields + $ufdata,
			'object' => clone $object
		]);
		$event->send();

		//event on update (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_UPDATE, [
			'id' => $object->primary,
			'primary' => $object->primary,
			'fields' => $fields + $ufdata,
			'object' => clone $object
		], true);
		$event->send();
	}

	/**
	 * @param EntityObject $object
	 * @param              $fields
	 */
	protected static function callOnAfterUpdateEvent($object, $fields)
	{
		$event = new Event($object->entity, self::EVENT_ON_AFTER_UPDATE, [
			'id' => $object->primary,
			'fields' => $fields,
			'object' => clone $object
		]);
		$event->send();

		//event after update (modern with namespace)
		$event = new Event($object->entity, self::EVENT_ON_AFTER_UPDATE, [
			'id' => $object->primary,
			'primary' => $object->primary,
			'fields' => $fields,
			'object' => clone $object
		], true);
		$event->send();
	}

	/**
	 * @param $primary
	 * @param $entity
	 * @param $result
	 */
	protected static function callOnBeforeDeleteEvent($primary, $entity, $result)
	{
		$event = new Event($entity, self::EVENT_ON_BEFORE_DELETE, array("id" => $primary));
		$event->send();
		$event->getErrors($result);

		//event before delete (modern with namespace)
		$event = new Event($entity, self::EVENT_ON_BEFORE_DELETE, array("id" => $primary, "primary" => $primary), true);
		$event->send();
		$event->getErrors($result);
	}

	/**
	 * @param $primary
	 * @param $entity
	 */
	protected static function callOnDeleteEvent($primary, $entity)
	{
		$event = new Event($entity, self::EVENT_ON_DELETE, array("id" => $primary));
		$event->send();

		//event on delete (modern with namespace)
		$event = new Event($entity, self::EVENT_ON_DELETE, array("id" => $primary, "primary" => $primary), true);
		$event->send();
	}

	/**
	 * @param $primary
	 * @param $entity
	 */
	protected static function callOnAfterDeleteEvent($primary, $entity)
	{
		$event = new Event($entity, self::EVENT_ON_AFTER_DELETE, array("id" => $primary));
		$event->send();

		//event after delete (modern with namespace)
		$event = new Event($entity, self::EVENT_ON_AFTER_DELETE, array("id" => $primary, "primary" => $primary), true);
		$event->send();
	}

	/**
	 * Sets a flag indicating crypto support for a field.
	 *
	 * @param string $field
	 * @param string $table
	 * @param bool   $mode
	 *
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function enableCrypto($field, $table = null, $mode = true)
	{
		if($table === null)
		{
			$table = static::getTableName();
		}
		$options = array();
		$optionString = Main\Config\Option::get("main", "~crypto_".$table);
		if($optionString <> '')
		{
			$options = unserialize($optionString);
		}
		$options[strtoupper($field)] = $mode;
		Main\Config\Option::set("main", "~crypto_".$table, serialize($options));
	}

	/**
	 * Returns true if crypto is enabled for a field.
	 *
	 * @param string $field
	 * @param string $table
	 *
	 * @return bool
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function cryptoEnabled($field, $table = null)
	{
		if($table === null)
		{
			$table = static::getTableName();
		}
		$optionString = Main\Config\Option::get("main", "~crypto_".$table);
		if($optionString <> '')
		{
			$field = strtoupper($field);
			$options = unserialize($optionString);
			if(isset($options[$field]) && $options[$field] === true)
			{
				return true;
			}
		}
		return false;
	}

	/*
	An inheritor class can define the event handlers for own events.
	Why? To prevent from rewriting the add/update/delete functions.
	These handlers are triggered in the Bitrix\Main\ORM\Event::send() function
	*/
	public static function onBeforeAdd(Event $event){}
	public static function onAdd(Event $event){}
	public static function onAfterAdd(Event $event){}
	public static function onBeforeUpdate(Event $event){}
	public static function onUpdate(Event $event){}
	public static function onAfterUpdate(Event $event){}
	public static function onBeforeDelete(Event $event){}
	public static function onDelete(Event $event){}
	public static function onAfterDelete(Event $event){}
}
