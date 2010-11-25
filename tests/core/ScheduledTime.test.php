<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../tests/config_test.php";
}

require_once 'ScheduledTime.php';
require_once 'ScheduledTime/Hourly.php';
require_once 'ScheduledTime/Daily.php';
require_once 'ScheduledTime/Weekly.php';
require_once 'ScheduledTime/Monthly.php';

Mock::generatePartial('Piwik_ScheduledTime_Hourly', 'Piwik_ScheduledTime_Hourly_Test', array('getTime'));
Mock::generatePartial('Piwik_ScheduledTime_Daily', 'Piwik_ScheduledTime_Daily_Test', array('getTime'));
Mock::generatePartial('Piwik_ScheduledTime_Weekly', 'Piwik_ScheduledTime_Weekly_Test', array('getTime'));
Mock::generatePartial('Piwik_ScheduledTime_Monthly', 'Piwik_ScheduledTime_Monthly_Test', array('getTime'));

class Test_Piwik_ScheduledTime extends UnitTestCase
{
	private $JANUARY_01_1971_09_00_00;
	private $JANUARY_01_1971_09_10_00;
	private $JANUARY_01_1971_10_00_00;
	private $JANUARY_01_1971_10_10_00;
	private $JANUARY_01_1971_11_00_00;
	private $JANUARY_01_1971_12_00_00;
	private $JANUARY_01_1971_12_10_00;
	private $JANUARY_02_1971_09_00_00;
	private $JANUARY_02_1971_09_10_00;
	private $JANUARY_04_1971_09_00_00;
	private $JANUARY_04_1971_09_10_00;
	private $JANUARY_05_1971_09_00_00;
	private $JANUARY_05_1971_09_10_00;
	private $JANUARY_05_1971_12_00_00;
	private $JANUARY_08_1971_09_00_00;
	private $JANUARY_08_1971_09_10_00;
	private $JANUARY_09_1971_09_00_00;
	private $JANUARY_09_1971_09_10_00;
	private $JANUARY_11_1971_09_00_00;
	private $JANUARY_11_1971_09_10_00;
	private $JANUARY_15_1971_09_00_00;
	private $JANUARY_31_1971_09_00_00;
	private $FEBRUARY_01_1971_09_00_00;
	private $FEBRUARY_02_1971_09_00_00;
	private $FEBRUARY_05_1971_09_00_00;
	private $FEBRUARY_28_1971_09_00_00;
	private $MARCH_05_1971_09_00_00;
	
	function __construct( $title = '')
	{
		$this->JANUARY_01_1971_09_00_00 = mktime(9,00,00,1,1,1971);
		$this->JANUARY_01_1971_09_10_00 = mktime(9,10,00,1,1,1971);
		$this->JANUARY_01_1971_10_00_00 = mktime(10,00,00,1,1,1971);
		$this->JANUARY_01_1971_10_10_00 = mktime(10,10,00,1,1,1971);
		$this->JANUARY_01_1971_11_00_00 = mktime(11,00,00,1,1,1971);
		$this->JANUARY_01_1971_12_00_00 = mktime(12,00,00,1,1,1971);
		$this->JANUARY_01_1971_12_10_00 = mktime(12,10,00,1,1,1971);
		$this->JANUARY_02_1971_09_00_00 = mktime(9,00,00,1,2,1971);
		$this->JANUARY_02_1971_09_10_00 = mktime(9,10,00,1,2,1971);
		$this->JANUARY_04_1971_09_00_00 = mktime(9,00,00,1,4,1971);
		$this->JANUARY_04_1971_09_10_00 = mktime(9,10,00,1,4,1971);
		$this->JANUARY_05_1971_09_00_00 = mktime(9,00,00,1,5,1971);
		$this->JANUARY_05_1971_09_10_00 = mktime(9,10,00,1,5,1971);
		$this->JANUARY_05_1971_12_00_00 = mktime(12,00,00,1,5,1971);
		$this->JANUARY_08_1971_09_00_00 = mktime(9,00,00,1,8,1971);
		$this->JANUARY_08_1971_09_10_00 = mktime(9,10,00,1,8,1971);
		$this->JANUARY_09_1971_09_00_00 = mktime(9,00,00,1,9,1971);
		$this->JANUARY_09_1971_09_10_00 = mktime(9,10,00,1,9,1971);
		$this->JANUARY_11_1971_09_00_00 = mktime(9,00,00,1,11,1971);
		$this->JANUARY_11_1971_09_10_00 = mktime(9,10,00,1,11,1971);
		$this->JANUARY_15_1971_09_00_00 = mktime(9,00,00,1,15,1971);
		$this->JANUARY_31_1971_09_00_00 = mktime(9,00,00,1,31,1971);
		$this->FEBRUARY_01_1971_09_00_00 = mktime(9,00,00,2,1,1971);
		$this->FEBRUARY_02_1971_09_00_00 = mktime(9,00,00,2,2,1971);
		$this->FEBRUARY_05_1971_09_00_00 = mktime(9,00,00,2,5,1971);
		$this->FEBRUARY_28_1971_09_00_00 = mktime(9,00,00,2,28,1971);
		$this->MARCH_05_1971_09_00_00 = mktime(9,00,00,3,5,1971);

		parent::__construct( $title );
	}
	
