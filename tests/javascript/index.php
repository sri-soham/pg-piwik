<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
                    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title>piwik.js: Piwik Unit Tests</title>
 <script type="text/javascript">
function getToken() {
	return "<?php $token = md5(uniqid(mt_rand(), true)); echo $token; ?>";
}
<?php
$sqlite = false;
if (file_exists("enable_sqlite")) {
	if (extension_loaded('sqlite')) {
		$sqlite = true;
	}
}

if ($sqlite) {
  echo '
var _paq = _paq || [];
_paq.push(["setSiteId", 1]);
_paq.push(["setTrackerUrl", "piwik.php"]);
_paq.push(["setCustomData", { "token" : getToken() }]);
_paq.push(["trackPageView", "Asynchronous tracker"]);';
}
?>
 </script>
 <script src="../../js/piwik.js" type="text/javascript"></script>
 <script src="piwiktest.js" type="text/javascript"></script>
 <script src="../../libs/jquery/jquery.js" type="text/javascript"></script>
 <link rel="stylesheet" href="assets/qunit.css" type="text/css" media="screen" />
 <script src="assets/qunit.js" type="text/javascript"></script>
 <script type="text/javascript">
<!--
/**
 * Add random number to url to stop IE from caching
 *
 * @example url("data/test.html")
 * @result "data/test.html?10538358428943"
 *
 * @example url("data/test.php?foo=bar")
 * @result "data/test.php?foo=bar&10538358345554"
 */
function url(value) {
        return value + (/\?/.test(value) ? "&" : "?") + new Date().getTime() + "" + parseInt(Math.random()*100000);
}
//-->
 </script>
</head>
<body>
<div style="display:none;"><a href="http://piwik.org/qa">First anchor link</a></div>

 <h1 id="qunit-header">piwik.js: Piwik Unit Tests</h1>
 <h2 id="qunit-banner"></h2>
 <div id="qunit-testrunner-toolbar"></div>
 <h2 id="qunit-userAgent"></h2>

 <div id="other" style="display:none;">
  <div id="div1"></div>
  <iframe name="iframe2"></iframe>
  <iframe name="iframe3"></iframe>
  <iframe name="iframe4"></iframe>
  <iframe name="iframe5"></iframe>
  <iframe name="iframe6"></iframe>
  <iframe name="iframe7"></iframe>
  <ul>
    <li><a id="click1" href="javascript:document.getElementById('div1').innerHTML='&lt;iframe src=&quot;http://example.com&quot;&gt;&lt;/iframe&gt;';void(0)" class="clicktest">ignore: implicit (JavaScript href)</a></li>
    <li><a id="click2" href="http://example.org" target="iframe2" class="piwik_ignore clicktest">ignore: explicit</a></li>
    <li><a id="click3" href="example.php" target="iframe3" class="clicktest">ignore: implicit (localhost)</a></li>
    <li><a id="click4" href="http://example.net" target="iframe4" class="clicktest">outlink: implicit (outbound URL)</a></li>
    <li><a id="click5" href="example.html" target="iframe5" class="piwik_link clicktest">outlink: explicit (localhost)</a></li>
    <li><a id="click6" href="example.pdf" target="iframe6" class="clicktest">download: implicit (file extension)</a></li>
    <li><a id="click7" href="example.word" target="iframe7" class="piwik_download clicktest">download: explicit</a></li>
  </ul>
 </div>

 <ol id="qunit-tests"></ol>

 <div id="main" style="display:none;"></div>

 <script>
