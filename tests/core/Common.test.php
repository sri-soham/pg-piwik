<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../tests/config_test.php";
}

require_once 'Common.php';
class Test_Piwik_Common extends UnitTestCase
{
	function __construct( $title = '')
	{
		parent::__construct( $title );
	}
	
	public function setUp()
	{
		$_GET = $_POST = array();
	}
	
	public function tearDown()
	{
	}

	function test_isUrl()
	{
		$valid = array(
			'http://piwik.org',
			'http://www.piwik.org',
			'https://piwik.org',
			'https://piwik.org/dir/dir2/?oeajkgea7aega=&ge=a',
			'ftp://www.pi-wik.org',
			'news://www.pi-wik.org',
			'https://www.tëteâ.org',
			'http://汉语/漢語.cn' //chinese
		);
		
		foreach($valid as $url)
		{
			$this->assertTrue(Piwik_Common::isLookLikeUrl($url), "$url not validated");
		}
	}
	
	function test_isUrl_notvalid()
	{
		$notValid = array(
			'it doesnt look like url',
			'/index?page=test',
			'test.html',
			'/\/\/\/\/\/\\\http://test.com////',
			'jmleslangues.php',
			'http://',
			' http://',
			'testhttp://test.com'
		);
		
		foreach($notValid as $url)
		{
			$this->assertTrue(!Piwik_Common::isLookLikeUrl($url), "$url validated");
		}
	}
	
	// sanitize an array OK
	function test_sanitizeInputValues_array1()
	{
		$a1 = array('test1' => 't1', 't45', "teatae", 4568, array('test'), 1.52);
		$this->assertEqual( $a1, Piwik_Common::sanitizeInputValues($a1));
	}
	
	// sanitize an array OK
	function test_sanitizeInputValues_array2()
	{
		$a1 = array('test1' => 't1', 't45', "teatae", 4568, array('test'), 1.52,
				array('test1' => 't1', 't45', "teatae", 4568, array('test'), 1.52),
				array('test1' => 't1', 't45', "teatae", 4568, array('test'), 1.52),
				array( array(array(array('test1' => 't1', 't45', "teatae", 4568, array('test'), 1.52)))
				));
		$this->assertEqual( $a1, Piwik_Common::sanitizeInputValues($a1));
	}
	
	// sanitize an array with bad value level1
	function test_sanitizeInputValues_arrayBadValueL1()
	{
		$a1 = array('test1' => 't1', 't45', 'tea1"ta"e', 568, 1 => array('t<e"st'), 1.52);
		$a1OK = array('test1' => 't1', 't45', 'tea1&quot;ta&quot;e', 568, 1 => array('t&lt;e&quot;st'), 1.52);
		
		$this->assertEqual( $a1OK, Piwik_Common::sanitizeInputValues($a1));
	}
	
	// sanitize an array with bad value level2
	function test_sanitizeInputValues_arrayBadValueL2()
	{
		$a1 = array('tea1"ta"e' => array('t<e"st' => array('tgeag454554"t')), 1.52);
		$a1OK = array('tea1&quot;ta&quot;e' => array('t&lt;e&quot;st' => array('tgeag454554&quot;t')), 1.52);
		
		$this->assertEqual( $a1OK, Piwik_Common::sanitizeInputValues($a1));
	}
	
	// sanitize a string unicode => no change
	function test_sanitizeInputValues_arrayBadValueutf8()
	{
		$a1 =   " Поиск в Интернете  Поgqegиск страниц на рgeqg8978усском";
		$a1OK = " Поиск в Интернете  Поgqegиск страниц на рgeqg8978усском";

		$this->assertEqual( $a1OK, Piwik_Common::sanitizeInputValues($a1));
	}
	
	// sanitize a bad string
	function test_sanitizeInputValues_badString()
	{
		$string = '& " < > 123abc\'';
		$stringOK = '&amp; &quot; &lt; &gt; 123abc&#039;';
		$this->assertEqual($stringOK, Piwik_Common::sanitizeInputValues($string));

	}
	// sanitize an integer
	function test_sanitizeInputValues_badInteger()
	{
		$string = '121564564';
		$this->assertEqual($string, Piwik_Common::sanitizeInputValues($string));
		$string = '121564564.0121';
		$this->assertEqual($string, Piwik_Common::sanitizeInputValues($string));
		$string = 121564564.0121;
		$this->assertEqual($string, Piwik_Common::sanitizeInputValues($string));
		$string = 12121;
		$this->assertEqual($string, Piwik_Common::sanitizeInputValues($string));
		
	}
	
	// sanitize HTML 
	function test_sanitizeInputValues_HTML()
	{
		$html = "<test toto='mama' piwik=\"cool\">Piwik!!!!!</test>";
		$htmlOK = "&lt;test toto=&#039;mama&#039; piwik=&quot;cool&quot;&gt;Piwik!!!!!&lt;/test&gt;";
		$this->assertEqual($htmlOK, Piwik_Common::sanitizeInputValues($html));
	}
	
