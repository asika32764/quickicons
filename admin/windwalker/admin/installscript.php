<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  script
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

$db 	= JFactory::getDbo();


// Show Installed table
// ========================================================================
include_once $path.'/windwalker/html/grid.php';
$grid = new AKGrid();

$option['class'] = 'adminlist table table-striped table-bordered' ;
$option['style'] = JVERSION >=3 ? 'width: 750px;' : 'width: 80%; margin: 15px;' ;
$grid->setTableOptions($option);
$grid->setColumns( array('num', 'type', 'name', 'version', 'state', 'info') ) ;

$grid->addRow(array(), 1) ;
$grid->setRowCell('num', '#' , array());
$grid->setRowCell('type', JText::_('COM_INSTALLER_HEADING_TYPE') , array());
$grid->setRowCell('name', JText::_('COM_INSTALLER_HEADING_NAME') , array());
$grid->setRowCell('version', JText::_('JVERSION') , array());
$grid->setRowCell('state', JText::_('JSTATUS') , array());
$grid->setRowCell('info', JText::_('COM_INSTALLER_MSG_DATABASE_INFO') , array());


// Set cells
$i = 0 ;

if(JVERSION >= 3){
	$tick 	= '<i class="icon-publish"></i>' ;
	$cross 	= '<i class="icon-unpublish"></i>' ;
}else{
	$tick 	= '<img src="templates/bluestork/images/admin/tick.png" alt="Success" />' ;
	$cross 	= '<img src="templates/bluestork/images/admin/publish_y.png" alt="Fail" />' ;
}

$td_class = array('style' => 'text-align:center;') ;


// Set component install success info
$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
$grid->setRowCell('num', ++$i , $td_class);
$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_COMPONENT') , $td_class);
$grid->setRowCell('name', JText::_(strtoupper($manifest->name)) , array());
$grid->setRowCell('version', $manifest->version , $td_class);
$grid->setRowCell('state', $tick , $td_class);
$grid->setRowCell('info', '', array());



// Install WindWalker
// ========================================================================
// Do install
$installer 		= new JInstaller();
$install_path 	= $path.'/windwalker';
if($result[] = $installer->install($install_path)){
	$status = $tick ;
}else{
	$status = $cross ;
}
// Set success table
$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
$grid->setRowCell('num', ++$i , $td_class);
$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_LIBRARY') , $td_class);
$grid->setRowCell('name', JText::_('LIB_WINDWALKER') , array());
$grid->setRowCell('version', $installer->manifest->version , $td_class);
$grid->setRowCell('state', $status , $td_class);
$grid->setRowCell('info', JText::_($installer->manifest->description), array());


// Install modules
// ========================================================================
$modules 	= $manifest->modules ;

if(!empty($modules)){
	foreach( (array)$modules as $module ):
		
		if(!trim($module)) continue ;
		
		$module = is_array($module) ? $module : array($module) ;
		
		// Install per module
		foreach( $module as $var ):
			$install_path = $path.'/../modules/'.$var ;
			
			// Do install
			$installer = new JInstaller();
			if($result[] = $installer->install($install_path)){
				$status = $tick ;
			}else{
				$status = $cross ;
			}
			
			// Set success table
			$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
			$grid->setRowCell('num', ++$i , $td_class);
			$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_MODULE') , $td_class);
			$grid->setRowCell('name', JText::_(strtoupper($var)) , array());
			$grid->setRowCell('version', $installer->manifest->version , $td_class);
			$grid->setRowCell('state', $status , $td_class);
			$grid->setRowCell('info', JText::_($installer->manifest->description), array());
			
		endforeach;
		
	endforeach;
}



// Install plugins
// ========================================================================
$plugins 	= $manifest->plugins ;

if(!empty($plugins)){
	foreach( (array)$plugins as $plugin ):
		
		if(!trim($plugin)) continue ;
		
		$plugin = is_array($plugin) ? $plugin : array($plugin) ;
		
		// Install per plugin
		foreach( $plugin as $var ):
			$install_path = $path.'/../plugins/'.$var ;
			
			// Get plugin name
			$path 		= explode('/', $var) ;
			$plg_name 	= array_pop($path) ;
				
			if( substr( $plg_name,0 ,4 ) == 'plg_' ){
				$plg_name = substr( $plg_name, 4 ) ;
			}
			
			$plg_name	= explode('_', $plg_name) ;
			$plg_name	= $plg_name[1];
			
			
			// Do install
			$installer = new JInstaller();
			if( $result[] = $installer->install($install_path) ){
				
				$plg_group 	= (string) $installer->manifest['group'] ;
				
				// Enable this plugin.
				if($type == 'install'):
					$q = $db->getQuery(true) ;
					
					$q->update('#__extensions')
						->set("enabled = 1")
						->where("type = 'plugin'")
						->where("element = '{$plg_name}'")
						->where("folder = '{$plg_group}'")
						;
					
					$db->setQuery($q);
					$db->query();
				endif;
				
				$status = $tick ;
			}else{
				$status = $cross ;
			}
			
			// Set success table
			$grid->addRow(array( 'class' => 'row'.($i % 2) )) ;
			$grid->setRowCell('num', ++$i , $td_class);
			$grid->setRowCell('type', JText::_('COM_INSTALLER_TYPE_PLUGIN') , $td_class);
			$grid->setRowCell('name', JText::_($var) , array());
			$grid->setRowCell('version', $installer->manifest->version , $td_class);
			$grid->setRowCell('state', $status , $td_class);
			$grid->setRowCell('info', JText::_($installer->manifest->description), array());
			
		endforeach;
		
	endforeach;
}

// Render install information
echo '<h1>'.JText::_(strtoupper($manifest->name)).'</h1>' ;
$img = JURI::base().'/components/'.strtolower($manifest->name).'/images/'.strtolower($manifest->name).'_logo.png' ;
$img = JHtml::_('image', $img, 'LOGO' ) ;
$link = JRoute::_("index.php?option=".$manifest->name);
echo '<div id="ak-install-img">'.JHtml::link($link, $img).'</div>';
echo '<div id="ak-install-msg">'.JText::_( strtoupper($manifest->name).'_INSTALL_MSG' ).'</div>';
echo '<br /><br />';

echo $grid ;

