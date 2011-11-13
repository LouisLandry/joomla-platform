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
 * Joomla Stream Directory Interface.  This interface adds directory functionality to a userland
 * stream wrapper which is used to read a directory.
 *
 * @package     Joomla.Platform
 * @subpackage  Stream
 * @since       11.3
 */
interface JStreamDirectory
{
	/**
	 * Method to close a directory handle.  This method is called in response to closedir().  Any
	 * resources which were locked, or allocated, during opening and use of the directory stream
	 * should be released.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     closedir()
	 * @since   11.3
	 */
	public function dir_closedir();

	/**
	 * Method to open a directory handle.  This method is called in response to opendir().
	 *
	 * @param   string   $path     The URL for the resource to be opened as a directory handle.
	 * @param   integer  $options  A bitwise mask of options for the directory handle.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     opendir()
	 * @since   11.3
	 */
	public function dir_opendir($path, $options);

	/**
	 * Method to read the next entry from a directory handle.  This method is called in response
	 * to readdir().
	 *
	 * @return  mixed  String representing the next filename or boolean false if there is no next file.
	 *
	 * @see     readdir()
	 * @since   11.3
	 */
	public function dir_readdir();

	/**
	 * Method to rewind a directory handle.  This method is called in response to rewinddir().  Should
	 * reset the output generated by self::dir_readdir(). i.e.: The next call to self::dir_readdir()
	 * should return the first entry in the location returned by self::dir_opendir().
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     rewinddir()
	 * @since   11.3
	 */
	public function dir_rewinddir();
}