	// sanitize a SQL query
	function test_sanitizeInputValues_SQLQuery()
	{
		$sql = "SELECT piwik FROM piwik_tests where test= 'super\"value' AND cool=toto #comment here";
		$sqlOK = "SELECT piwik FROM piwik_tests where test= &#039;super&quot;value&#039; AND cool=toto #comment here";
		$this->assertEqual($sqlOK, Piwik_Common::sanitizeInputValues($sql));
	}
	
	// sanitize php variables
	function test_sanitizeInputValues_php()
	{
		$a = true;
		$b = true;
		$this->assertEqual($b, Piwik_Common::sanitizeInputValues($a));
		$a = false;
		$b = false;
		$this->assertEqual($b, Piwik_Common::sanitizeInputValues($a));
		$a = null;
		$b = null;
		$this->assertEqual($b, Piwik_Common::sanitizeInputValues($a));
		$a = "";
		$b = "";
		$this->assertEqual($b, Piwik_Common::sanitizeInputValues($a));
	}
	
	
	// sanitize with magic quotes runtime on => shouldnt affect the result
	function test_sanitizeInputValues_magicquotesON()
	{
		$this->assertTrue(@set_magic_quotes_runtime(1));
		$this->assertTrue(@get_magic_quotes_runtime(), 1);
		
		$this->test_sanitizeInputValues_array1();
		$this->test_sanitizeInputValues_array2();
		$this->test_sanitizeInputValues_badString();
		$this->test_sanitizeInputValues_HTML();
	}
	
	// sanitize with magic quotes off
	function test_sanitizeInputValues_magicquotesOFF()
	{
		$this->assertTrue(@set_magic_quotes_runtime(0));
		$this->assertEqual(@get_magic_quotes_runtime(), 0);
		$this->test_sanitizeInputValues_array1();
		$this->test_sanitizeInputValues_array2();
		$this->test_sanitizeInputValues_badString();
		$this->test_sanitizeInputValues_HTML();
	}
	
    /**
     * emptyvarname => exception
     */
    function test_getRequestVar_emptyVarName()
    {
    	$_GET['']=1;
    	try {
    		$test = Piwik_Common::getRequestVar('');
        	$this->fail("Exception not raised.");
    	}
    	catch (Exception $expected) {
    		return;
        }
    }
	
    /**
     * nodefault Notype Novalue => exception
     */
    function test_getRequestVar_nodefaultNotypeNovalue()
    {
    	try {
    		$test = Piwik_Common::getRequestVar('test');
        	$this->fail("Exception not raised.");
    	}
    	catch (Exception $expected) {
    		return;
        }
    }
	
    /**
     *nodefault Notype WithValue => value
     */
    function test_getRequestVar_nodefaultNotypeWithValue()
    {
    	$_GET['test'] = 1413.431413;
    	$this->assertEqual( Piwik_Common::getRequestVar('test'), $_GET['test']);
    	
    }
	
    /**
     * nodefault Withtype WithValue => exception cos type not matching
     */
    function test_getRequestVar_nodefaultWithtypeWithValue()
    {
    	$_GET['test'] = 1413.431413;
    	
    	try {
    		$this->assertEqual( Piwik_Common::getRequestVar('test', null, 'string'), 
    						(string)$_GET['test']);
        	$this->fail("Exception not raised.");
    	}
    	catch (Exception $expected) {
    		return;
        }
    	
    }
	
	
    /**
     * nodefault Withtype WithValue => exception cos type not matching
     */
    function test_getRequestVar_nodefaultWithtypeWithValue2()
    {    	
    	try {
    		$this->assertEqual( Piwik_Common::getRequestVar('test', null, 'string'), 
    						null);
        	$this->fail("Exception not raised.");
    	}
    	catch (Exception $expected) {
    		return;
        }
    }
	
