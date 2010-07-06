<?php
flush();
define('PIWIK_PATH_TEST_TO_ROOT', '..');
require_once "config_test.php";
Piwik::createConfigObject();
$databaseTestName = Zend_Registry::get('config')->database_tests->dbname;
Zend_Registry::get('config')->disableSavingConfigurationFileUpdates();
Piwik::setMaxExecutionTime(300);
?>

<h2>Piwik unit tests</h2> 
<p>Some of the tests require a database access. The database used for tests is different from your normal Piwik database. 
You may need to create this database ; you can edit the settings for the unit tests database access in your config file 
/config/global.ini.php</p>
<p><b>The database used in your tests is called "<?php echo $databaseTestName; ?>". Create it if necessary.</b></p>
<p><a href='core'>Run the tests by module</a></p>
<hr>

<?php
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

$test = new GroupTest('Piwik - running all tests');
$toInclude = array();

foreach(Piwik::globr(PIWIK_INCLUDE_PATH . '/tests/core', '*.php') as $file)
{
	if(preg_match('/Database|ReleaseCheckList/', $file))
	{
		continue;
	}
	$toInclude[] = $file;
}
sort($toInclude);
foreach(Piwik::globr(PIWIK_INCLUDE_PATH . '/plugins', '*/tests/*.php') as $file)
{
	$toInclude[] = $file;
}
array_unshift($toInclude, PIWIK_INCLUDE_PATH . '/tests/core/Database.test.php');
$toInclude[] = PIWIK_INCLUDE_PATH . '/tests/core/ReleaseCheckList.test.php';

foreach(Piwik::globr(PIWIK_INCLUDE_PATH . '/tests/integration', '*.test.php') as $file)
{
	$toInclude[] = $file;
}
foreach($toInclude as $file)
{
	if(substr_count($file, 'test.php') == 0
//		|| !preg_match('/Documentation/', $file) // Debug: only run this one test in the context of all_tests.php
		)
	{
		print("The file '$file' is not valid: doesn't end with '.test.php' extension. \n<br>");
		continue;
	}
	$test->addFile($file);
}

$timer = new Piwik_Timer;
$test->run(new HtmlReporter());
echo $timer."<br>";
echo $timer->getMemoryLeak();

/*
assertTrue($x)					Fail if $x is false
assertFalse($x)					Fail if $x is true
assertNull($x)					Fail if $x is set
assertNotNull($x)				Fail if $x not set
assertIsA($x, $t)				Fail if $x is not the class or type $t
assertNotA($x, $t)				Fail if $x is of the class or type $t
assertEqual($x, $y)				Fail if $x == $y is false
assertNotEqual($x, $y)			Fail if $x == $y is true
assertWithinMargin($x, $y, $m)	Fail if abs($x - $y) < $m is false
assertOutsideMargin($x, $y, $m)	Fail if abs($x - $y) < $m is true
assertIdentical($x, $y)			Fail if $x == $y is false or a type mismatch
assertNotIdentical($x, $y)		Fail if $x == $y is true and types match
assertReference($x, $y)			Fail unless $x and $y are the same variable
assertClone($x, $y)				Fail unless $x and $y are identical copies
assertPattern($p, $x)			Fail unless the regex $p matches $x
assertNoPattern($p, $x)			Fail if the regex $p matches $x
expectError($x)					Swallows any upcoming matching error
assert($e)						Fail on failed expectation object $e
 */
