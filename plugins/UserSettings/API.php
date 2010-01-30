<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 * 
 * @category Piwik_Plugins
 * @package Piwik_UserSettings
 */

// no direct access
defined('PIWIK_INCLUDE_PATH') or die;

/**
 * @see plugins/UserSettings/functions.php
 */
require_once PIWIK_INCLUDE_PATH . '/plugins/UserSettings/functions.php';

/**
 *
 * @package Piwik_UserSettings
 */
class Piwik_UserSettings_API 
{
	static private $instance = null;
	static public function getInstance()
	{
		if (self::$instance == null)
		{            
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	static protected function getDataTable($name, $idSite, $period, $date)
	{
		Piwik::checkUserHasViewAccess( $idSite );
		$archive = Piwik_Archive::build($idSite, $period, $date );
		$dataTable = $archive->getDataTable($name);
		$dataTable->filter('Sort', array(Piwik_Archive::INDEX_NB_VISITS));
		$dataTable->queueFilter('ReplaceColumnNames');
		return $dataTable;
	}

	static public function getResolution( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_resolution', $idSite, $period, $date);
		return $dataTable;
	}

	static public function getConfiguration( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_configuration', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackReplace', array('label', 'Piwik_getConfigurationLabel'));
		return $dataTable;
	}

	static public function getOS( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_os', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'logo', 'Piwik_getOSLogo'));
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array( 'label', 'shortLabel', 'Piwik_getOSShortLabel') );
		$dataTable->queueFilter('ColumnCallbackReplace', array( 'label', 'Piwik_getOSLabel') );
		return $dataTable;
	}
		
	static public function getBrowser( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_browser', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'logo', 'Piwik_getBrowsersLogo'));
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'shortLabel', 'Piwik_getBrowserShortLabel'));
		$dataTable->queueFilter('ColumnCallbackReplace', array('label', 'Piwik_getBrowserLabel'));
		return $dataTable;
	}
	
	static public function getBrowserType( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_browserType', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'shortLabel', 'ucfirst'));
		$dataTable->queueFilter('ColumnCallbackReplace', array('label', 'Piwik_getBrowserTypeLabel'));
		return $dataTable;
	}
	
	static public function getWideScreen( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_wideScreen', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'logo', 'Piwik_getScreensLogo'));
		$dataTable->queueFilter('ColumnCallbackReplace', array('label', 'ucfirst'));
		return $dataTable;
	}
	
	static public function getPlugin( $idSite, $period, $date )
	{
		$dataTable = self::getDataTable('UserSettings_plugin', $idSite, $period, $date);
		$dataTable->queueFilter('ColumnCallbackAddMetadata', array('label', 'logo', 'Piwik_getPluginsLogo'));
		$dataTable->queueFilter('ColumnCallbackReplace', array('label', 'ucfirst'));
		return $dataTable;
	}	
}
