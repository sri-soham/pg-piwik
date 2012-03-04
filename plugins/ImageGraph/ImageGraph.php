<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 * 
 * @category Piwik_Plugins
 * @package Piwik_ImageGraph
 */

class Piwik_ImageGraph extends Piwik_Plugin
{

	public function getInformation()
	{
		return array(
			'description' => Piwik_Translate('ImageGraph_PluginDescription') 
					. ' Debug: <a href="'.Piwik_Url::getCurrentQueryStringWithParametersModified(
							array('module'=> 'ImageGraph', 'action' => 'index'))
					. '">All images</a>',
			'author' => 'Piwik',
			'author_homepage' => 'http://piwik.org/',
			'version' => Piwik_Version::VERSION
		);
	}
	
	function getListHooksRegistered()
	{
		$hooks = array(
			'API.getReportMetadata.end.end' => 'getReportMetadata',
		);
		return $hooks;
	}
	
	// Number of periods to plot on an evolution graph
	const GRAPH_EVOLUTION_LAST_PERIODS = 30;
	
	public function getReportMetadata($notification)
	{
		$info = $notification->getNotificationInfo();
		$reports = &$notification->getNotificationObject();
		$idSites = $info['idSites'];

		// If only one website is selected, we add the Graph URL
		if(count($idSites) != 1)
		{
			return;
		}
		$idSite = reset($idSites);
	
		// in case API.getReportMetadata was not called with date/period we use sane defaults 
		if(empty($info['period'])) 
		{
			$info['period'] = 'day';
		}
		if(empty($info['date'])) 
		{
			$info['date'] = 'today';
		}

		// need two sets of period & date, one for single period graphs, one for multiple periods graphs
		if(Piwik_Archive::isMultiplePeriod($info['date'], $info['period']))
		{
			$periodForMultiplePeriodGraph = $info['period'];
			$dateForMultiplePeriodGraph = $info['date'];

			$periodForSinglePeriodGraph = 'range';
			$dateForSinglePeriodGraph = $info['date'];
		}
		else
		{
			$periodForSinglePeriodGraph = $info['period'];
			$dateForSinglePeriodGraph = $info['date'];

			$piwikSite = new Piwik_Site($idSite);
			if($periodForSinglePeriodGraph == 'range')
			{
				$periodForMultiplePeriodGraph = Piwik_Config::getInstance()->General['graphs_default_period_to_plot_when_period_range'];
				$dateForMultiplePeriodGraph = $dateForSinglePeriodGraph;
			}
			else
			{
				$periodForMultiplePeriodGraph = $periodForSinglePeriodGraph;
				$dateForMultiplePeriodGraph = Piwik_Controller::getDateRangeRelativeToEndDate(
					$periodForSinglePeriodGraph,
					'last' . self::GRAPH_EVOLUTION_LAST_PERIODS,
					$dateForSinglePeriodGraph,
					$piwikSite
				);
			}
		}

		$token_auth = Piwik_Common::getRequestVar('token_auth', false);
		
		$urlPrefix = "index.php?";
		foreach($reports as &$report)
		{
			$parameters = array();
			$parameters['module'] = 'API';
			$parameters['method'] = 'ImageGraph.get';
			$parameters['idSite'] = $idSite;
			$parameters['apiModule'] = $report['module'];
			$parameters['apiAction'] = $report['action'];
			if(!empty($token_auth))
			{
				$parameters['token_auth'] = $token_auth;
			}

			// Forward custom Report parameters to the graph URL 
			if(!empty($report['parameters']))
			{
				$parameters = array_merge($parameters, $report['parameters']);
			}
			if(empty($report['dimension']))
			{
				$parameters['period'] = $periodForMultiplePeriodGraph;
				$parameters['date'] = $dateForMultiplePeriodGraph;
			}
			else
			{
				$parameters['period'] = $periodForSinglePeriodGraph;
				$parameters['date'] = $dateForSinglePeriodGraph;
			}
			
			$report['imageGraphUrl'] = $urlPrefix . Piwik_Url::getQueryStringFromParameters($parameters);
		}
			
	}
}