$(document).ready(function () {

	test("Basic requirements", function() {
		expect(5);

		equals( typeof encodeURIComponent, 'function', 'encodeURIComponent' );
		ok( RegExp, "RegExp" );
		ok( Piwik, "Piwik" );
		ok( piwik_log, "piwik_log" );
		equals( typeof piwik_track, 'undefined', "piwk_track" );
	});

	module("piwik test");
	test("Test API - addPlugin(), getTracker(), getHook(), and hook", function() {
		expect(6);

		ok( Piwik.addPlugin, "Piwik.addPlugin" );

		var tracker = Piwik.getTracker();

		equals( typeof tracker, 'object', "Piwik.getTracker()" );
		equals( typeof tracker.getHook, 'function', "test Tracker getHook" );
		equals( typeof tracker.hook, 'object', "test Tracker hook" );
		equals( typeof tracker.getHook('test'), 'object', "test Tracker getHook('test')" );
		equals( typeof tracker.hook.test, 'object', "test Tracker hook.test" );
	});

	module("piwik");
	test("Tracker escape and unescape wrappers", function() {
		expect(4);

		var tracker = Piwik.getTracker();

		equals( typeof tracker.hook.test._escape, 'function', 'escapeWrapper' );
		equals( typeof tracker.hook.test._unescape, 'function', 'unescapeWrapper' );

		equals( tracker.hook.test._escape("&=?;/#"), '%26%3D%3F%3B%2F%23', 'escapeWrapper()' );
		equals( tracker.hook.test._unescape("%26%3D%3F%3B%2F%23"), '&=?;/#', 'unescapeWrapper()' );
	});

	test("Tracker getHostname(), getParameter(), and urlFixup()", function() {
		expect(24);

		var tracker = Piwik.getTracker();

		equals( typeof tracker.hook.test._getHostname, 'function', 'getHostname' );
		equals( typeof tracker.hook.test._getParameter, 'function', 'getParameter' );

		equals( tracker.hook.test._getHostname('http://example.com'), 'example.com', 'http://example.com');
		equals( tracker.hook.test._getHostname('http://example.com/'), 'example.com', 'http://example.com/');
		equals( tracker.hook.test._getHostname('http://example.com/index'), 'example.com', 'http://example.com/index');
		equals( tracker.hook.test._getHostname('http://example.com/index?q=xyz'), 'example.com', 'http://example.com/index?q=xyz');
		equals( tracker.hook.test._getHostname('http://example.com/?q=xyz'), 'example.com', 'http://example.com/?q=xyz');
		equals( tracker.hook.test._getHostname('http://example.com/?q=xyz#hash'), 'example.com', 'http://example.com/?q=xyz#hash');
		equals( tracker.hook.test._getHostname('http://example.com#hash'), 'example.com', 'http://example.com#hash');
		equals( tracker.hook.test._getHostname('http://example.com/#hash'), 'example.com', 'http://example.com/#hash');
		equals( tracker.hook.test._getHostname('http://example.com:80'), 'example.com', 'http://example.com:80');
		equals( tracker.hook.test._getHostname('http://example.com:80/'), 'example.com', 'http://example.com:80/');
		equals( tracker.hook.test._getHostname('https://example.com/'), 'example.com', 'https://example.com/');
		equals( tracker.hook.test._getHostname('http://user@example.com/'), 'example.com', 'http://user@example.com/');
		equals( tracker.hook.test._getHostname('http://user:password@example.com/'), 'example.com', 'http://user:password@example.com/');

		equals( tracker.hook.test._getParameter('http://piwik.org/', 'q'), '', 'no query');
		equals( tracker.hook.test._getParameter('http://piwik.org/?q=test', 'q'), 'test', '?q');
		equals( tracker.hook.test._getParameter('http://piwik.org/?p=test1&q=test2', 'q'), 'test2', '&q');
		equals( tracker.hook.test._getParameter('http://piwik.org/?q=http%3a%2f%2flocalhost%2f%3fr%3d1%26q%3dfalse', 'q'), 'http://localhost/?r=1&q=false', 'url');

		same( tracker.hook.test._urlFixup( 'webcache.googleusercontent.com', 'http://webcache.googleusercontent.com/search?q=cache:CD2SncROLs4J:piwik.org/blog/2010/04/piwik-0-6-security-advisory/+piwik+security&cd=1&hl=en&ct=clnk', '' ),
				['piwik.org', 'http://piwik.org/qa', ''], 'webcache.googleusercontent.com' );

		same( tracker.hook.test._urlFixup( 'cc.bingj.com', 'http://cc.bingj.com/cache.aspx?q=web+analytics&d=5020318678516316&mkt=en-CA&setlang=en-CA&w=6ea8ea88,ff6c44df', '' ),
				['piwik.org', 'http://piwik.org/qa', ''], 'cc.bingj.com' );

		same( tracker.hook.test._urlFixup( '74.6.239.185', 'http://74.6.239.185/search/srpcache?ei=UTF-8&p=piwik&fr=yfp-t-964&fp_ip=ca&u=http://cc.bingj.com/cache.aspx?q=piwik&d=4770519086662477&mkt=en-US&setlang=en-US&w=f4bc05d8,8c8af2e3&icp=1&.intl=us&sig=PXmPDNqapxSQ.scsuhIpZA--', '' ),
				['piwik.org', 'http://piwik.org/qa', ''], 'yahoo cache (1)' );

		same( tracker.hook.test._urlFixup( '74.6.239.84', 'http://74.6.239.84/search/srpcache?ei=UTF-8&p=web+analytics&fr=yfp-t-715&u=http://cc.bingj.com/cache.aspx?q=web+analytics&d=5020318680482405&mkt=en-CA&setlang=en-CA&w=a68d7af0,873cfeb0&icp=1&.intl=ca&sig=x6MgjtrDYvsxi8Zk2ZX.tw--', '' ),
				['piwik.org', 'http://piwik.org/qa', ''], 'yahoo cache (2)' );

		same( tracker.hook.test._urlFixup( 'translate.googleusercontent.com', 'http://translate.googleusercontent.com/translate_c?hl=en&ie=UTF-8&sl=en&tl=fr&u=http://piwik.org/&prev=_t&rurl=translate.google.com&twu=1&usg=ALkJrhirI_ijXXT7Ja_aDGndEJbE7pJqpQ', '' ),
				['piwik.org', 'http://piwik.org/', 'http://translate.googleusercontent.com/translate_c?hl=en&ie=UTF-8&sl=en&tl=fr&u=http://piwik.org/&prev=_t&rurl=translate.google.com&twu=1&usg=ALkJrhirI_ijXXT7Ja_aDGndEJbE7pJqpQ'], 'translate.googleusercontent.com' );
	});

	test("Tracker setDomains() and isSiteHostName()", function() {
		expect(9);

		var tracker = Piwik.getTracker();

		equals( typeof tracker.hook.test._isSiteHostName, 'function', "isSiteHostName" );

		// test wildcards
		tracker.setDomains( ['*.Example.com'] );
		// skip test if testing on localhost
		ok( window.location.hostname != 'localhost' ? !tracker.hook.test._isSiteHostName('localhost') : true, '!isSiteHostName("localhost")' );

		ok( !tracker.hook.test._isSiteHostName('google.com'), '!isSiteHostName("google.com")' );
		ok( tracker.hook.test._isSiteHostName('example.com'), 'isSiteHostName("example.com")' );
		ok( tracker.hook.test._isSiteHostName('www.example.com'), 'isSiteHostName("www.example.com")' );
		ok( tracker.hook.test._isSiteHostName('www.sub.example.com'), 'isSiteHostName("www.sub.example.com")' );

		tracker.setDomains( 'dev.piwik.org' );
		ok( !tracker.hook.test._isSiteHostName('piwik.org'), '!isSiteHostName("dev.piwik.org")' );
		ok( tracker.hook.test._isSiteHostName('dev.piwik.org'), 'isSiteHostName("dev.piwik.org")' );
		ok( !tracker.hook.test._isSiteHostName('piwik.example.org'), '!isSiteHostName("piwik.example.org")');
	});

	test("Tracker getClassesRegExp()", function() {
		expect(0);

		var tracker = Piwik.getTracker();

		equals( typeof tracker.hook.test._getClassesRegExp, 'function', "getClassesRegExp" );

		var download = tracker.hook.test._getClassesRegExp([], 'download');
		ok( download.test('piwik_download'), 'piwik_download (default)' );

		var outlink = tracker.hook.test._getClassesRegExp([], 'link');
		ok( outlink.test('piwik_link'), 'piwik_link (default)' );

	});

	test("Tracker setIgnoreClasses() and getClassesRegExp(ignore)", function() {
		expect(14);

		var tracker = Piwik.getTracker();

		var ignore = tracker.hook.test._getClassesRegExp([], 'ignore');
		ok( ignore.test('piwik_ignore'), '[1] piwik_ignore' );
		ok( !ignore.test('pk_ignore'), '[1] !pk_ignore' );
		ok( !ignore.test('apiwik_ignore'), '!apiwik_ignore' );
		ok( !ignore.test('piwik_ignorez'), '!piwik_ignorez' );
		ok( ignore.test('abc piwik_ignore xyz'), 'abc piwik_ignore xyz' );

		tracker.setIgnoreClasses( 'my_download' );
		ignore = tracker.hook.test._getClassesRegExp(['my_download'], 'ignore');
		ok( ignore.test('piwik_ignore'), '[2] piwik_ignore' );
		ok( !ignore.test('pk_ignore'), '[2] !pk_ignore' );
		ok( ignore.test('my_download'), 'my_download' );
		ok( ignore.test('abc piwik_ignore xyz'), 'abc piwik_ignore xyz' );
		ok( ignore.test('abc my_download xyz'), 'abc my_download xyz' );

		tracker.setIgnoreClasses( ['my_download', 'my_outlink'] );
		ignore = tracker.hook.test._getClassesRegExp(['my_download','my_outlink'], 'ignore');
		ok( ignore.test('piwik_ignore'), '[3] piwik_ignore' );
		ok( !ignore.test('pk_ignore'), '[3] !pk_ignore' );
		ok( ignore.test('my_download'), 'my_download' );
		ok( ignore.test('my_outlink'), 'my_outlink' );
	});

	test("Tracker hasCookies(), getCookie(), setCookie()", function() {
		expect(2);

		var tracker = Piwik.getTracker();

		ok( tracker.hook.test._hasCookies() == '1', 'hasCookies()' );

		var cookieName = '_pk_test_harness' + Math.random(),
		    expectedValue = Math.random();
		tracker.hook.test._setCookie( cookieName, expectedValue );
		equals( tracker.hook.test._getCookie( cookieName ), expectedValue, 'getCookie(), setCookie()' );
	});

	test("Tracker setDownloadExtensions(), addDownloadExtensions(), setDownloadClass(), setLinkClass(), and getLinkType()", function() {
		expect(29);

		var tracker = Piwik.getTracker();

		equals( typeof tracker.hook.test._getLinkType, 'function', 'getLinkType' );

		equals( tracker.hook.test._getLinkType('something', 'goofy.html', false), 'link', 'implicit link' );
		equals( tracker.hook.test._getLinkType('something', 'goofy.pdf', false), 'link', 'implicit link' );

		equals( tracker.hook.test._getLinkType('piwik_download', 'piwiktest.ext', true), 'download', 'piwik_download' );
		equals( tracker.hook.test._getLinkType('abc piwik_download xyz', 'piwiktest.ext', true), 'download', 'abc piwik_download xyz' );
		equals( tracker.hook.test._getLinkType('piwik_link', 'piwiktest.asp', true), 'link', 'piwik_link' );
		equals( tracker.hook.test._getLinkType('abc piwik_link xyz', 'piwiktest.asp', true), 'link', 'abc piwik_link xyz' );
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.txt', true), 'download', 'download extension' );
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.ext', true), 0, '[1] link (default)' );

		equals( tracker.hook.test._getLinkType('something', 'file.zip', true), 'download', 'download file.zip' );
		equals( tracker.hook.test._getLinkType('something', 'index.php?name=file.zip#anchor', true), 'download', 'download file.zip (anchor)' );
		equals( tracker.hook.test._getLinkType('something', 'index.php?name=file.zip&redirect=yes', true), 'download', 'download file.zip (is param)' );
		equals( tracker.hook.test._getLinkType('something', 'file.zip?mirror=true', true), 'download', 'download file.zip (with param)' );

		tracker.setDownloadExtensions('pk');
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.pk', true), 'download', '[1] .pk == download extension' );
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.txt', true), 0, '.txt =! download extension' );

		tracker.addDownloadExtensions('xyz');
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.pk', true), 'download', '[2] .pk == download extension' );
		equals( tracker.hook.test._getLinkType('something', 'piwiktest.xyz', true), 'download', '.xyz == download extension' );

		tracker.setDownloadClass('my_download');
		equals( tracker.hook.test._getLinkType('my_download', 'piwiktest.ext', true), 'download', 'my_download' );
		equals( tracker.hook.test._getLinkType('abc my_download xyz', 'piwiktest.ext', true), 'download', 'abc my_download xyz' );
		equals( tracker.hook.test._getLinkType('piwik_download', 'piwiktest.ext', true), 'download', 'download (default)' );

		tracker.setDownloadClasses(['a', 'b']);
		equals( tracker.hook.test._getLinkType('abc piwik_download', 'piwiktest.ext', true), 'download', 'download (default)' );
		equals( tracker.hook.test._getLinkType('abc a', 'piwiktest.ext', true), 'download', 'download (a)' );
		equals( tracker.hook.test._getLinkType('b abc', 'piwiktest.ext', true), 'download', 'download (b)' );

		tracker.setLinkClass('my_link');
		equals( tracker.hook.test._getLinkType('my_link', 'piwiktest.ext', true), 'link', 'my_link' );
		equals( tracker.hook.test._getLinkType('abc my_link xyz', 'piwiktest.ext', true), 'link', 'abc my_link xyz' );
		equals( tracker.hook.test._getLinkType('piwik_link', 'piwiktest.ext', true), 'link', '[2] link default' );

		tracker.setLinkClasses(['c', 'd']);
		equals( tracker.hook.test._getLinkType('abc piwik_link', 'piwiktest.ext', true), 'link', 'link (default)' );
		equals( tracker.hook.test._getLinkType('abc c', 'piwiktest.ext', true), 'link', 'link (c)' );
		equals( tracker.hook.test._getLinkType('d abc', 'piwiktest.ext', true), 'link', 'link (d)' );
	});

	test("JSON", function() {
		expect(10);

		var tracker = Piwik.getTracker(), dummy;

		equals( tracker.hook.test._stringify(true), 'true', 'Boolean (true)' );
		equals( tracker.hook.test._stringify(false), 'false', 'Boolean (false)' );
		equals( tracker.hook.test._stringify(42), '42', 'Number' );
		equals( tracker.hook.test._stringify("ABC"), '"ABC"', 'String' );

		var d = new Date();
		d.setTime(1240013340000);
		ok( tracker.hook.test._stringify(d) == '"2009-04-18T00:09:00Z"'
		|| tracker.hook.test._stringify(d) == '"2009-04-18T00:09:00.000Z"', 'Date');

		equals( tracker.hook.test._stringify(null), 'null', 'null' );
		equals( typeof tracker.hook.test._stringify(dummy), 'undefined', 'undefined' );
		equals( tracker.hook.test._stringify([1, 2, 3]), '[1,2,3]', 'Array of numbers' );
		equals( tracker.hook.test._stringify({'key' : 'value'}), '{"key":"value"}', 'Object (members)' );
		equals( tracker.hook.test._stringify(
			[ {'domains' : ['example.com', 'example.ca']},
			  {'names' : ['Sean', 'Cathy'] } ]
		), '[{"domains":["example.com","example.ca"]},{"names":["Sean","Cathy"]}]', 'Nested members' );
	});

	test("Tracking", function() {
		expect(<?php echo $sqlite ? 21 : 6; ?>);

		var tracker = Piwik.getTracker();

		ok( ! ( _paq instanceof Array ), "async tracker proxy not an array" );
		equals( typeof tracker, typeof _paq, "async tracker proxy" );

		var startTime, stopTime;

		equals( typeof tracker.setReferrerUrl, 'function', 'setReferrerUrl' );

		equals( typeof tracker.hook.test._beforeUnloadHandler, 'function', 'beforeUnloadHandler' );

		startTime = new Date();
		tracker.hook.test._beforeUnloadHandler();
		stopTime = new Date();
		ok( (stopTime.getTime() - startTime.getTime()) < 500, 'beforeUnloadHandler()' );

		tracker.setLinkTrackingTimer(2000);
		startTime = new Date();
		tracker.trackPageView();
		tracker.hook.test._beforeUnloadHandler();
		stopTime = new Date();
		ok( (stopTime.getTime() - startTime.getTime()) >= 2000, 'setLinkTrackingTimer()' );
<?php
if ($sqlite) {
	echo '
		tracker.setTrackerUrl("piwik.php");
		tracker.setSiteId(1);
		tracker.setCustomData({ "token" : getToken() });
		tracker.setDocumentTitle("PiwikTest");
		tracker.setReferrerUrl("http://referrer.example.com");

		tracker.enableLinkTracking();

		tracker.trackPageView();

		tracker.trackPageView("CustomTitleTest");

		tracker.trackLink("http://example.ca", "link", { "token" : getToken() });

		// async tracker proxy
		_paq.push(["trackLink", "http://example.fr/async.zip", "download",  { "token" : getToken() }]);

		// push function
		_paq.push([ function(t) {
			tracker.trackLink("http://example.de", "link", { "token" : t });
		}, getToken() ]);

		var buttons = new Array("click1", "click2", "click3", "click4", "click5", "click6", "click7");
		for (var i=0; i < buttons.length; i++) {
			triggerEvent( document.getElementById(buttons[i]), "click" );
		}

		tracker.setRequestMethod("POST");
		tracker.trackGoal(42, 69, { "token" : getToken(), "boy" : "Michael", "girl" : "Mandy"});

		piwik_log("CompatibilityLayer", 1, "piwik.php", { "token" : getToken() });

		stop();
		setTimeout(function() {
			jQuery.ajax({
				url: url("piwik.php?results='. $token .'"),
				success: function(results) {
//alert(results);
					ok( /\<span\>12\<\/span\>/.test( results ), "count tracking events" );
					ok( /PiwikTest/.test( results ), "trackPageView()" );
					ok( /Asynchronous/.test( results ), "async trackPageView()" );
					ok( /CustomTitleTest/.test( results ), "trackPageView(customTitle)" );
					ok( /example.ca/.test( results ), "trackLink()" );
					ok( /example.fr/.test( results ), "async trackLink()" );
					ok( /example.de/.test( results ), "push function" );
					ok( /example.net/.test( results ), "click: implicit outlink (by outbound URL)" );
					ok( /example.html/.test( results ), "click: explicit outlink" );
					ok( /example.pdf/.test( results ), "click: implicit download (by file extension)" );
					ok( /example.word/.test( results ), "click: explicit download" );
					ok( ! /example.(org|php)/.test( results ), "click: ignored" );
					ok( /Michael.*?Mandy.*?idgoal=42.*?revenue=69/.test( results ), "trackGoal()" );
					ok( /CompatibilityLayer/.test( results ), "piwik_log(): compatibility layer" );
					ok( /referrer.example.com/.test( results ), "setReferrerUrl()" );

					start();
				}
			});
		}, 2000);
		';
}
?>
	});
});
 </script>

</body>
</html>
