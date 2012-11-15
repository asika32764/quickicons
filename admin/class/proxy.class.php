<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_akquickicons
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

class AKProxy
{
 
    /**
     * An array to hold included paths
     *
     * @var    array 
     * @since  11.1
     */
    protected static $includePaths = array();
 
    /**
     * An array to hold method references
     *
     * @var    array 
     * @since  11.1
     */
    protected static $registry = array();
	
	protected static $prefix = 'AKProxy' ;
	
    /**
     * Method to extract a key
     *
     * @param   string  $key  The name of helper method to load, (prefix).(class).function
     *                         prefix and class are optional and can be used to load custom html helpers.
     *
     * @return  array  Contains lowercase key, prefix, file, function.
     *
     * @since   11.1
     */
    protected static function extract($key)
    {
        $key = preg_replace('#[^A-Z0-9_\.]#i', '', $key);
 
        // Check to see whether we need to load a helper file
        $parts = explode('.', $key);
 
        $prefix = (count($parts) == 3 ? array_shift($parts) : self::$prefix);
        $file = (count($parts) == 2 ? array_shift($parts) : '');
        $func = array_shift($parts);
 
        return array(strtolower($prefix . '.' . $file . '.' . $func), $prefix, $file, $func);
    }
 
    /**
     * Class loader method
     *
     * Additional arguments may be supplied and are passed to the sub-class.
     * Additional include paths are also able to be specified for third-party use
     *
     * @param   string  $key  The name of helper method to load, (prefix).(class).function
     *                         prefix and class are optional and can be used to load custom
     *                         html helpers.
     *
     * @return  mixed  self::call($function, $args) or False on error
     *
     * @since   11.1
     */
    public static function _($key)
    {
        list($key, $prefix, $file, $func) = self::extract($key);
        if (array_key_exists($key, self::$registry))
        {
            $function = self::$registry[$key];
            $args = func_get_args();
            // Remove function name from arguments
            array_shift($args);
            return self::call($function, $args);
        }
 
        $className = $prefix . ucfirst($file);
 
        if (!class_exists($className))
        {
            jimport('joomla.filesystem.path');
            if ($path = JPath::find(self::$includePaths[$prefix], strtolower($file) . '.php'))
            {
                require_once $path;
 
                if (!class_exists($className))
                {
                    JError::raiseError(500, JText::sprintf('JLIB_HTML_ERROR_NOTFOUNDINFILE', $className, $func));
                    return false;
                }
            }
            else
            {
                JError::raiseError(500, JText::sprintf('JLIB_HTML_ERROR_NOTSUPPORTED_NOFILE', $prefix, $file));
                return false;
            }
        }
 
        $toCall = array($className, $func);
        if (is_callable($toCall))
        {
            self::register($key, $toCall);
            $args = func_get_args();
            // Remove function name from arguments
            array_shift($args);
            return self::call($toCall, $args);
        }
        else
        {
            JError::raiseError(500, JText::sprintf('JLIB_HTML_ERROR_NOTSUPPORTED', $className, $func));
            return false;
        }
    }
 
    /**
     * Registers a function to be called with a specific key
     *
     * @param   string  $key       The name of the key
     * @param   string  $function  Function or method
     *
     * @return  boolean  True if the function is callable
     *
     * @since   11.1
     */
    public static function register($key, $function)
    {
        list($key) = self::extract($key);
        if (is_callable($function))
        {
            self::$registry[$key] = $function;
            return true;
        }
        return false;
    }
 
    /**
     * Removes a key for a method from registry.
     *
     * @param   string  $key  The name of the key
     *
     * @return  boolean  True if a set key is unset
     *
     * @since   11.1
     */
    public static function unregister($key)
    {
        list($key) = self::extract($key);
        if (isset(self::$registry[$key]))
        {
            unset(self::$registry[$key]);
            return true;
        }
 
        return false;
    }
 
    /**
     * Test if the key is registered.
     *
     * @param   string  $key  The name of the key
     *
     * @return  boolean  True if the key is registered.
     *
     * @since   11.1
     */
    public static function isRegistered($key)
    {
        list($key) = self::extract($key);
        return isset(self::$registry[$key]);
    }
 
    /**
     * Function caller method
     *
     * @param   string  $function  Function or method to call
     * @param   array   $args      Arguments to be passed to function
     *
     * @return  mixed   Function result or false on error.
     *
     * @see     http://php.net/manual/en/function.call-user-func-array.php
     * @since   11.1
     */
    protected static function call($function, $args)
    {
        if (is_callable($function))
        {
            // PHP 5.3 workaround
            $temp = array();
            foreach ($args as &$arg)
            {
                $temp[] = &$arg;
            }
            return call_user_func_array($function, $temp);
        }
        else
        {
            JError::raiseError(500, JText::_('JLIB_HTML_ERROR_FUNCTION_NOT_SUPPORTED'));
            return false;
        }
    }
 
    /**
     * Add a directory where self should search for helpers. You may
     * either pass a string or an array of directories.
     *
     * @param   string  $path  A path to search.
     *
     * @return  array  An array with directory elements
     *
     * @since   11.1
     */
    public static function addIncludePath($path = '', $prefix = null)
    {
        // Force path to array
        settype($path, 'array');
		
		$prefix = $prefix ? $prefix : self::getPrefix();
		
        // Loop through the path directories
        foreach ($path as $dir)
        {
            if (!empty($dir) && !in_array($dir, self::$includePaths[$prefix]))
            {
                jimport('joomla.filesystem.path');
                array_unshift(self::$includePaths[$prefix], JPath::clean($dir));
            }
        }
 
        return self::$includePaths[$prefix];
    }
	
	
	/*
	 * function setPrefix
	 * @param $prefix
	 */
	
	public static function setPrefix($prefix)
	{
		self::$prefix = $prefix ;
		self::$includePaths[$prefix] = array();
	}
	
	
	/*
	 * function getPrefix
	 * @param 
	 */
	
	public static function getPrefix()
	{
		return self::$prefix ;
	}
}
