<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Script file of HelloWorld component
 */
class com_AkquickiconsInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		jimport('joomla.filesystem.file') ;
		jimport('joomla.filesystem.folder') ;
		
		$path = $parent->getPath('source');
		$installer = new JInstaller();
		echo $mod_path = $path.DS.'module' ;
		$result = $installer->install($mod_path);
		
		AK::show($result);
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		echo '123123' ;
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		echo '123123' ;
		AK::show($parent); jexit();
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		echo '123123' ;
		AK::show($parent); jexit();
	}
	
}

