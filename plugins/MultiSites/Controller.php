<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 * 
 * @category Piwik_Plugins
 * @package Piwik_MultiSites
 */

/**
 *
 * @package Piwik_MultiSites
 */
class Piwik_MultiSites_Controller extends Piwik_Controller
{
	protected $orderBy = 'visits';
	protected $order = 'desc';
	protected $evolutionBy = 'visits';
	protected $page = 1;
	protected $limit = 0;
	protected $period;
	protected $date;

	function __construct()
	{
		parent::__construct();
		
		$this->limit = Zend_Registry::get('config')->General->all_websites_website_per_page;
	}

	function index()
	{
		$this->getSitesInfo();
	}


	public function getSitesInfo()
	{
		Piwik::checkUserHasSomeViewAccess();
		$displayRevenueColumn = Piwik_Common::isGoalPluginEnabled();
		
		// overwrites the default Date set in the parent controller 
		// Instead of the default current website's local date, 
		// we set "today" or "yesterday" based on the default Piwik timezone
		$piwikDefaultTimezone = Piwik_SitesManager_API::getInstance()->getDefaultTimezone();
		$dateRequest = Piwik_Common::getRequestVar('date', 'today');
		$period = Piwik_Common::getRequestVar('period', 'day');	
		$date = $dateRequest;
		if($period != 'range')
		{
			$date = $this->getDateParameterInTimezone($dateRequest, $piwikDefaultTimezone);
			$date = $date->toString();
		}
		$siteIds = Piwik_SitesManager_API::getInstance()->getSitesIdWithAtLeastViewAccess();
		$dataTable = Piwik_MultiSites_API::getInstance()->getAll($period, $date, $segment = false);

		list($minDate, $maxDate) = $this->getMinMaxDateAcrossWebsites($siteIds);
		
		$totalVisits = $totalActions = $totalRevenue = 0;

		// put data into a form the template will understand better
		$digestableData = array();
		foreach($siteIds as $idSite)
		{
			$isEcommerceEnabled = Piwik_Site::isEcommerceEnabledFor($idSite);

			$digestableData[$idSite] = array(
				'idsite' => $idSite,
				'main_url' => Piwik_Site::getMainUrlFor($idSite),
				'name' => Piwik_Site::getNameFor($idSite),
				'visits' => 0,
				'actions' => 0
			);
			
			if ($period != 'range')
			{
				$digestableData[$idSite]['visits_evolution'] = 0;
				$digestableData[$idSite]['actions_evolution'] = 0;
			}
			
			if ($displayRevenueColumn)
			{
				$revenueDefault = $isEcommerceEnabled ? 0 : "'-'";
			
				if ($period != 'range')
				{
					$digestableData[$idSite]['revenue_evolution'] = $revenueDefault;
				}
			}
		}

		foreach($dataTable->getRows() as $row)
		{
			$idsite = (int)$row->getMetadata('idsite');
			
			$site = &$digestableData[$idsite];
			
			$site['visits'] = $row->getColumn('nb_visits');
			$totalVisits += $site['visits'];

			$site['actions'] = $row->getColumn('nb_actions');
			$totalActions += $site['actions'];

			if ($displayRevenueColumn)
			{
				if ($row->getColumn('revenue') !== false)
				{
					$site['revenue'] = $row->getColumn('revenue');
					$totalRevenue += $site['revenue'];
				}
			}

			if ($period != 'range')
			{
				$site['visits_evolution'] = $row->getColumn('visits_evolution');
				$site['actions_evolution'] = $row->getColumn('actions_evolution');
				
				if ($displayRevenueColumn)
				{
					$site['revenue_evolution'] = $row->getColumn('revenue_evolution');
				}
			}
		}
		
		$this->applyPrettyMoney($digestableData);

		$view = new Piwik_View("MultiSites/templates/index.tpl");
		$view->sitesData = array_values($digestableData);
		$view->evolutionBy = $this->evolutionBy;
		$view->period = $period;
		$view->dateRequest = $dateRequest;
		$view->page = $this->page;
		$view->limit = $this->limit;
		$view->orderBy = $this->orderBy;
		$view->order = $this->order;
		$view->totalVisits = $totalVisits;
		$view->totalRevenue = $totalRevenue;
		$view->displayRevenueColumn = $displayRevenueColumn;
		$view->totalActions = $totalActions;
	
		$params = $this->getGraphParamsModified();
		$view->dateSparkline = $period == 'range' ? $dateRequest : $params['date'];
		
		$view->autoRefreshTodayReport = false;
		// if the current date is today, or yesterday, 
		// in case the website is set to UTC-12), or today in UTC+14, we refresh the page every 5min
		if(in_array($date, array(	'today', date('Y-m-d'), 
											'yesterday', Piwik_Date::factory('yesterday')->toString('Y-m-d'),
											Piwik_Date::factory('now', 'UTC+14')->toString('Y-m-d'))))
		{
			
			$view->autoRefreshTodayReport = Zend_Registry::get('config')->General->multisites_refresh_after_seconds;
		}
		$this->setGeneralVariablesView($view);
		$this->setMinDateView($minDate, $view);
		$this->setMaxDateView($maxDate, $view);
		$view->show_sparklines = Zend_Registry::get('config')->General->show_multisites_sparklines;

		echo $view->render();
	}
	
	/**
	 * The Multisites reports displays the first calendar date as the earliest day available for all websites.
	 * Also, today is the later "today" available across all timezones.
	 * @param array $siteIds Array of IDs for each site being displayed.
	 * @return array of two Piwik_Date instances. First is the min-date & the second
	 *               is the max date.
	 */
	private function getMinMaxDateAcrossWebsites($siteIds)
	{
		$now = Piwik_Date::now();

		$minDate = null;
		$maxDate = $now->subDay(1)->getTimestamp();
		foreach($siteIds as $idsite)
		{
			// look for 'now' in the website's timezone
			$timezone = Piwik_Site::getTimezoneFor($idsite);
			$date = Piwik_Date::adjustForTimezone($now->getTimestamp(), $timezone);
			if($date > $maxDate)
			{
				$maxDate = $date;
			}
			
			// look for the absolute minimum date
			$creationDate = Piwik_Site::getCreationDateFor($idsite);
			$date = Piwik_Date::adjustForTimezone(strtotime($creationDate), $timezone);
			if(is_null($minDate) || $date < $minDate)
			{
				$minDate = $date;
			}
		}
		
		return array(Piwik_Date::factory($minDate), Piwik_Date::factory($maxDate));
	}
	
	protected function applyPrettyMoney(&$sites)
	{
		foreach($sites as $idsite => &$site)
		{
			$revenue = "-";
			if(!empty($site['revenue']))
			{
				$revenue = Piwik::getPrettyMoney($site['revenue'], $site['idsite'], $htmlAllowed = false);
			}
			$site['revenue'] = '"'. $revenue . '"';
		}
	}

	public function getEvolutionGraph( $fetch = false, $columns = false)
	{
		if(empty($columns))
		{
			$columns = Piwik_Common::getRequestVar('columns');
		}
		$api = "VisitsSummary.get";
		
		if($columns == 'revenue')
		{
			$api = "Goals.get";
		}
		$view = $this->getLastUnitGraph($this->pluginName, __FUNCTION__, $api);
		$view->setColumnsToDisplay($columns);
		return $this->renderView($view, $fetch);
	}
}
