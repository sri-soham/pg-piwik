<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 * 
 * @package Piwik_Referers
 */

function Piwik_getPathFromUrl($url)
{
	$path = Piwik_Common::getPathAndQueryFromUrl($url);
	if(empty($path))
	{
		return 'index';
	}
	return $path;
}

function Piwik_getSearchEngineUrlFromName($name)
{
	require_once PIWIK_INCLUDE_PATH . '/core/DataFiles/SearchEngines.php';
	if(isset($GLOBALS['Piwik_SearchEngines_NameToUrl'][$name]))
	{
		$url = 'http://'.$GLOBALS['Piwik_SearchEngines_NameToUrl'][$name];
	}
	else
	{
		$url = 'URL unknown!';
	}
	return $url;
}

function Piwik_getSearchEngineLogoFromUrl($url)
{
	$pathInPiwik = 'plugins/Referers/images/searchEngines/%s.png';
	$pathWithCode = sprintf($pathInPiwik, Piwik_getSearchEngineHostFromUrl($url));
	$absolutePath = PIWIK_INCLUDE_PATH . '/' . $pathWithCode;
	if(file_exists($absolutePath))
	{
		return $pathWithCode;
	}
	return sprintf($pathInPiwik, 'xx');
}

function Piwik_getSearchEngineHostFromUrl($url)
{
	return substr($url, strpos($url,'//') + 2);
}

function Piwik_getSearchEngineUrlFromUrlAndKeyword($url, $keyword)
{
	require_once PIWIK_INCLUDE_PATH . '/core/DataFiles/SearchEngines.php';
	$keyword = urlencode($keyword);
	$keyword = str_replace(urlencode('+'), urlencode(' '), $keyword);
	$path = @$GLOBALS['Piwik_SearchEngines'][Piwik_getSearchEngineHostFromUrl($url)][2];
	if(empty($path))
	{
		return false;
	}
	$path = str_replace("{k}", $keyword, $path);
	return $url . '/' . $path;
}

function Piwik_getSearchEngineUrlFromKeywordAndUrl($keyword, $url)
{
	return Piwik_getSearchEngineUrlFromUrlAndKeyword($url, $keyword);
}

function Piwik_getRefererTypeLabel($label)
{
	$indexTranslation = '';
	switch($label)
	{
		case Piwik_Common::REFERER_TYPE_DIRECT_ENTRY:
			$indexTranslation = 'Referers_DirectEntry';
			break;
		case Piwik_Common::REFERER_TYPE_SEARCH_ENGINE:
			$indexTranslation = 'Referers_SearchEngines';
			break;
		case Piwik_Common::REFERER_TYPE_WEBSITE:
			$indexTranslation = 'Referers_Websites';
			break;
		case Piwik_Common::REFERER_TYPE_CAMPAIGN:
			$indexTranslation = 'Referers_Campaigns';
			break;
		default:
			// case of newsletter, partners, before Piwik 0.2.25
			$indexTranslation = 'General_Others';
			break;
	}
	return Piwik_Translate($indexTranslation);
}

