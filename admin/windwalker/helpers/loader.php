<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

 
// no direct access
defined('_JEXEC') or die;


class AKHelperLoader
{
	
	public static $files = array() ;
	
	/*
	 * function import
	 * @param $uri
	 */
	
	public static function import($uri, $option = null)
	{
		$key = $uri ;
		if( isset(self::$files[$key]) ){
			return true ;
		}
		
		$uri 	= explode( '://' , $uri ) ;
		$root 	= AKHelper::_('path.get', $uri[0], $option) ;
		$path 	= $root.'/'.$uri[1].'.php' ;
		
		if( JFile::exists($path) ){
			include_once $path ;
			self::$files[$key] = $path ;
			return true ;
		}else{
			return false ;
		}
	}
		
}