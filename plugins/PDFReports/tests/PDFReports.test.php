<?php
if(!defined("PIWIK_PATH_TEST_TO_ROOT")) {
	define('PIWIK_PATH_TEST_TO_ROOT', dirname(__FILE__).'/../../..');
}
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once PIWIK_PATH_TEST_TO_ROOT . "/tests/config_test.php";
}

require_once PIWIK_PATH_TEST_TO_ROOT . '/tests/core/Database.test.php';
require_once PIWIK_PATH_TEST_TO_ROOT . '/plugins/PDFReports/PDFReports.php';

class Test_Piwik_PDFReports extends Test_Database
{
	protected $idSiteAccess;
    function setUp()
    {
    	parent::setUp();
    	
		// setup the access layer
    	$pseudoMockAccess = new FakeAccess;
		FakeAccess::$superUser = true;
		//finally we set the user as a super user by default
		Zend_Registry::set('access', $pseudoMockAccess);
		Piwik_PluginsManager::getInstance()->loadPlugins( array('API', 'UserCountry', 'PDFReports') );
		Piwik_PluginsManager::getInstance()->installLoadedPlugins();
    	Piwik_PDFReports_API::$cache = array();
    	
    	$this->idSiteAccess = Piwik_SitesManager_API::getInstance()->addSite("Test",array("http://piwik.net"));
    	
    	$idSite = Piwik_SitesManager_API::getInstance()->addSite("Test",array("http://piwik.net"));
		FakeAccess::setIdSitesView( array($this->idSiteAccess,2));
		
    }

    function tearDown()
    {
    	Piwik_Query('TRUNCATE '.Piwik_Common::prefixTable('pdf'));
    	Piwik_PDFReports_API::$cache = array();
    }
    
    function test_addReport_getReports()
    {
    	$data = array(
    		'idsite' => $this->idSiteAccess,
    		'description' => 'test description"',
    		'period' => 'day',
    		'reports' => 'UserCountry_getCountry',
    		'email_me' => 1,
    		'additional_emails' => 'test@test.com, t2@test.com',
    	);
    	
    	$dataWebsiteTwo = $data;
    	$dataWebsiteTwo['idsite'] = 2;
    	$dataWebsiteTwo['period'] = 'month';

    	$idReportTwo = $this->_createReport($dataWebsiteTwo);
    	// Testing getReports without parameters
    	$report = reset(Piwik_PDFReports_API::getInstance()->getReports());
    	$this->_checkReportsEqual($report, $dataWebsiteTwo);

    	$idReport = $this->_createReport($data);
    	
    	// Passing 3 parameters
    	$report = reset(Piwik_PDFReports_API::getInstance()->getReports($this->idSiteAccess, $data['period'], $idReport));
    	$this->_checkReportsEqual($report, $data);

    	// Passing only idsite
    	$report = reset(Piwik_PDFReports_API::getInstance()->getReports($this->idSiteAccess));
    	$this->_checkReportsEqual($report, $data);
    	
    	// Passing only period
    	$report = reset(Piwik_PDFReports_API::getInstance()->getReports($idSite=false, $data['period']));
    	$this->_checkReportsEqual($report, $data);
    	
    	// Passing only idreport
    	$report = reset(Piwik_PDFReports_API::getInstance()->getReports($idSite=false,$period=false, $idReport));
    	$this->_checkReportsEqual($report, $data);
    	
    }
    
    function test_getReports_idReportNotFound()
    {
    	try {
        	$report = Piwik_PDFReports_API::getInstance()->getReports($idSite=false,$period=false, $idReport = 1);
        	var_dump($report);
        	$this->fail();
    	} catch(Exception $e) { 
    		$this->pass(); 
    	}
    }
    
