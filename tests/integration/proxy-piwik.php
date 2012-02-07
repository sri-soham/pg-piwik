<?php
/**
 *  Proxy to normal piwik.php, but in testing mode
 *  
 *  - Use the tests database to record Tracking data
 *  - Allows to overwrite the Visitor IP, and Server datetime 
 *  
 * @see Main.test.php
 * 
 */
// Wrapping the request inside ob_start() calls to ensure that the Test
// calling us waits for the full request to process before unblocking
ob_start();

define('PIWIK_INCLUDE_PATH', '../..');
define('PIWIK_USER_PATH', PIWIK_INCLUDE_PATH);

require_once PIWIK_INCLUDE_PATH .'/libs/upgradephp/upgrade.php';
require_once PIWIK_INCLUDE_PATH .'/core/Loader.php';

// Config files forced to use the test database
// Note that this also provides security for Piwik installs containing tests files: 
// this proxy will not record any data in the production database.
Piwik::createConfigObject();
Zend_Registry::get('config')->setTestEnvironment();	
Piwik_Tracker_Config::getInstance()->setTestEnvironment();
// Do not run scheduled tasks during tests
Piwik_Tracker_Config::getInstance()->setTestValue('Tracker', 'scheduled_tasks_min_interval', 0);

// Tests can force the use of 3rd party cookie for ID visitor
if(Piwik_Common::getRequestVar('forceUseThirdPartyCookie', false) == 1)
{
	Piwik_Tracker_Config::getInstance()->setTestValue('Tracker', 'use_third_party_id_cookie', 1);
}

// Tests can force the enabling of IP anonymization
if(Piwik_Common::getRequestVar('forceIpAnonymization', false) == 1)
{
	Piwik_Tracker_Config::getInstance()->setTestValue('Tracker', 'ip_address_mask_length', 2);
	$pluginsTracker = Piwik_Tracker_Config::getInstance()->Plugins_Tracker['Plugins_Tracker'];
	$pluginsTracker[] = "AnonymizeIP";
	Piwik_Tracker_Config::getInstance()->setTestValue('Plugins_Tracker', array('Plugins_Tracker' => $pluginsTracker));
}
// Custom IP to use for this visitor
$customIp = Piwik_Common::getRequestVar('cip', false);
if(!empty($customIp)) 
{
	Piwik_Tracker::setForceIp($customIp);
}

// Custom server date time to use
$customDatetime = Piwik_Common::getRequestVar('cdt', false);
if(!empty($customDatetime))
{
	Piwik_Tracker::setForceDateTime($customDatetime);
}

// Custom server date time to use
$customVisitorId = Piwik_Common::getRequestVar('cid', false);
if(!empty($customVisitorId))
{
	Piwik_Tracker::setForceVisitorId($customVisitorId);
}

// Disable provider plugin, because it is so slow to do reverse ip lookup in dev environment somehow
Piwik_Tracker::setPluginsNotToLoad(array('Provider'));
include '../../piwik.php';
ob_flush();
