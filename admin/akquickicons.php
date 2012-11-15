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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_akquickicons')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// init
include_once JPATH_COMPONENT_ADMINISTRATOR.'/includes/init.php';

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Akquickicons');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
