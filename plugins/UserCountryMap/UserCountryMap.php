<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 *
 * @category Piwik_Plugins
 * @package Piwik_UserCountryMap
 */

/**
 *
 * @package Piwik_UserCountryMap
 */
class Piwik_UserCountryMap extends Piwik_Plugin
{
	public function getInformation()
	{
		return array(
			'name' => 'User Country Map',
			'description' => 'This plugin shows a zoomable world map of your visitors location.',
			'author' => 'Piwik',
			'author_homepage' => 'http://piwik.org/',
			'version' => Piwik_Version::VERSION
		);
	}

	function postLoad()
	{
		Piwik_AddWidget('General_Visitors', Piwik_Translate('UserCountry_WidgetCountries').' ('.Piwik_Translate('UserCountryMap_worldMap').')', 'UserCountryMap', 'worldMap');
	}
}

/**
 *
 * @package Piwik_UserCountryMap
 */
class Piwik_UserCountryMap_Controller extends Piwik_Controller
{
	function worldMap()
	{
		$view = Piwik_View::factory('worldmap');
		
		$view->dataUrl = "?module=API"
			. "&method=API.getProcessedReport&format=XML"
			. "&apiModule=UserCountry&apiAction=getCountry"
			. "&idSite=" . Piwik_Common::getRequestVar('idSite', 1, 'int')
			. "&period=" . Piwik_Common::getRequestVar('period')
			. "&date=" . Piwik_Common::getRequestVar('date')
			. "&token_auth=" . Piwik::getCurrentUserTokenAuth()
			. "&filter_limit=-1";
		
		// definition of the color scale
		$view->hueMin = 218; 	
		$view->hueMax = 216; 	
		$view->satMin = "0.285"; 	
		$view->satMax = "0.9";
		$view->lgtMin = ".97";
		$view->lgtMax = ".44";
		
		$request = new Piwik_API_Request(
			'method=API.getMetadata&format=PHP'
			. '&apiModule=UserCountry&apiAction=getCountry'
			. '&idSite=' . Piwik_Common::getRequestVar('idSite', 1, 'int')
			. '&period=' . Piwik_Common::getRequestVar('period')
			. '&date=' . Piwik_Common::getRequestVar('date')
			. '&token_auth=' . Piwik::getCurrentUserTokenAuth()
			. '&filter_limit=-1'
		);
		$metaData = $request->process();
		
		$metrics = array();
		foreach ($metaData[0]['metrics'] as $id => $val)
		{
			$metrics[] = array($id, $val);
		} 
		foreach ($metaData[0]['processedMetrics'] as $id => $val) 
		{
			$metrics[] = array($id, $val);
		}
		
		$view->metrics = $metrics;
		$view->defaultMetric = 'nb_visits';
		$view->version = Piwik_Version::VERSION;
		echo $view->render();
	}
	
	/*
	 * shows the traditional extra page where the user
	 * is able to download the exported image via right - click
	 *
	 * note: this is a fallback for older flashplayer versions
	 */
	function exportImage()
	{
		$view = Piwik_View::factory('exportImage');
		$view->imageData = 'data:image/png;base64,'.$_POST['imageData'];		
		echo $view->render();
	}
	
	/*
	 * this outputs the image straight forward and is directly called
	 * via flash download process
	 */
	function outputImage()
	{
		header('Content-Type: image/png');
		echo base64_decode($_POST['imagedata']);
		exit;
	}
	
	/*
	 * debug mode for worldmap
	 * helps to find JS bugs in IE8
	 */
	/*
	function debug()
	{
		echo '<html><head><title>DEBUG: world map</title>';
		echo '<script type="text/javascript" src="libs/jquery/jquery.js"></script>';
		echo '<script type="text/javascript" src="libs/swfobject/swfobject.js"></script>';
		echo '</head><body><div id="widgetUserCountryMapworldMap" style="width:600px;">';
		echo $this->worldMap();
		echo '</div></body></html>';
	} 
	// */
}