    /**
     * withdefault Withtype WithValue => value casted as type
     */
    function test_getRequestVar_withdefaultWithtypeWithValue()
    {
    	
    	$_GET['test'] = 1413.431413;
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 2, 'int'), 
    						2);
    }
	
    /**
     * withdefault Notype NoValue => default value
     */
    function test_getRequestVar_withdefaultNotypeNoValue()
    {
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 'default'), 
    						'default');
    }
	
    /**
     * withdefault Withtype NoValue =>default value casted as type
     */
    function test_getRequestVar_withdefaultWithtypeNoValue()
    {
    	
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 'default', 'string'), 
    						'default');
    }
	
    /**
     * integer as a default value / types
     * several tests
     */
    function test_getRequestVar_integerdefault()
    {
    	$_GET['test'] = 1413.431413;
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'int'), 45);
    	$_GET['test'] = '';
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'int'), 45);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'integer'), 45);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'float'), 45);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45.25, 'float'), 45.25);
    }
	
    /**
     * string as a default value / types
     * several tests
     */
    function test_getRequestVar_stringdefault()
    {
    	$_GET['test'] = "1413.431413";
    	$this->assertTrue( is_float(Piwik_Common::getRequestVar('test', 45, 'float')) );
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'int'), 45);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'string'), "1413.431413");
    	$_GET['test'] = '';
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'string'), '45');
    	$this->assertEqual( Piwik_Common::getRequestVar('test', "geaga", 'string'), "geaga");
    	$this->assertEqual( Piwik_Common::getRequestVar('test', "&#039;}{}}{}{}&#039;", 'string'), "&#039;}{}}{}{}&#039;");
    	
    }
	
    /**
     * array as a default value / types
     * several tests
     *
     */
    function test_getRequestVar_arraydefault()
    {
    	$test = array("test", 1345524, array("gaga"));
    	$_GET['test'] = $test;
    	
    	$this->assertEqual( Piwik_Common::getRequestVar('test', array(), 'array'), $test);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'string'), "45");
    	$this->assertEqual( Piwik_Common::getRequestVar('test', array(1), 'array'), $test);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 4, 'int'), 4);
    	
    	$_GET['test'] = '';
    	$this->assertEqual( Piwik_Common::getRequestVar('test', array(1), 'array'), array(1));
    	$this->assertEqual( Piwik_Common::getRequestVar('test', array(), 'array'), array());
    }
	
    /**
     * we give a number in a string and request for a number 
     * 	=> it should give the string casted as a number
     *
     */
    function test_getRequestVar_stringedNumericCastedNumeric()
    {
    	$test = "45645646";
    	$_GET['test'] = $test;
    	
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 1, 'int'), 45645646);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', 45, 'integer'), 45645646);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', "45454", 'string'), $test);
    	$this->assertEqual( Piwik_Common::getRequestVar('test', array(), 'array'), array());
    	
    }
    
    /**
     * no query string => false
     */
    function test_getParameterFromQueryString_noQuerystring()
    {
    	$urlQuery = "";
    	$parameter = "test''";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = false;
    	$this->assertEqual($result, $expectedResult);
    }
    
    /**
     * param not found => false
     */
    function test_getParameterFromQueryString_parameternotfound()
    {
    	
    	$urlQuery = "toto=mama&mama=titi";
    	$parameter = "tot";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = false;
    	$this->assertEqual($result, $expectedResult);
    }
    
    /**
     * empty parameter value => returns empty string
     */
    function test_getParameterFromQueryString_emptyParamValue()
    {
    	
    	$urlQuery = "toto=mama&mama=&tuytyt=teaoi";
    	$parameter = "mama";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = '';
    	$this->assertEqual($result, $expectedResult);
    }
    
    /**
     * twice the parameter => returns the last value in the url
     */
    function test_getParameterFromQueryString_twiceTheParameterInQuery()
    {
    	
    	$urlQuery = "toto=mama&mama=&tuytyt=teaoi&toto=mama second value";
    	$parameter = "toto";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = 'mama second value';
    	$this->assertEqual($result, $expectedResult);
    }
    
    /**
     * normal use case => parameter found
     */
    function test_getParameterFromQueryString_normalCase()
    {
    	
    	$urlQuery = "toto=mama&mama=&tuytyt=teaoi&toto=mama second value";
    	$parameter = "tuytyt";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = 'teaoi';
    	$this->assertEqual($result, $expectedResult);
    }
    
    /**
     * normal use case with a string with many strange characters
     */
    function test_getParameterFromQueryString_strangeChars()
    {
    	$urlQuery = 'toto=mama&mama=&tuytyt=Поиск в Интернете  Поиск страниц на русском _*()!$!£$^!£$%&toto=mama second value';
    	$parameter = "tuytyt";
    	$result = Piwik_Common::getParameterFromQueryString( $urlQuery, $parameter);
    	$expectedResult = 'Поиск в Интернете  Поиск страниц на русском _*()!$!£$^!£$%';
    	$this->assertEqual($result, $expectedResult);
    }

	function test_getParameterFromQueryString()
	{
		$tests = array(
			'x=1' => '1',
			'?x=1' => '1',
			'x[]=' => array(''),
			'x[]=1' => array('1'),
			'?x[]=1&x[]=2' => array('1', '2'),
			'?x%5b%5d=3&x[]=4' => array('3', '4'),
			'?x%5B]=5&x[%5D=6' => array('5', '6'),

			// don't unescape the value, otherwise it becomes
			//   ?x[]=A&y=1
			'?x%5B%5D=A%26y%3D1' => array('A%26y%3D1'),
			//   ?z=y&x[]=1
			'?z=y%26x%5b%5d%3d1' => false,
		);

		// use $i as the test index because simpletest uses sprintf() internally and the percent encoding causes an error
		$i = 0;
		foreach($tests as $test => $expected)
		{
			$i++;
			$this->assertFalse(Piwik_Common::getParameterFromQueryString($test, 'y'), $i);
			$this->assertTrue(Piwik_Common::getParameterFromQueryString($test, 'x') === $expected, $i);
		}
	}

    public function test_isValidFilenameValidValues()
    {
    
    	$valid = array(
    			 "test", "test.txt","test.......", "en-ZHsimplified",
    		);
    	foreach($valid as $toTest)
    	{
    		$this->assertTrue(Piwik_Common::isValidFilename($toTest), $toTest." not valid!");
    	}
    }

    public function test_isValidFilenameNotValidValues()
    {
    
    	$notvalid = array(
    			"../test", "/etc/htpasswd", '$var', ';test', '[bizarre]', '', ".htaccess", "very long long eogaioge ageja geau ghaeihieg heiagie aiughaeui hfilename",
    		);
    	foreach($notvalid as $toTest)
    	{
    		$this->assertFalse(Piwik_Common::isValidFilename($toTest), $toTest." valid but shouldn't!");
    	}
    }

	/**
	 * Data driven tests of getBrowserLanguage
	 */
	public function test_getBrowserLanguage()
	{
		$a1 = array( // user agent, browser language
				array( "en-gb", "en-gb" ),

				// filter quality attribute
				array( "en-us,en;q=0.5", "en-us,en" ),

				// bad user agents
				array( "en-us,chrome://global/locale/intl.properties", "en-us" ),

				// unregistered language tag
				array( "en,en-securid", "en" ),
				array( "en-securid,en", "en" ),
				array( "en-us,en-securid,en", "en-us,en" ),

				// accept private sub tags
				array( "en-us,x-en-securid", "en-us,x-en-securid" ),
				array( "en-us,en-x-securid", "en-us,en-x-securid" ),

				// filter arbitrary white space
				array( "en-us, en", "en-us,en" ),
				array( "en-ca, en-us ,en", "en-ca,en-us,en" ),

				// handle comments
				array( " ( comment ) en-us (another comment) ", "en-us" ),

				// handle quoted pairs (embedded in comments)
				array( " ( \( start ) en-us ( \) end ) ", "en-us" ),
				array( " ( \) en-ca, \( ) en-us ( \) ,en ) ", "en-us" ),
			);

		foreach($a1 as $testdata)
		{
			$res = Piwik_Common::getBrowserLanguage( $testdata[0] );
			$this->assertEqual( $testdata[1], $res );
		}
	}
    
	/**
	 * Data driven tests of extractCountryCodeFromBrowserLanguage
	 */
	public function test_extractCountryCodeFromBrowserLanguage()
	{
		$a1 = array( // browser language, valid countries, expected result
				array( "",                        array(),                 "xx" ),
				array( "",                        array("us"),             "xx" ),
				array( "en",                      array("us"),             "xx" ),
				array( "en-us",                   array("us"),             "us" ),
				array( "en-ca",                   array("us"),             "xx" ),
				array( "en-ca",                   array("us", "ca"),       "ca" ),
				array( "fr-fr,fr-ca",             array("us", "ca"),       "ca" ),
				array( "fr-fr;q=1.0,fr-ca;q=0.9", array("us", "ca"),       "ca" ),
				array( "fr-ca,fr;q=0.1",          array("us", "ca"),       "ca" ),
				array( "en-us,en;q=0.5", Piwik_Common::getCountriesList(), "us" ),
				array( "fr-ca,fr;q=0.1",          array("fr", "us", "ca"), "ca" ),
				array( "fr-fr,fr-ca",             array("fr", "us", "ca"), "fr" )
			);

		foreach($a1 as $testdata)
		{
			$this->assertEqual( $testdata[2], Piwik_Common::extractCountryCodeFromBrowserLanguage( $testdata[0], $testdata[1], true ));
			$this->assertEqual( $testdata[2], Piwik_Common::extractCountryCodeFromBrowserLanguage( $testdata[0], $testdata[1], false ));
		}
	}

	/**
	 * Data driven tests of extractCountryCodeFromBrowserLanguage
	 */
	public function test_extractCountryCodeFromBrowserLanguage_Infer()
	{
		$a1 = array( // browser language, valid countries, expected result (non-guess vs guess)
				array( "fr,en-us",       array("us", "ca"),       "us", "fr" ),
				array( "fr,en-us",       array("fr", "us", "ca"), "us", "fr" ),
				array( "fr,fr-fr,en-us", array("fr", "us", "ca"), "fr", "fr" ),
				array( "fr-fr,fr,en-us", array("fr", "us", "ca"), "fr", "fr" )
			);

		// do not infer country from language
		foreach($a1 as $testdata)
		{
			$this->assertEqual( $testdata[2], Piwik_Common::extractCountryCodeFromBrowserLanguage( $testdata[0], $testdata[1], $enableLanguageToCountryGuess = false ));
		}

		// infer country from language
		foreach($a1 as $testdata)
		{
			$this->assertEqual( $testdata[3], Piwik_Common::extractCountryCodeFromBrowserLanguage( $testdata[0], $testdata[1], $enableLanguageToCountryGuess = true ));
		}
	}

	/**
	 * Data driven tests of extractLanguageCodeFromBrowserLanguage
	 */
	public function test_extractLanguageCodeFromBrowserLanguage()
	{
		$a1 = array( // browser language, valid languages, expected result
				array( "fr-ca",          		  array("fr"),    "fr" ),
				array( "",                        array(),           "xx"    ),
				array( "",                        array("en"),       "xx"    ),
				array( "fr",                      array("en"),       "xx"    ),
				array( "en",                      array("en"),       "en"    ),
				array( "en-ca",                   array("en-ca"),    "en-ca" ),
				array( "en-ca",                   array("en"),       "en"    ),
				array( "fr,en-us",                array("fr", "en"), "fr"    ),
				array( "fr,en-us",                array("en", "fr"), "fr"    ),
				array( "fr-fr,fr-ca",             array("fr"),       "fr"    ),
				array( "fr-fr,fr-ca",             array("fr-ca"),    "fr-ca" ),
				array( "fr-fr;q=1.0,fr-ca;q=0.9", array("fr-ca"),    "fr-ca" ),
				array( "fr-ca,fr;q=0.1",          array("fr-ca"),    "fr-ca" ),
			);

		foreach($a1 as $testdata)
		{
			$this->assertEqual( $testdata[2], Piwik_Common::extractLanguageCodeFromBrowserLanguage( $testdata[0], $testdata[1] ), "test with {$testdata[0]} failed, expected {$testdata[2]}");
		}
	}
	
	public function test_SearchEngines_areDefinedCorrectly()
	{
		require_once "DataFiles/SearchEngines.php";

		$searchEngines = array();
		foreach($GLOBALS['Piwik_SearchEngines'] as $host => $info)
		{
			if(isset($info[2]) && $info[2] !== false)
			{
				$this->assertTrue(strrpos($info[2], "{k}") !== false, $host . " search URL is not defined correctly, must contain the macro {k}");
			}

			if(!array_key_exists($info[0], $searchEngines))
			{
				$searchEngines[$info[0]] = true;

				$this->assertTrue(strpos($host, '{}') === false, $host . " search URL is the master record and should not contain {}");
			}
		}
	}
	
	public function test_extractSearchEngineInformationFromUrl()
	{
		$urls = array(
			// normal case
			'http://uk.search.yahoo.com/search?p=piwik&ei=UTF-8&fr=moz2'
				=> array('name' => 'Yahoo!', 'keywords' => 'piwik'),
			
			// test request trimmed and capitalized
			'http://www.google.com/search?hl=en&q=+piWIk+&btnG=Google+Search&aq=f&oq='
				=> array('name' => 'Google', 'keywords' => 'piwik'),
				
			// testing special case of Google images
			'http://images.google.com/imgres?imgurl=http://www.linux-corner.info/snapshot1.png&imgrefurl=http://www.oxxus.net/blog/archives/date/2007/10/page/41/&usg=__-xYvnp1IKpRZKjRDQVhpfExMkuM=&h=781&w=937&sz=203&hl=en&start=1&tbnid=P9LqKMIbdhlg-M:&tbnh=123&tbnw=148&prev=/images%3Fq%3Dthis%2Bmy%2Bquery%2Bwith%2Bhttp://domain%2Bname%2Band%2Bstrange%2Bcharacters%2B%2526%2B%255E%2B%257C%2B%253C%253E%2B%2525%2B%2522%2B%2527%2527%2BEOL%26gbv%3D2%26hl%3Den%26sa%3DG'
				=> array('name' => 'Google Images', 'keywords' => 'this my query with http://domain name and strange characters & ^ | <> % " \'\' eol'),
			
			'http://www.google.fr/search?hl=en&q=%3C%3E+%26test%3B+piwik+%26quot%3B&ei=GcXJSb-VKoKEsAPmnIjzBw&sa=X&oi=revisions_inline&ct=unquoted-query-link'
				=> array('name' => 'Google', 'keywords' => '<> &test; piwik &quot;'),
				
			// testing Baidu special case (several variable names possible, and custom encoding)
			// see http://dev.piwik.org/trac/ticket/589
			
			// keyword is in "wd" 
			'http://www.baidu.com/s?ie=gb2312&bs=%BF%D5%BC%E4+hao123+%7C+%B8%FC%B6%E0%3E%3E&sr=&z=&cl=3&f=8&tn=baidu&wd=%BF%D5%BC%E4+%BA%C3123+%7C+%B8%FC%B6%E0%3E%3E&ct=0'
				=> array('name' => 'Baidu', 'keywords' => '空间 好123 | 更多>>'),

			// keyword is in "word"
			'http://www.baidu.com/s?kw=&sc=web&cl=3&tn=sitehao123&ct=0&rn=&lm=&ie=gb2312&rs2=&myselectvalue=&f=&pv=&z=&from=&word=%B7%E8%BF%F1%CB%B5%D3%A2%D3%EF+%D4%DA%CF%DF%B9%DB%BF%B4'
				=> array('name' => 'Baidu', 'keywords' => '疯狂说英语 在线观看'),

			'http://www.baidu.com/s?wd=%C1%F7%D0%D0%C3%C0%D3%EF%CF%C2%D4%D8'
				=> array('name' => 'Baidu', 'keywords' => '流行美语下载'),

			'http://web.gougou.com/search?search=%E5%85%A8%E9%83%A8&id=1'
				=> array('name' => 'Baidu', 'keywords' => '全部'),
			
			'http://www.google.cn/search?hl=zh-CN&q=%E6%B5%8F%E8%A7%88%E5%85%AC%E4%BA%A4%E5%9C%B0%E9%93%81%E7%AB%99%E7%82%B9%E4%BF%A1%E6%81%AF&btnG=Google+%E6%90%9C%E7%B4%A2&meta=cr%3DcountryCN&aq=f&oq='
				=> array('name' => 'Google', 'keywords' => '浏览公交地铁站点信息'),
			
			// testing other exotic unicode characters
			'http://www.yandex.com/yandsearch?text=%D1%87%D0%B0%D1%81%D1%82%D0%BE%D1%82%D0%B0+%D1%80%D0%B0%D1%81%D0%BF%D0%B0%D0%B4%D0%B0+%D1%81%D1%82%D0%B5%D0%BA%D0%BB%D0%B0&stpar2=%2Fh1%2Ftm11%2Fs1&stpar4=%2Fs1&stpar1=%2Fu0%27,%20%27%D1%87%D0%B0%D1%81%D1%82%D0%BE%D1%82%D0%B0+%D1%80%D0%B0%D1%81%D0%BF%D0%B0%D0%B4%D0%B0+%D1%81%D1%82%D0%B5%D0%BA%D0%BB%D0%B0'
				=> array('name' => 'Yandex', 'keywords' => 'частота распада стекла'),

			'http://www.yandex.ru/yandsearch?text=%D1%81%D0%BF%D0%BE%D1%80%D1%82%D0%B7%D0%B4%D1%80%D0%B0%D0%B2'
				=> array('name' => 'Yandex', 'keywords' => 'спортздрав'),
			
			'http://www.google.ge/search?hl=en&q=%E1%83%A1%E1%83%90%E1%83%A5%E1%83%90%E1%83%A0%E1%83%97%E1%83%95%E1%83%94%E1%83%9A%E1%83%9D&btnG=Google+Search' 
				=> array('name' => 'Google', 'keywords' => 'საქართველო'),

			// new Google url formats
			'http://www.google.com/url?sa=t&source=web&ct=res&cd=7&url=http%3A%2F%2Fwww.example.com%2Fmypage.htm&ei=0SjdSa-1N5O8M_qW8dQN&rct=j&q=flowers&usg=AFQjCNHJXSUh7Vw7oubPaO3tZOzz-F-u_w&sig2=X8uCFh6IoPtnwmvGMULQfw'
				=> array('name' => 'Google', 'keywords' => 'flowers'),
			'http://www.google.com/webhp?tab=mw#hl=en&source=hp&q=test+hash&btnG=Google+Search&aq=f&aqi=&aql=&oq=&fp=22b4dcbb1403dc0f'
				=> false,
			'http://www.google.com/#hl=en&source=hp&q=test+hash&aq=f&aqi=n1g5g-s1g1g-s1g2&aql=&oq=&fp=22b4dcbb1403dc0f'
				=> false,
				
			// new Google image format
			'http://www.google.com/imgres?imgurl=http://www.imagedomain.com/zoom/34782_ZOOM.jpg&imgrefurl=http://www.mydomain.com/product/Omala-Govindra-Tank-XS-Brown-and-Chile.html&usg=__BD6z_JrJRAFjScDRhj4Tp8Vm_Zo=&h=610&w=465&sz=248&hl=en&start=3&itbs=1&tbnid=aiNVNce9-ZYAPM:&tbnh=136&tbnw=104&prev=/images%3Fq%3DFull%2BSupport%2BTummy%26hl%3Den%26safe%3Doff%26sa%3DG%26gbv%3D2%26tbs%3Disch:1'
				=> array('name' => 'Google Images', 'keywords' => 'full support tummy'),

			// Google CSE is not standard google
			'http://www.google.com/cse?cx=006944612449134755049%3Ahq5up-97k4u&cof=FORID%3A10&q=piwik&ad=w9&num=10&rurl=http%3A%2F%2Fwww.homepagle.com%2Fsearch.php%3Fcx%3D006944612449134755049%253Ahq5up-97k4u%26cof%3DFORID%253A10%26q%3D89'
				=> array('name' => 'Google Custom Search', 'keywords' => 'piwik'),

			// Powered by Google CSE
			'http://www.cathoogle.com/results?cx=partner-pub-6379407697620666%3Alil1v7i1hv0&cof=FORID%3A9&safe=active&q=i+love+piwik&sa=Cathoogle+Search&siteurl=www.cathoogle.com%2F#867'
				=> array('name' => 'Google Custom Search', 'keywords' => 'i love piwik'),

			// Google advanced search
			'http://www.google.ca/search?hl=en&as_q=web+analytics&as_epq=real+time&as_oq=gpl+open+source&as_eq=oracle&num=10&lr=&as_filetype=&ft=i&as_sitesearch=&as_qdr=all&as_rights=&as_occt=any&cr=&as_nlo=&as_nhi=&safe=images'
				=> array('name' => 'Google', 'keywords' => 'web analytics gpl or open or source "real time" -oracle'),

			'http://www.google.ca/url?sa=t&source=web&cd=1&ved=0CBQQFjAA&url=http%3A%2F%2Fwww.robocoder.ca%2F&rct=j&q=web%20analytics%20gpl%20OR%20open%20OR%20source%20%22real%20time%22%20-sco&ei=zv6KTILkGsG88gaxoqz9Cw&usg=AFQjCNEv2Mp3ruU8YCMI40Pqo9ijjXvsUA'
				=> array('name' => 'Google', 'keywords' => 'web analytics gpl or open or source "real time" -sco'),

			// Google cache
			'http://webcache.googleusercontent.com/search?q=cache:CD2SncROLs4J:piwik.org/blog/2010/04/piwik-0-6-security-advisory/+piwik+security&cd=1&hl=en&ct=clnk'
				=> array('name' => 'Google', 'keywords' => 'piwik security'),

			// Bing cache
			'http://cc.bingj.com/cache.aspx?q=web+analytics&d=5020318678516316&mkt=en-CA&setlang=en-CA&w=6ea8ea88,ff6c44df'
				=> array('name' => 'Bing', 'keywords' => 'web analytics'),

			// Bing Mobile
			'http://m.bing.com/search/search.aspx?Q=piwik&d=&dl=&pq=&a=results&MID=8015'
				=> array('name' => 'Bing', 'keywords' => 'piwik'),

			// Bing image search has a special URL
			'http://www.bing.com/images/search?q=piwik&go=&form=QBIL'
				=> array('name' => 'Bing Images', 'keywords' => 'piwik'),

/*
			// Bing mobile image search has a special URL
			'http://m.bing.com/search/search.aspx?A=imageresults&Q=piwik&D=Image&MID=8015&SI=0&PN=0&SCO=0'
				=> array('name' => 'Bing Images', 'keywords' => 'piwik'),
*/

/*
			// Yahoo (Bing-powered) cache
			'http://74.6.239.84/search/srpcache?ei=UTF-8&p=web+analytics&fr=yfp-t-715&u=http://cc.bingj.com/cache.aspx?q=web+analytics&d=5020318680482405&mkt=en-CA&setlang=en-CA&w=a68d7af0,873cfeb0&icp=1&.intl=ca&sig=x6MgjtrDYvsxi8Zk2ZX.tw--'
				=> array('name' => 'Yahoo', 'keywords' => 'web analytics'),

			'http://74.6.239.185/search/srpcache?ei=UTF-8&p=piwik&fr=yfp-t-964&fp_ip=ca&u=http://cc.bingj.com/cache.aspx?q=piwik&d=4770519086662477&mkt=en-US&setlang=en-US&w=f4bc05d8,8c8af2e3&icp=1&.intl=us&sig=PXmPDNqapxSQ.scsuhIpZA--'
				=> array('name' => 'Yahoo', 'keywords' => 'piwik'),
*/

			// InfoSpace
			'http://infospace.com/ispace/ws/results/Web/real time web analytics/1/417/TopNavigation/Relevance/iq=true/zoom=off/enginefilter=all/_iceUrlFlag=7?_IceUrl=true'
				=> array('name' => 'InfoSpace', 'keywords' => 'real time web analytics'),

			// Powered by InfoSpace metasearch
			'http://search.kiwee.com/pemonitorhosted/ws/results/Web/mobile analytics/1/417/TopNavigation/Source/iq=true/zoom=off/_iceUrlFlag=7?_IceUrl=true'
				=> array('name' => 'InfoSpace', 'keywords' => 'mobile analytics'),
			
			'http://ws.copernic.com/pemonitorhosted/ws/results/Web/piwik/1/417/TopNavigation/Source/iq=true/zoom=off/_iceUrlFlag=7?_IceUrl=true'
				=> array('name' => 'InfoSpace', 'keywords' => 'piwik'),
			
			// 123people
			'http://www.123people.de/s/piwik'
				=> array('name' => '123people', 'keywords' => 'piwik'),

			// msxml.excite.com (using regex)
			'http://msxml.excite.com/excite/ws/results/Images/test/1/408/TopNavigation/Relevance/iq=true/zoom=off/_iceUrlFlag=7?_IceUrl=true&padv=qall%3dpiwik%26qphrase%3d%26qany%3d%26qnot%3d'
				=> array('name' => 'Excite', 'keywords' => 'test'),

			// test that master record is used to backfill subsequent rows
			'http://www.baidu.com/?wd=test1'
				=> array('name' => 'Baidu', 'keywords' => 'test1'),
			'http://tieba.baidu.com/?kw=test2'
				=> array('name' => 'Baidu', 'keywords' => 'test2'),
			'http://web.gougou.com/?search=test3'
				=> array('name' => 'Baidu', 'keywords' => 'test3'),
		);
		
		foreach($urls as $refererUrl => $expectedReturnedValue) {
			$returnedValue = Piwik_Common::extractSearchEngineInformationFromUrl($refererUrl);
			$exported = var_export($returnedValue,true);
			$result = $expectedReturnedValue === $returnedValue;
			$this->assertTrue($result);
			if(!$result) {
				$this->fail("error in extracting from $refererUrl got ".$exported."<br>");
			}
		}
	}

	function test_getLossyUrl()
	{
		$urls = array(
			'example.com' => 'example.com',
			'm.example.com' => 'example.com',
			'www.example.com' => 'example.com',
			'search.example.com' => 'example.com',
			'example.ca' => 'example.{}',
			'us.example.com' => '{}.example.com',
			'www.m.example.ca' => 'example.{}',
		);
		foreach($urls as $input => $expected)
		{
			$this->assertEqual( Piwik_Common::getLossyUrl($input), $expected);
		}
	}

	function test_getLastElementFromList()
	{
		$tests = array(
			'' => '',
			'a' => 'a',
			' a ' => 'a',
			' a, b' => 'b',
			'a ,b ' => 'b',
			'a,' => '',
		);

		foreach($tests as $csv => $expected)
		{
			$this->assertEqual( Piwik_Common::getLastElementFromList($csv), $expected);

			$this->assertEqual( Piwik_Common::getLastElementFromList($csv . ', d', 'd'), $expected);
		}
	}

	function test_getProxyFromHeader()
	{
		$ips = array(
			'0.0.0.0',
			'72.14.204.99',
			'127.0.0.1',
			'169.254.0.1',
			'208.80.152.2',
			'224.0.0.1',
		);

		// no proxies
		foreach($ips as $ip)
		{
			$this->assertEqual( Piwik_Common::getProxyFromHeader($ip, array()), $ip, $ip );
		}

		// 1.1.1.1 is not a trusted proxy
		$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
		foreach($ips as $ip)
		{
			$_SERVER['HTTP_X_FORWARDED_FOR'] = '';
			$this->assertEqual( Piwik_Common::getProxyFromHeader('1.1.1.1', array('HTTP_X_FORWARDED_FOR')), '1.1.1.1', $ip);
		}

		// 1.1.1.1 is a trusted proxy
		$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
		foreach($ips as $ip)
		{
			$_SERVER['HTTP_X_FORWARDED_FOR'] = $ip;
			$this->assertEqual( Piwik_Common::getProxyFromHeader('1.1.1.1', array('HTTP_X_FORWARDED_FOR')), $ip, $ip);

			$_SERVER['HTTP_X_FORWARDED_FOR'] = '1.2.3.4, ' . $ip;
			$this->assertEqual( Piwik_Common::getProxyFromHeader('1.1.1.1', array('HTTP_X_FORWARDED_FOR')), $ip, $ip);

			// misconfiguration
			$_SERVER['HTTP_X_FORWARDED_FOR'] = $ip . ', 1.1.1.1';
			$this->assertEqual( Piwik_Common::getProxyFromHeader('1.1.1.1', array('HTTP_X_FORWARDED_FOR')), $ip, $ip);
		}
	}
}
