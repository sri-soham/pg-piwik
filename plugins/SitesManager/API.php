<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 * 
 * @category Piwik_Plugins
 * @package Piwik_SitesManager
 */

/**
 *
 * @package Piwik_SitesManager
 */
class Piwik_SitesManager_API 
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
	
	/**
	 * Returns the javascript tag for the given idSite.
	 * This tag must be included on every page to be tracked by Piwik
	 *
	 * @param int $idSite
	 * @return string The Javascript tag ready to be included on the HTML pages
	 */
	public function getJavascriptTag( $idSite, $piwikUrl = '', $actionName = '')
	{
		Piwik::checkUserHasViewAccess($idSite);
		
		$actionName = "'".addslashes(Piwik_Common::sanitizeInputValues($actionName))."'";
		if(empty($piwikUrl))
		{
			$piwikUrl = Piwik_Url::getCurrentUrlWithoutFileName();
		}
		$piwikUrl = addslashes(Piwik_Common::sanitizeInputValues($piwikUrl));
		
		$htmlEncoded = Piwik::getJavascriptCode($idSite, $piwikUrl, $actionName);
		$htmlEncoded = str_replace(array('<br>','<br />','<br/>'), '', $htmlEncoded);
		return html_entity_decode($htmlEncoded);
	}
	
	/**
	 * Returns the website information : name, main_url
	 * 
	 * @exception if the site ID doesn't exist or the user doesn't have access to it
	 * @return array
	 */
	public function getSiteFromId( $idSite )
	{
		Piwik::checkUserHasViewAccess( $idSite );
		$site = Zend_Registry::get('db')->fetchRow("SELECT * FROM ".Piwik::prefixTable("site")." WHERE idsite = ?", $idSite);
		return $site;
	}
	
	/**
	 * Returns the list of alias URLs registered for the given idSite.
	 * The website ID must be valid when calling this method!
	 * 
	 * @return array list of alias URLs
	 */
	private function getAliasSiteUrlsFromId( $idsite )
	{
		$db = Zend_Registry::get('db');
		$result = $db->fetchAll("SELECT url 
								FROM ".Piwik::prefixTable("site_url"). " 
								WHERE idsite = ?", $idsite);
		$urls = array();
		foreach($result as $url)
		{
			$urls[] = $url['url'];
		}
		return $urls;
	}
	
	/**
	 * Returns the list of all URLs registered for the given idSite (main_url + alias URLs).
	 * 
	 * @exception if the website ID doesn't exist or the user doesn't have access to it
	 * @return array list of URLs
	 */
	public function getSiteUrlsFromId( $idSite )
	{
		Piwik::checkUserHasViewAccess($idSite);
		$site = $this->getSiteFromId($idSite);
		$urls = $this->getAliasSiteUrlsFromId($idSite);
		return array_merge(array($site['main_url']), $urls);
	}
	
	/**
	 * Returns the list of all the websites ID registered
	 * 
	 * @return array the list of websites ID
	 */
	public function getAllSitesId()
	{
		Piwik::checkUserIsSuperUser();
		$result = Piwik_FetchAll("SELECT idsite FROM ".Piwik::prefixTable('site'));
		$idSites = array();
		foreach($result as $idSite)
		{
			$idSites[] = $idSite['idsite'];
		}
		return $idSites;
	}
	
	
	/**
	 * Returns the list of websites with the 'admin' access for the current user.
	 * For the superUser it returns all the websites in the database.
	 * 
	 * @return array for each site, an array of information (idsite, name, main_url, etc.)
	 */
	public function getSitesWithAdminAccess()
	{
		$sitesId = $this->getSitesIdWithAdminAccess();
		return $this->getSitesFromIds($sitesId);
	}
	
	/**
	 * Returns the list of websites with the 'view' access for the current user.
	 * For the superUser it doesn't return any result because the superUser has admin access on all the websites (use getSitesWithAtLeastViewAccess() instead).
	 * 
	 * @return array for each site, an array of information (idsite, name, main_url, etc.)
	 */
	public function getSitesWithViewAccess()
	{
		$sitesId = $this->getSitesIdWithViewAccess();
		return $this->getSitesFromIds($sitesId);
	}
	
	/**
	 * Returns the list of websites with the 'view' or 'admin' access for the current user.
	 * For the superUser it returns all the websites in the database.
	 * 
	 * @return array array for each site, an array of information (idsite, name, main_url, etc.)
	 */
	public function getSitesWithAtLeastViewAccess()
	{
		$sitesId = $this->getSitesIdWithAtLeastViewAccess();
		return $this->getSitesFromIds($sitesId);
	}
	
	/**
	 * Returns the list of websites ID with the 'admin' access for the current user.
	 * For the superUser it returns all the websites in the database.
	 * 
	 * @return array list of websites ID
	 */
	public function getSitesIdWithAdminAccess()
	{
		$sitesId = Zend_Registry::get('access')->getSitesIdWithAdminAccess();
		return $sitesId;
	}
	
	/**
	 * Returns the list of websites ID with the 'view' access for the current user.
	 * For the superUser it doesn't return any result because the superUser has admin access on all the websites (use getSitesIdWithAtLeastViewAccess() instead).
	 * 
	 * @return array list of websites ID
	 */
	public function getSitesIdWithViewAccess()
	{
		return Zend_Registry::get('access')->getSitesIdWithViewAccess();
	}
	
	/**
	 * Returns the list of websites ID with the 'view' or 'admin' access for the current user.
	 * For the superUser it returns all the websites in the database.
	 * 
	 * @return array list of websites ID
	 */
	public function getSitesIdWithAtLeastViewAccess()
	{
		return Zend_Registry::get('access')->getSitesIdWithAtLeastViewAccess();
	}

	/**
	 * Returns the list of websites from the ID array in parameters.
	 * The user access is not checked in this method so the ID have to be accessible by the user!
	 * 
	 * @param array list of website ID
	 */
	private function getSitesFromIds( $idSites )
	{
		if(count($idSites) === 0)
		{
			return array();
		}
		$db = Zend_Registry::get('db');
		$sites = $db->fetchAll("SELECT * 
								FROM ".Piwik::prefixTable("site")." 
								WHERE idsite IN (".implode(", ", $idSites).")
								ORDER BY idsite ASC");
		return $sites;
	}
	
	/**
	 * Add a website in the database.
	 * 
	 * The website is defined by a name and an array of URLs.
	 * The name must not be empty.
	 * The URLs array must contain at least one URL called the 'main_url' ; 
	 * if several URLs are provided in the array, they will be recorded as Alias URLs for
	 * this website.
	 * 
	 * Requires Super User access.
	 * 
	 * @return int the website ID created
	 */
	public function addSite( $siteName, $urls )
	{
		Piwik::checkUserIsSuperUser();
		
		$this->checkName($siteName);
		$urls = $this->cleanParameterUrls($urls);
		$this->checkUrls($urls);
		$this->checkAtLeastOneUrl($urls);
		
		$db = Zend_Registry::get('db');
		
		$url = $urls[0];
		$urls = array_slice($urls, 1);
		
		$db->insert(Piwik::prefixTable("site"), array(
									'name' => $siteName,
									'main_url' => $url,
									)
								);
									
		$idSite = $db->lastInsertId();
		
		$this->insertSiteUrls($idSite, $urls);
		
		// we reload the access list which doesn't yet take in consideration this new website
		Zend_Registry::get('access')->reloadAccess();
		$this->postUpdateWebsite($idSite);

		return (int)$idSite;
	}
	
	private function postUpdateWebsite($idSite)
	{
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);	
	}
	
	/**
	 * Delete a website from the database, given its Id.
	 * 
	 * Requires Super User access. 
	 *
	 * @param int $idSite
	 */
	public function deleteSite( $idSite )
	{
		Piwik::checkUserIsSuperUser();
		
		$idSites = Piwik_SitesManager_API::getInstance()->getAllSitesId();
		if(!in_array($idSite, $idSites))
		{
			throw new Exception("website id = $idSite not found");
		}
		$nbSites = count($idSites);
		if($nbSites == 1)
		{
			throw new Exception(Piwik_TranslateException("SitesManager_ExceptionDeleteSite"));
		}
		
		$db = Zend_Registry::get('db');
		
		$db->query("DELETE FROM ".Piwik::prefixTable("site")." 
					WHERE idsite = ?", $idSite);
		
		$db->query("DELETE FROM ".Piwik::prefixTable("site_url")." 
					WHERE idsite = ?", $idSite);
		
		$db->query("DELETE FROM ".Piwik::prefixTable("access")." 
					WHERE idsite = ?", $idSite);
		
		Piwik_Common::deleteCacheWebsiteAttributes($idSite);
	}
	
	
	/**
	 * Checks that the array has at least one element
	 * 
	 * @exception if the parameter is not an array or if array empty 
	 */
	private function checkAtLeastOneUrl( $urls )
	{
		if(!is_array($urls)
			|| count($urls) == 0)
		{
			throw new Exception(Piwik_TranslateException("SitesManager_ExceptionNoUrl"));
		}
	}

	/**
	 * Add a list of alias Urls to the given idSite
	 * 
	 * If some URLs given in parameter are already recorded as alias URLs for this website,
	 * they won't be duplicated. The 'main_url' of the website won't be affected by this method.
	 * 
	 * @return int the number of inserted URLs
	 */
	public function addSiteAliasUrls( $idSite,  $urls)
	{
		Piwik::checkUserHasAdminAccess( $idSite );
		
		$urls = $this->cleanParameterUrls($urls);
		$this->checkUrls($urls);
		
		$urlsInit = $this->getSiteUrlsFromId($idSite);
		$toInsert = array_diff($urls, $urlsInit);
		$this->insertSiteUrls($idSite, $toInsert);
		$this->postUpdateWebsite($idSite);
		
		return count($toInsert);
	}
	
	/**
	 * Update an existing website.
	 * If only one URL is specified then only the main url will be updated.
	 * If several URLs are specified, both the main URL and the alias URLs will be updated.
	 * 
	 * @param int website ID defining the website to edit
	 * @param string website name
	 * @param string|array the website URLs
	 * 
	 * @exception if any of the parameter is not correct
	 * 
	 * @return bool true on success
	 */
	public function updateSite( $idSite, $siteName, $urls = null)
	{
		Piwik::checkUserHasAdminAccess($idSite);

		$this->checkName($siteName);
		
		// SQL fields to update
		$bind = array();
		
		if(!is_null($urls))
		{
			$urls = $this->cleanParameterUrls($urls);
			$this->checkUrls($urls);
			$this->checkAtLeastOneUrl($urls);
			$url = $urls[0];
			
			$bind['main_url'] = $url;
		}
		
		$bind['name'] = $siteName;
		
		$db = Zend_Registry::get('db');
		$db->update(Piwik::prefixTable("site"), 
							$bind,
							"idsite = $idSite"
								);
								
		// we now update the main + alias URLs
		$this->deleteSiteAliasUrls($idSite);
		if(count($urls) > 1)
		{
			$insertedUrls = $this->addSiteAliasUrls($idSite, array_slice($urls,1));
		}
		$this->postUpdateWebsite($idSite);
	}
	
	/**
	 * Insert the list of alias URLs for the website.
	 * The URLs must not exist already for this website!
	 */
	private function insertSiteUrls($idSite, $urls)
	{
		if(count($urls) != 0)
		{
			$db = Zend_Registry::get('db');
			foreach($urls as $url)
			{
				$db->insert(Piwik::prefixTable("site_url"), array(
										'idsite' => $idSite,
										'url' => $url
										)
									);
			}
		}
	}
	
	/**
	 * Delete all the alias URLs for the given idSite.
	 */
	private function deleteSiteAliasUrls($idsite)
	{
		$db = Zend_Registry::get('db');
		$db->query("DELETE FROM ".Piwik::prefixTable("site_url") ." 
					WHERE idsite = ?", $idsite);
	}
	
	/**
	 * Remove the final slash in the URLs if found
	 * 
	 * @return string the URL without the trailing slash
	 */
	private function removeTrailingSlash($url)
	{
		// if there is a final slash, we take the URL without this slash (expected URL format)
		if(strlen($url) > 5
			&& $url[strlen($url)-1] == '/')
		{
			$url = substr($url,0,strlen($url)-1);
		}
		return $url;
	}
	
	/**
	 * Tests if the URL is a valid URL
	 * 
	 * @return bool
	 */
	private function isValidUrl( $url )
	{
		return Piwik_Common::isLookLikeUrl($url);
	}
	
	/**
	 * Check that the website name has a correct format.
	 * 
	 * @exception if the website name is empty
	 */
	private function checkName($siteName)
	{
		if(empty($siteName))
		{
			throw new Exception(Piwik_TranslateException("SitesManager_ExceptionEmptyName"));
		}
	}

	/**
	 * Check that the array of URLs are valid URLs
	 * 
	 * @exception if any of the urls is not valid
	 * @param array
	 */
	private function checkUrls($urls)
	{
		foreach($urls as $url)
		{			
			if(!$this->isValidUrl($url))
			{
				throw new Exception(sprintf(Piwik_TranslateException("SitesManager_ExceptionInvalidUrl"),$url));
			}
		}
	}
	
	/**
	 * Clean the parameter URLs:
	 * - if the parameter is a string make it an array
	 * - remove the trailing slashes if found
	 * 
	 * @param string|array urls
	 * @return array the array of cleaned URLs
	 */
	private function cleanParameterUrls( $urls )
	{
		if(!is_array($urls))
		{
			$urls = array($urls);
		}
		foreach($urls as &$url)
		{
			$url = $this->removeTrailingSlash($url);
		}
		$urls = array_unique($urls);
		
		return $urls;
	}
}

