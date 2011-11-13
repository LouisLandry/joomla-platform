<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Stream
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die();

/**
 * Joomla Stream Interface.  This is the minimal interface to implement a userland stream wrapper.
 *
 * @package     Joomla.Platform
 * @subpackage  Stream
 * @since       11.3
 */
class JStreamStorageApc extends JStreamStorage
{
	/**
	 * Method to add a storage entry.
	 *
	 * @param   string   $key
	 * @param   string   $value
	 * @param   integer  $ttl
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function addStorageEntry($key, $value, $ttl)
	{
		// Add the time value.
		apc_add($key.'_time', time(), $ttl+1);

		return apc_add($key, $value, $ttl);
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key
	 *
	 * @return  string
	 *
	 * @since   11.3
	 */
	protected function getStorageEntry($key)
	{
		return apc_fetch($key);
	}

	/**
	 * Method to get information about a storage entry for a key.
	 *
	 * @param   string  $key
	 *
	 * @return  array
	 *
	 * @since   11.3
	 */
	protected function getStorageEntryInfo($key)
	{
		return array(
			'size' => (int) strlen(apc_fetch($key)),
			'time' => (int) apc_fetch($key.'_time')
		);
	}

	/**
	 * Method to determine whether a storage entry has been set for a key.
	 *
	 * @param   string  $key
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function isStorageEntrySet($key)
	{
		return apc_exists($key);
	}

	/**
	 * Method to get a lock on a storage entry.  We are going to bluff here so that locking can be
	 * used by other storage wrapper types consistently.  This shoiuldn't be any sort of issue
	 * because we expect APC to be fairly atomic in writes.
	 *
	 * @param   string   $key
	 * @param   boolean  $exclusive
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function lockStorageEntry($key, $exclusive = false)
	{
		return true;
	}

	/**
	 * Method to remove a storage entry for a key.
	 *
	 * @param   string  $key
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function removeStorageEntry($key)
	{
		// Delete the time value.
		apc_delete($key.'_time');

		return apc_delete($key);
	}

	/**
	 * Method to set a value for a storage entry.
	 *
	 * @param   string   $key
	 * @param   string   $value
	 * @param   integer  $ttl
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function setStorageEntry($key, $value, $ttl)
	{
		// Add the time value.
		apc_store($key.'_time', time(), $ttl+1);

		return apc_store($key, $value, $ttl);
	}

	/**
	 * Method to release a lock on a storage entry.  We are going to bluff here so that locking can be
	 * used by other storage wrapper types consistently.  This shoiuldn't be any sort of issue
	 * because we expect APC to be fairly atomic in writes.
	 *
	 * @param   string   $key
	 *
	 * @return  boolean
	 *
	 * @since   11.3
	 */
	protected function unlockStorageEntry($key)
	{
		return true;
	}
}