    function test_getReports_invalidPermission()
    {
    	$data = $this->_getAddReportData();
    	$idReport = $this->_createReport($data);
    	
    	try {
        	$report = Piwik_PDFReports_API::getInstance()->getReports($idSite=44,$period=false, $idReport);
        	$this->fail();
    	} catch(Exception $e){ 
    		$this->pass(); 
    	}
    }
    
    function test_addReport_invalidWebsite()
    {
    	$data = $this->_getAddReportData();
    	$data['idsite'] = 33;
    	try {
    		$idReport = $this->_createReport($data);
    		$this->fail();
    	} catch(Exception $e){ 
    		$this->pass(); 
    	}
    }
    
    function test_addReport_invalidPeriod()
    {
    	$data = $this->_getAddReportData();
    	$data['period'] = 'dx';
    	try {
    		$idReport = $this->_createReport($data);
    		$this->fail();
    	} catch(Exception $e){ 
    		$this->pass(); 
    	}
    }
    
    function test_updateReport()
    {
    	$dataBefore = $this->_getAddReportData();
    	$idReport = $this->_createReport($dataBefore);
    	$dataAfter = $this->_getYetAnotherAddReportData();
    	$this->_updateReport($idReport, $dataAfter);
    	$newReport = reset(Piwik_PDFReports_API::getInstance()->getReports($idSite=false,$period=false, $idReport));
    	$this->_checkReportsEqual($newReport, $dataAfter);
    }
    
    function test_deleteReport()
    {
    	// Deletes non existing report throws exception
    	try {
    		Piwik_PDFReports_API::getInstance()->deleteReport($idReport = 1);
    		$this->fail();
    	} catch(Exception $e) { 
    		$this->pass(); 
    	}
    	
    	$idReport = $this->_createReport($this->_getYetAnotherAddReportData());
    	$this->assertEqual(1, count(Piwik_PDFReports_API::getInstance()->getReports()));
		Piwik_PDFReports_API::getInstance()->deleteReport($idReport);
    	$this->assertEqual(0, count(Piwik_PDFReports_API::getInstance()->getReports()));
    }
    
    
    function _getAddReportData()
    {
    	return array(
    		'idsite' => $this->idSiteAccess,
    		'description' => 'test description"',
    		'period' => 'day',
    		'reports' => 'UserCountry_getCountry',
    		'email_me' => 1,
    		'additional_emails' => 'test@test.com, t2@test.com',
    	);
    }
    
    function _getYetAnotherAddReportData()
    {
    	return array(
    		'idsite' => $this->idSiteAccess,
    		'description' => 'very very long and possibly truncated description. very very long and possibly truncated description. very very long and possibly truncated description. very very long and possibly truncated description. very very long and possibly truncated description. ',
    		'period' => 'month',
    		'reports' => 'UserCountry_getContinent',
    		'email_me' => 0,
    		'additional_emails' => 'blabla@ec.fr',
    	);
    }
    function _createReport($data)
    {
    	$idReport = Piwik_PDFReports_API::getInstance()->addReport(
    										$data['idsite'], 
    										$data['description'], 
    										$data['period'], 
    										$data['reports'], 
    										$data['email_me'], 
    										$data['additional_emails']);
    	return $idReport;
    }
    
    function _updateReport($idReport, $data)
    {
    	//$idReport, $idSite, $description, $period, $reports, $emailMe = true, $additionalEmails = false)
    	$idReport = Piwik_PDFReports_API::getInstance()->updateReport(
    										$idReport,
    										$data['idsite'], 
    										$data['description'], 
    										$data['period'], 
    										$data['reports'], 
    										$data['email_me'], 
    										$data['additional_emails']);
    	return $idReport;
    }
    function _checkReportsEqual($report, $data)
    {
    	foreach($data as $key => $value)
    	{
    		if($key == 'additional_emails') $value = str_replace(' ','', $value);
    		if($key == 'description') $value = substr($value,0,250);
    		$this->assertEqual($value, $report[$key], "Error for $key for report $report and data ".var_export($data,true)." ---> %s ");
    	}
    }
       
}