	/*
	 * Tests forbidden call to setHour on Piwik_ScheduledTime_Hourly
	 */
	public function test_setHour_ScheduledTime_Hourly()
	{
		try {
			$hourlySchedule = new Piwik_ScheduledTime_Hourly();
			$hourlySchedule->setHour(0);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Daily
	 */
	public function test_setHour_ScheduledTime_Daily_Negative()
	{
		try {
			$dailySchedule = new Piwik_ScheduledTime_Daily();
			$dailySchedule->setHour(-1);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Daily
	 */
	public function test_setHour_ScheduledTime_Daily_Over_24()
	{
		try {
			$dailySchedule = new Piwik_ScheduledTime_Daily();
			$dailySchedule->setHour(25);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	
	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Weekly
	 */
	public function test_setHour_ScheduledTime_Weekly_Negative()
	{
		try {
			$weeklySchedule = new Piwik_ScheduledTime_Weekly();
			$weeklySchedule->setHour(-1);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Weekly
	 */
	public function test_setHour_ScheduledTime_Weekly_Over_24()
	{
		try {
			$weeklySchedule = new Piwik_ScheduledTime_Weekly();
			$weeklySchedule->setHour(25);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	
	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Monthly
	 */
	public function test_setHour_ScheduledTime_Monthly_Negative()
	{
		try {
			$monthlySchedule = new Piwik_ScheduledTime_Monthly();
			$monthlySchedule->setHour(-1);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setHour on Piwik_ScheduledTime_Monthly
	 */
	public function test_setHour_ScheduledTime_Monthly_Over_24()
	{
		try {
			$monthlySchedule = new Piwik_ScheduledTime_Monthly();
			$monthlySchedule->setHour(25);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}	

	/*
	 * Tests forbidden call to setDay on Piwik_ScheduledTime_Hourly
	 */
	public function test_setDay_ScheduledTime_Hourly()
	{
		try {
			$hourlySchedule = new Piwik_ScheduledTime_Hourly();
			$hourlySchedule->setDay(1);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	
	/*
	 * Tests forbidden call to setDay on Piwik_ScheduledTime_Daily
	 */
	public function test_setDay_ScheduledTime_Daily()
	{
		try {
			$dailySchedule = new Piwik_ScheduledTime_Daily();
			$dailySchedule->setDay(1);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	
	/*
	 * Tests invalid call to setDay on Piwik_ScheduledTime_Weekly
	 */
	public function test_setDay_ScheduledTime_Weekly_Day_0()
	{
		try {
			$weeklySchedule = new Piwik_ScheduledTime_Weekly();
			$weeklySchedule->setDay(0);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setDay on Piwik_ScheduledTime_Weekly
	 */
	public function test_setDay_ScheduledTime_Weekly_Over_7()
	{
		try {
			$weeklySchedule = new Piwik_ScheduledTime_Weekly();
			$weeklySchedule->setDay(8);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	
	/*
	 * Tests invalid call to setDay on Piwik_ScheduledTime_Monthly
	 */
	public function test_setDay_ScheduledTime_Monthly_Day_0()
	{
		try {
			$monthlySchedule = new Piwik_ScheduledTime_Monthly();
			$monthlySchedule->setDay(0);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}

	/*
	 * Tests invalid call to setDay on Piwik_ScheduledTime_Monthly
	 */
	public function test_setDay_ScheduledTime_Monthly_Over_31()
	{
		try {
			$monthlySchedule = new Piwik_ScheduledTime_Monthly();
			$monthlySchedule->setDay(32);
			$this->fail("Exception not raised.");
		}
		catch (Exception $expected) {
			$this->pass();
			return;
		}
	}
	

	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Hourly
	 *
	 */
	public function test_getRescheduledTime_Hourly()
	{
		
		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *
		 * Expected :
		 *  getRescheduledTime returns January 1 1971 10:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Hourly_Test();
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_01_1971_10_00_00);

		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task to simulate a previous task
		 *
		 * Expected :
		 *  getRescheduledTime returns January 1 1971 10:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Hourly_Test();
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_10_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_01_1971_10_00_00);

		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 1 hour and 10 minutes after the cron task to simulate a long previous task
		 *
		 * Expected :
		 *  getRescheduledTime returns January 1 1971 11:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Hourly_Test();
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_10_10_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_01_1971_11_00_00);
	}

	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Daily with unspecified hour
	 *
	 */
	public function test_getRescheduledTime_Daily_Unspecified_Hour()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_00_00);

		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task to simulate a previous task
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:10:00 GMT
		 *  The task would then be executed at January 2 1971 10:00:00 which is ok because setHour has not been called
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_10_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_10_00);
	}

	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Daily with specified hour
	 *
	 */
	public function test_getRescheduledTime_Daily_Specified_Hour()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setHour(9);
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_00_00);

		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task to simulate a previous task
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setHour(9);
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_10_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_00_00);

		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron fails to run at January 1 1971 09:00:00 GMT, runs at January 1 1971 12:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setHour(9);
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_12_00_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_00_00);

		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron fails to run at January 1 1971 09:00:00 GMT, runs at January 1 1971 12:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task to simulate a previous task
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns January 2 1971 09:00:00 GMT
		 */
		$hourlySchedule = new Piwik_ScheduledTime_Daily_Test();
		$hourlySchedule->setHour(9);
		$hourlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_12_10_00);
		$this->assertEqual($hourlySchedule->getRescheduledTime(), $this->JANUARY_02_1971_09_00_00);
	}
	
	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Weekly with unspecified hour and unspecified day
	 *
	 */
	public function test_getRescheduledTime_Weekly_Unspecified_Hour_Unspecified_Day()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 8 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_08_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task to simulate a previous task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 8 1971 09:10:00 GMT
		 *  The task would then be executed at January 8 1971 10:00:00 which is ok because setHour has not been called
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_08_1971_09_10_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 1 day and 10 minutes after the cron task to simulate a long previous task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 9 1971 09:10:00 GMT
		 *  The task would then be executed at January 9 1971 10:00:00 which is ok because setHour and setDay 
		 *  have not been called.
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_02_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_09_1971_09_10_00);

	}
	
	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Weekly with specified hour and unspecified day
	 *
	 */
	public function test_getRescheduledTime_Weekly_Specified_Hour_Unspecified_Day()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is set to 9
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 8 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_08_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task
		 *  - setHour is set to 9
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 8 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_08_1971_09_00_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 1 day and 10 minutes after the cron task to simulate a long previous task
		 *  - setHour is set to 9
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 9 1971 09:00:00 GMT
		 *  The task would then be executed at January 9 1971 09:00:00 which is ok because setDay 
		 *  has not been called.
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_02_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_09_1971_09_00_00);
		
		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron fails to run at January 1 1971 09:00:00 GMT, runs at January 1 1971 12:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is set to 9
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns January 8 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_12_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_08_1971_09_00_00);
	}	

	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Weekly with unspecified hour and specified day
	 *
	 */
	public function test_getRescheduledTime_Weekly_Unspecified_Hour_Specified_Day()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at Monday January 4 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_04_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at Monday January 4 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task
		 *  - setDay is set to 1
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:10:00 GMT
		 *  The task will run at Monday January 11 1971 10:00:00 GMT which is ok because setHour has not been called
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_04_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_10_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron fails to run at Monday January 4 1971 09:00:00 GMT, cron restarted at January 5 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 1
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_05_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);

		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron runs at Monday January 4 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 5
		 *  - setHour is not called, no need to control the scheduled hour
		 *
		 * Expected :
		 *  getRescheduledTime returns Friday January 15 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(5);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_04_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_15_1971_09_00_00);
	}
	
	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Weekly with specified hour and specified day
	 *
	 */
	public function test_getRescheduledTime_Weekly_Specified_Hour_Specified_Day()
	{

		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at Monday January 4 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_04_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at Monday January 4 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_04_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron fails to run at Monday January 4 1971 09:00:00 GMT, cron restarted at January 5 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_05_1971_09_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);
		
		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron fails to run at Monday January 4 1971 09:00:00 GMT, cron restarted at January 5 1971 09:00:00 GMT
		 *  - getRescheduledTime is called 10 minutes after the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_05_1971_09_10_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);	
		
		/*
		 * Test 5
		 *
		 * Context :
		 *  - Cron fails to run at Monday January 4 1971 09:00:00 GMT, cron restarted at January 5 1971 12:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setDay is set to 1, Monday
		 *  - setHour is set to 9
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday January 11 1971 09:00:00 GMT
		 */
		$weeklySchedule = new Piwik_ScheduledTime_Weekly_Test();
		$weeklySchedule->setDay(1);
		$weeklySchedule->setHour(9);
		$weeklySchedule->setReturnValue('getTime', $this->JANUARY_05_1971_12_00_00);
		$this->assertEqual($weeklySchedule->getRescheduledTime(), $this->JANUARY_11_1971_09_00_00);	
	}
	
	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Monthly with unspecified hour and unspecified day
	 *
	 */
	public function test_getRescheduledTime_Monthly_Unspecified_Hour_Unspecified_Day()
	{
		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns February 1 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_01_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at January 5 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns February 5 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_05_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_05_1971_09_00_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron runs at February 5 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns March 5 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setReturnValue('getTime', $this->FEBRUARY_05_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->MARCH_05_1971_09_00_00);

		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron runs at January 31 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is not called, no need to control the scheduled day
		 *
		 * Expected :
		 *  getRescheduledTime returns February 28 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_31_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_28_1971_09_00_00);
	}

	
	/*
	 * Tests getRescheduledTime on Piwik_ScheduledTime_Monthly with unspecified hour and specified day
	 *
	 */
	public function test_getRescheduledTime_Monthly_Unspecified_Hour_Specified_Day()
	{
		/*
		 * Test 1
		 *
		 * Context :
		 *  - Cron runs at January 1 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is set to 1
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday February 1 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setDay(1);
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_01_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_01_1971_09_00_00);
		
		/*
		 * Test 2
		 *
		 * Context :
		 *  - Cron runs at Saturday January 2 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is set to 2
		 *
		 * Expected :
		 *  getRescheduledTime returns Tuesday February 2 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setDay(2);
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_02_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_02_1971_09_00_00);
		
		/*
		 * Test 3
		 *
		 * Context :
		 *  - Cron runs at Friday January 15 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is set to 1
		 *
		 * Expected :
		 *  getRescheduledTime returns Monday February 1 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setDay(1);
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_15_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_01_1971_09_00_00);

		/*
		 * Test 4
		 *
		 * Context :
		 *  - Cron runs at Friday January 15 1971 09:00:00 GMT
		 *  - getRescheduledTime is called within the same second as the cron task
		 *  - setHour is not called, no need to control the scheduled hour
		 *  - setDay is set to 31
		 *
		 * Expected :
		 *  getRescheduledTime returns Sunday February 28 1971 09:00:00 GMT
		 */
		$monthlySchedule = new Piwik_ScheduledTime_Monthly_Test();
		$monthlySchedule->setDay(31);
		$monthlySchedule->setReturnValue('getTime', $this->JANUARY_15_1971_09_00_00);
		$this->assertEqual($monthlySchedule->getRescheduledTime(), $this->FEBRUARY_28_1971_09_00_00);
	}
}
