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
 * XCache cache driver for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Cache
 * @since       13.1
 */
class JCacheXCache extends JCache
{
	/**
	 * The class constructor.
	 *
	 * @param   JRegistry  $options  Caching options object.
	 *
	 * @since   13.1
	 * @throws  RuntimeException
	 */
	public function __construct(JRegistry $options = null)
	{
		parent::__construct($options);

		if (!extension_loaded('xcache') || !is_callable('xcache_get'))
		{
			throw new RuntimeException('XCache not supported.');
		}
	}

	/**
	 * Adds a storage entry.  XCache doesn't have a separate add function so we'll just
	 * use the set function instead.
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
	protected function add($key, $value, $ttl)
	{
		if (!xcache_set($key, $value, $ttl))
		{
			throw new RuntimeException(sprintf('Unable to add cache entry for %s.', $key));
		}
	}

	/**
	 * Determines whether a storage entry has been set for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean
	 *
	 * @since   13.1
	 */
	protected function exists($key)
	{
		return xcache_isset($key);
	}

	/**
	 * Gets a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  mixed
	 *
	 * @since   13.1
	 */
	protected function fetch($key)
	{
		return xcache_get($key);
	}

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
	protected function delete($key)
	{
		if (!xcache_unset($key))
		{
			throw new RuntimeException(sprintf('Unable to remove cache entry for %s.', $key));
		}
	}

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
	protected function set($key, $value, $ttl)
	{
		if (!xcache_set($key, $value, $ttl))
		{
			throw new RuntimeException(sprintf('Unable to set cache entry for %s.', $key));
		}
	}
}
