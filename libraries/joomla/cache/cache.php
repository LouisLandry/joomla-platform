<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Cache
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * A base caching class to enable the storage of persistent and non-persistent data.
 *
 * @package     Joomla.Platform
 * @subpackage  Cache
 * @since       13.1
 */
abstract class JCache
{
	/**
	 * An array of key/value pairs to be used as a non-persistent, runtime cache.
	 *
	 * @var    array
	 * @since  13.1
	 */
	static protected $runtime = array();

	/**
	 * The options for the cache object.
	 *
	 * @var    JRegistry
	 * @since  13.1
	 */
	protected $options;

	/**
	 * The class constructor.
	 *
	 * @param   JRegistry  $options  The caching options. The standard options are as follows:
	 *                               ttl - the number of seconds before the stored data expires (default = 900).
	 *                               runtime - a boolean flag to enabled non-persistent caching (default = true).
	 *
	 * @since   13.1
	 * @throws  RuntimeException if the cache handler is not supported.
	 */
	public function __construct(JRegistry $options = null)
	{
		// Set the options object.
		$this->options = $options ? $options : new JRegistry;

		$this->options->def('ttl', 900);
		$this->options->def('runtime', true);
	}

	/**
	 * Gets a caching option.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The value of the option.
	 *
	 * @since   13.1
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Sets a caching option.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JCache  Returns itself to allow chaining.
	 *
	 * @since   13.1
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}

	/**
	 * Gets cached data by identifier under which it was stored. If the cached data has expired then the cached data will be
	 * removed and false will be returned.
	 *
	 * @param   string   $cacheId       The identifier of the cached data.
	 * @param   boolean  $checkRuntime  An optional flag to check runtime cache first (default = true).
	 *
	 * @return  mixed  Cached data string if it exists.
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	public function get($cacheId, $checkRuntime = true)
	{
		if ($checkRuntime && isset(self::$runtime[$cacheId]) && $this->options->get('runtime'))
		{
			return self::$runtime[$cacheId];
		}

		$data = $this->fetch($cacheId);

		if ($this->options->get('runtime'))
		{
			self::$runtime[$cacheId] = $data;
		}

		return $data;
	}

	/**
	 * Stores the cached data under an identifier.
	 *
	 * @param   string  $cacheId  The identifier for the cache data.
	 * @param   mixed   $data     The data to store.
	 *
	 * @return  JCache  Returns itself to allow chaining.
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	public function store($cacheId, $data)
	{
		if ($this->exists($cacheId))
		{
			$this->set($cacheId, $data, $this->options->get('ttl'));
		}
		else
		{
			$this->add($cacheId, $data, $this->options->get('ttl'));
		}

		if ($this->options->get('runtime'))
		{
			self::$runtime[$cacheId] = $data;
		}

		return $this;
	}

	/**
	 * Removes a cached data entry by the identifier under which it was stored.
	 *
	 * @param   string  $cacheId  The identifier of the cache data.
	 *
	 * @return  JCache  Returns itself to allow chaining.
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	public function remove($cacheId)
	{
		$this->delete($cacheId);

		if ($this->options->get('runtime'))
		{
			unset(self::$runtime[$cacheId]);
		}

		return $this;
	}

	/**
	 * Adds a storage entry.
	 *
	 * @param   string   $key    The identifier of the storage entry.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  void
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	abstract protected function add($key, $value, $ttl);

	/**
	 * Determines whether a storage entry has been set for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean
	 *
	 * @since   13.1
	 */
	abstract protected function exists($key);

	/**
	 * Gets a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  mixed
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	abstract protected function fetch($key);

	/**
	 * Removes a storage entry for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  void
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	abstract protected function delete($key);

	/**
	 * Sets a value for a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  void
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	abstract protected function set($key, $value, $ttl);
}
