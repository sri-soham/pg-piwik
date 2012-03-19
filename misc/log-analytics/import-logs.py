#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
if sys.version_info < (2, 6):
    print >> sys.stderr, 'You must use Python 2.6 or greater'
    sys.exit(1)


import base64
import ConfigParser
import datetime
import fnmatch
import gzip
import hashlib
import inspect
import itertools
import json
import logging
import optparse
import os
import os.path
import Queue
import re
import threading
import time
import urllib
import urllib2



##
## Constants.
##

_COMMON_LOG_FORMAT = (
    '(?P<ip>\S+) \S+ \S+ \[(?P<date>.*?)\] '
    '"\S+ (?P<path>.*?) \S+" (?P<status>\S+) (?P<length>\S+)'
)
_NSCA_EXTENDED_LOG_FORMAT = (
    '(?P<ip>\S+) \S+ \S+ \[(?P<date>.*?)\] '
    '"\S+ (?P<path>.*?) \S+" (?P<status>\S+) (?P<length>\S+) '
    '"(?P<referrer>.*?)" "(?P<user_agent>.*?)"'
)
_COMMON_COMPLETE_LOG_FORMAT = (
    '(?P<host>[\w\-\.]*)(?::\d+)? '
    '(?P<ip>\S+) \S+ \S+ \[(?P<date>.*?)\] '
    '"\S+ (?P<path>.*?) \S+" (?P<status>\S+) (?P<length>\S+) '
    '"(?P<referrer>.*?)" "(?P<user_agent>.*?)"'
)

FORMATS = {
    'common': _COMMON_LOG_FORMAT,
    'common_vhost': '(?P<host>[\w\-\.]*)(?::\d+)? ' + _COMMON_LOG_FORMAT,
    'nsca_extended': _NSCA_EXTENDED_LOG_FORMAT,
    'common_complete': _COMMON_COMPLETE_LOG_FORMAT,
}

DATE_FORMAT = '%d/%b/%Y:%H:%M:%S'


EXCLUDED_EXTENSIONS = (
    # Images
    '.gif', '.jpg', '.jpeg', '.png', '.bmp', '.ico', '.svg',
    # Fonts
    '.ttf', '.eot', '.woff',
    # Plugins
    '.class', '.swf',
    # Misc
    '.css', '.js', '.xml', 'robots.txt',
)

DOWNLOAD_EXTENSIONS = (
    '7z aac arc arj asf asx avi bin csv deb dmg doc exe flv gif gz gzip hqx '
    'jar jpg jpeg js mpg mp2 mp3 mp4 mpeg mov movie msi msp odb odf odg odp '
    'ods odt ogg ogv pdf phps png ppt qt qtm ra ram rar rpm sea sit tar tbz '
    'bz2 tbz tgz torrent txt wav wma wmv wpd xls xml z zip'
).split()

# A good source is: http://phpbb-bots.blogspot.com/
EXCLUDED_USER_AGENTS = (
    'AdsBot-Google',
    'ia_archiver',
    'Scooter/',
    'Ask Jeeves',
    'Baiduspider+(',
    'Exabot',
    'Googlebot',
    'Mediapartners-Google',
    'msnbot',
    'Sosospider+',
    'SurveyBot',
    'Twiceler',
    'VoilaBot',
    'Yahoo',
    'Yandex',
)


PIWIK_MAX_ATTEMPTS = 3
PIWIK_DELAY_AFTER_FAILURE = 2

PIWIK_EXPECTED_IMAGE = base64.b64decode(
    'R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='
)




##
## Code.
##


class Configuration(object):
    """
    Stores all the configuration options by reading sys.argv and parsing,
    if needed, the config.inc.php.

    It has 2 attributes: options and filenames.
    """

    class Error(Exception):
        pass

    def __init__(self):
        option_parser = optparse.OptionParser(
            usage='Usage: %prog [options] log_file [ log_file [...] ]',
            description="Import HTTP access logs to Piwik."
        )
        option_parser.add_option(
            '-d', '--debug', dest='debug', action='count', default=0,
            help="Enable debug output (specify multiple times for more verbose)",
        )
        option_parser.add_option(
            '-n', '--dry-run', dest='dry_run',
            action='store_true', default=False,
            help="Perform a trial run with no tracking data being inserted into Piwik",
        )
        option_parser.add_option(
            '-u', '--url', dest='piwik_url',
            help="Piwik base URL, eg. http://example.com/piwik/ or http://analytics.example.net",
        )
        default_config = os.path.abspath(
            os.path.join(os.path.dirname(__file__),
            '../../config/config.ini.php'),
        )
        option_parser.add_option(
            '-c', '--config', dest='config_file', default=default_config,
            help=(
                "This is only used with --login and --password is not used. "
		"Piwik will read the configuration file (default: %default) to "
                "fetch the Super User token_auth from the config file. "
            )
        )
        option_parser.add_option(
            '-l', '--login', dest='login',
            help="You can manually specify the Piwik Super User login"
        )
        option_parser.add_option(
            '-p', '--password', dest='password',
            help="You can manually specify the Piwik Super User password"
        )
        option_parser.add_option(
            '-t', '--token-auth', dest='piwik_token_auth',
            help="Piwik Super User token_auth, 32 characters hexadecimal string, found in Piwik > API",
        )
        option_parser.add_option(
            '-f', '--format', dest='format', default=None,
            help=(
		"Access log format to detect (supported are: common, common_vhost, nsca_extended, common_complete) "
		"When not specified, the log format will be autodetected by trying all supported log formats."
	    )
        )
        option_parser.add_option(
            '-i', '--idsite', dest='site_id',
            help= ( 
		"When specified "
		"- All data in the specified log files will be tracked for this Piwik site ID."
		"- The script will not auto-detect the website based on the log line hostname (new websites will not be automatically created)."
	    )
        )
        option_parser.add_option(
            '--idsite-fallback', dest='site_id_fallback',
            help="Default Piwik site ID to use if the hostname doesn't match any "
            "known Website's URL",
        )
        option_parser.add_option(
            '--hostnames', dest='hostnames', action='append',
            help="Accepted hostnames (others will be excluded)"
        )
        option_parser.add_option(
            '-s', '--skip', dest='skip', default=0, type='int',
            help="Skip the n first lines to start parsing/importing data at a given line for the specified log file",
        )
        option_parser.add_option(
            '-r', '--recorders', dest='recorders', default=1, type='int',
            help="Number of simultaneous recorders (default: %default)",
        )
        option_parser.add_option(
            '--add-sites-new-hosts', dest='add_sites_new_hosts',
            action='store_true', default=False,
            help="When a hostname is found in the log file, but not matched to any website "
            "in Piwik, automatically create a new website in Piwik with this hostname to "
            "import the logs"
        )
        option_parser.add_option(
            '--useragent-exclude', dest='excluded_useragents',
            action='append', default=[],
            help="User agents to exclude (in addition to the standard excluded "
            "user agents)",
        )
        option_parser.add_option(
            '--show-progress', dest='show_progress',
            action='store_true', default=os.isatty(sys.stdout.fileno()),
            help="Print a progress report every second"
        )

        self.options, self.filenames = option_parser.parse_args(sys.argv[1:])

        # Configure logging before calling logging.{debug,info}.
        logging.basicConfig(
            format='%(asctime)s: [%(levelname)s] %(message)s',
            level=logging.DEBUG if self.options.debug >= 1 else logging.INFO,
        )

        if self.options.hostnames:
            logging.debug('Accepted hostnames: %s', ', '.join(options.hostnames))
        else:
            logging.debug('Accepted hostnames: all')

        if self.options.format:
            try:
                self.format_regexp = re.compile(FORMATS[self.options.format])
            except KeyError:
                fatal_error('invalid log format: %s' % self.options.format)
        else:
            self.format_regexp = None

        if not self.options.piwik_url:
            fatal_error('no URL given for Piwik')

        if not (self.options.piwik_url.startswith('http://') or self.options.piwik_url.startswith('https://')):
            self.options.piwik_url = 'http://' + self.options.piwik_url
        logging.debug('Piwik URL is: %s', self.options.piwik_url)

        if not self.options.piwik_token_auth:
            self.options.piwik_token_auth = self._get_token_auth()
        logging.debug('Authentication token token_auth is: %s', self.options.piwik_token_auth)

        if self.options.recorders < 1:
            self.options.recorders = 1


    def _get_token_auth(self):
        """
        If the token auth is not specified in the options, get it from Piwik.
        """
        # Get superuser login/password from the options.
        logging.debug('No token-auth specified')

        if self.options.login and self.options.password:
            piwik_login = self.options.login
            piwik_password = hashlib.md5(self.options.password).hexdigest()
        else:
            # Fallback to the given (or default) configuration file, then
            # get the token from the API.
            logging.debug(
                'No credentials specified, reading them from "%s"',
                self.options.config_file,
            )
            config_file = ConfigParser.RawConfigParser()
            success = len(config_file.read(self.options.config_file)) > 0
            if not success:
                fatal_error(
                    "couldn't open the configuration file, "
                    "required to get the authentication token"
                )
            piwik_login = config_file.get('superuser', 'login')
            piwik_password = config_file.get('superuser', 'password')

        logging.debug('Using credentials: (login = %s, password = %s)', piwik_login, piwik_password)
        try:
            api_result = piwik.call_api('UsersManager.getTokenAuth',
                userLogin=piwik_login,
                md5Password=piwik_password,
                _token_auth='',
                _url=self.options.piwik_url,
            )
        except urllib2.URLError, e:
            fatal_error('error when fetching token_auth from the API: %s' % e)

        try:
            return api_result['value']
        except KeyError:
            # Happens when the credentials are invalid.
            message = api_result.get('message')
            fatal_error(
                'error fetching authentication token token_auth%s' % (
                ': %s' % message if message else '')
            )

    def get_resolver(self):
        if self.options.site_id:
            logging.debug('Resolver: static')
            return StaticResolver(self.options.site_id)
        else:
            logging.debug('Resolver: dynamic')
            return DynamicResolver()



class Statistics(object):
    """
    Store statistics about parsed logs and recorded entries.
    Can optionally print statistics on standard output every second.
    """

    class Counter(object):
        """
        Simple integers cannot be used by multithreaded programs. See:
        http://stackoverflow.com/questions/6320107/are-python-ints-thread-safe
        """
        def __init__(self):
            # itertools.count's implementation in C does not release the GIL and
            # therefore is thread-safe.
            self.counter = itertools.count(1)
            self.value = 0

        def increment(self):
            self.value = self.counter.next()

        def __str__(self):
            return str(int(self.value))


    def __init__(self):
        self.time_start = None
        self.time_stop = None

        self.piwik_sites = set()                # sites ID
        self.piwik_sites_created = []           # (hostname, site ID)
        self.piwik_sites_ignored = set()        # hostname

        self.count_lines_parsed = self.Counter()
        self.count_lines_recorded = self.Counter()

        # Do not match the regexp.
        self.count_lines_invalid = self.Counter()
        # No site ID found by the resolver.
        self.count_lines_no_site = self.Counter()
        # Hostname filtered by config.options.hostnames
        self.count_lines_hostname_skipped = self.Counter()
        # Static files.
        self.count_lines_static = self.Counter()
        # Ignored user-agents.
        self.count_lines_skipped_user_agent = self.Counter()
        # Downloads
        self.count_lines_downloads = self.Counter()

        # Misc
        self.dates_recorded = set()
        self.monitor_stop = False


    def set_time_start(self):
        self.time_start = time.time()

    def set_time_stop(self):
        self.time_stop = time.time()

    def _compute_speed(self, value, start, end):
        delta_time = end - start
        if value == 0:
            return 0
        if delta_time == 0:
            return 'very high!'
        else:
            return value / delta_time

    def _round_value(self, value, base=100):
        return round(value * base) / base

    def _indent_text(self, lines, level=1):
        """
        Return an indented text. 'lines' can be a list of lines or a single
        line (as a string). One level of indentation is 4 spaces.
        """
        prefix = ' ' * (4 * level)
        if isinstance(lines, basestring):
            return prefix + lines
        else:
            return '\n'.join(
                prefix + line
                for line in lines
            )

    def print_summary(self):
        print '''
Logs import summary
-------------------

    %(count_lines_recorded)d requests imported successfully
    %(count_lines_downloads)d requests were downloads
    %(total_lines_ignored)d requests ignored:
        %(count_lines_invalid)d invalid log lines
        %(count_lines_skipped_user_agent)d requests done by bots, search engines, ...
        %(count_lines_static)d requests to static resources (images, stylesheets, ...)
        %(count_lines_no_site)d requests did not match any known site
        %(count_lines_hostname_skipped)d requests did not match any requested hostname

Website import summary
----------------------

    %(count_lines_recorded)d requests imported to %(total_sites)d sites
        %(total_sites_existing)d sites already existed
        %(total_sites_created)d sites were created:
%(sites_created)s
    %(total_sites_ignored)d distinct hostnames did not match any existing site:
%(sites_ignored)s
        TIP: if one of these hosts is an alias host for one of the websites
        in Piwik, you can add this host as an "Alias URL" in Settings > Websites.
        TIP: use --add-sites-new-hosts if you wish to automatically create
        one website for each of these hosts in Piwik rather than discarding
        these requests.

Performance summary
-------------------

    Total time: %(total_time)d seconds
    Requests imported per second: %(speed_recording)s requests per second
''' % {

    'count_lines_recorded': self.count_lines_recorded.value,
    'count_lines_downloads': self.count_lines_downloads.value,
    'total_lines_ignored': sum([
            self.count_lines_invalid.value,
            self.count_lines_skipped_user_agent.value,
            self.count_lines_static.value,
            self.count_lines_no_site.value,
            self.count_lines_hostname_skipped.value,
        ]),
    'count_lines_invalid': self.count_lines_invalid.value,
    'count_lines_skipped_user_agent': self.count_lines_skipped_user_agent.value,
    'count_lines_static': self.count_lines_static.value,
    'count_lines_no_site': self.count_lines_no_site.value,
    'count_lines_hostname_skipped': self.count_lines_hostname_skipped.value,
    'total_sites': len(self.piwik_sites),
    'total_sites_existing': len(self.piwik_sites - set(site_id for hostname, site_id in self.piwik_sites_created)),
    'total_sites_created': len(self.piwik_sites_created),
    'sites_created': self._indent_text(
            ['%s (ID: %d)' % (hostname, site_id) for hostname, site_id in self.piwik_sites_created],
            level=3,
        ),
    'total_sites_ignored': len(self.piwik_sites_ignored),
    'sites_ignored': self._indent_text(
            self.piwik_sites_ignored, level=3,
        ),
    'total_time': self.time_stop - self.time_start,
    'speed_recording': self._round_value(self._compute_speed(
            self.count_lines_recorded.value,
            self.time_start, self.time_stop,
        )),
}


    ##
    ## The monitor is a thread that prints a short summary each second.
    ##

    def _monitor(self):
        latest_total_recorded = 0
        while not self.monitor_stop:
            current_total = stats.count_lines_recorded.value
            print '%d lines parsed, %d lines recorded, %d records/sec' % (
                stats.count_lines_parsed.value,
                current_total,
                current_total - latest_total_recorded,
            )
            latest_total_recorded = current_total
            time.sleep(1)

    def start_monitor(self):
        t = threading.Thread(target=self._monitor)
        t.daemon = True
        t.start()

    def stop_monitor(self):
        self.monitor_stop = True



class Piwik(object):
    """
    Make requests to Piwik.
    """

    class Error(Exception):
        pass

    @staticmethod
    def _call(path, args, headers=None, url=None):
        """
        Make a request to the Piwik site. It is up to the caller to format
        arguments, to embed authentication, etc.
        """
        if url is None:
            url = config.options.piwik_url
        headers = headers or {}
        # If Content-Type isn't defined, PHP do not parse the request's body.
        headers['Content-type'] = 'application/x-www-form-urlencoded'
        data = urllib.urlencode(args).encode('ascii', 'ignore')
        request = urllib2.Request(url + path, data, headers)
        response = urllib2.urlopen(request)
        return response.read()

    @staticmethod
    def _call_api(method, **kwargs):
        """
        Make a request to the Piwik API taking care of authentication, body
        formatting, etc.
        """
        args = {
            'module' : 'API',
            'format' : 'json',
            'method' : method,
        }
        # token_auth, by default, is taken from config.
        token_auth = kwargs.pop('_token_auth', None)
        if token_auth is None:
            token_auth = config.options.piwik_token_auth
        if token_auth:
            args['token_auth'] = token_auth

        url = kwargs.pop('_url', None)

        if kwargs:
            args.update(kwargs)

        # Convert lists into appropriate format.
        # See: http://dev.piwik.org/trac/wiki/API/Reference#PassinganArrayParameter
        # Warning: we have to pass the parameters in order: foo[0], foo[1], foo[2]
        # and not foo[1], foo[0], foo[2] (it will break Piwik otherwise.)
        final_args = []
        for key, value in args.iteritems():
            if isinstance(value, (list, tuple)):
                for index, obj in enumerate(value):
                    final_args.append(('%s[%d]' % (key, index), obj))
            else:
                final_args.append((key, value))
        res = Piwik._call('/', final_args, url=url)
        try:
            return json.loads(res)
        except ValueError:
            raise urllib2.URLError('Piwik returned an invalid response: ' + res)


    def _call_wrapper(self, func, expected_response, *args, **kwargs):
        """
        Try to make requests to Piwik at most PIWIK_FAILURE_MAX_RETRY times.
        """
        errors = 0
        while True:
            try:
                response = func(*args, **kwargs)
                if expected_response is not None and response != expected_response:
                    raise urllib2.URLError("didn't receive the expected response")
                return response
            except (urllib2.URLError, ValueError), e:
                logging.debug('Error when connecting to Piwik: %s', e)
                errors += 1
                if errors == PIWIK_MAX_ATTEMPTS:
                    raise Piwik.Error(str(e))
                else:
                    time.sleep(PIWIK_DELAY_AFTER_FAILURE)

    def call(self, path, args, expected_content=None, headers=None):
        return self._call_wrapper(self._call, expected_content, path, args, headers)

    def call_api(self, method, **kwargs):
        return self._call_wrapper(self._call_api, None, method, **kwargs)



##
## Resolvers.
##
## A resolver is a class that turns a hostname into a Piwik site ID.
##

class StaticResolver(object):
    """
    Always return the same site ID, specified in the configuration.
    """

    def __init__(self, site_id):
        self.site_id = site_id
        # Go get the main URL
        sites = piwik.call_api(
            'SitesManager.getSiteFromId', idSite=self.site_id
        )
        try:
            site = sites[0]
        except (IndexError, KeyError):
            fatal_error(
                "cannot get the main URL of this site: invalid site ID: %s" % site_id
            )
        if site.get('result') == 'error':
            fatal_error(
                "cannot get the main URL of this site: %s" % site.get('message')
            )
        self._main_url = site['main_url']
        stats.piwik_sites.add(self.site_id)

    def resolve(self, hit):
        return (self.site_id, self._main_url)

    def check_format(self, format):
        pass


class DynamicResolver(object):
    """
    Use Piwik API to determine the site ID.
    """

    def __init__(self):
        self._cache = {}

    def _resolve(self, hit):
        main_url = 'http://' + hit.host
        res = piwik.call_api(
            'SitesManager.getSitesIdFromSiteUrl',
            url=main_url,
        )
        if res:
            # The site already exists.
            site_id = res[0]['idsite']
        else:
            # The site doesn't exist.
            logging.debug('No Piwik site found for the hostname: %s', hit.host)
            if config.options.site_id_fallback is not None:
                logging.debug('Using default site for hostname: %s', hit.host)
                return config.options.site_id_fallback
            elif config.options.add_sites_new_hosts:
                if config.options.dry_run:
                    # Let's just return a fake ID.
                    site_id = 0
                logging.debug('Creating a Piwik site for hostname %s', hit.host)
                result = piwik.call_api(
                    'SitesManager.addSite',
                    siteName=hit.host,
                    urls=[main_url],
                )
                if result.get('result') == 'error':
                    logging.error("Couldn't create a Piwik site for host %s: %s",
                        hit.host, result.get('message'),
                    )
                else:
                    site_id = result['value']
                    stats.piwik_sites_created.append((hit.host, site_id))
            else:
                # The site doesn't exist, we don't want to create new sites and
                # there's no default site ID. We thus have to ignore this hit.
                site_id = None
        stats.piwik_sites.add(site_id)

    def resolve(self, hit):
        """
        Return the site ID from the cache if found, otherwise call _resolve.
        """
        try:
            site_id = self._cache[hit.host]
        except KeyError:
            logging.debug(
                'Site ID for hostname %s not in cache', hit.host
            )
            site_id = self._resolve(hit)
            logging.debug('Site ID for hostname %s: %s', hit.host, site_id)
            self._cache[hit.host] = site_id
        return (site_id, 'http://' + hit.host)


    def check_format(self, format):
        regexp = re.compile(format)
        if 'host' not in regexp.groupindex:
            fatal_error(
                "the selected log format doesn't include the hostname: you must "
                "specify the Piwik site ID with the -i argument"
            )




class Recorder(object):
    """
    A Recorder fetches hits from the Queue and inserts them into Piwik using
    the API.
    """

    recorders = []

    def __init__(self):
        self.queue = Queue.Queue(maxsize=10000)

    @staticmethod
    def launch(recorder_count):
        """
        Launch a bunch of Recorder objects in a separate thread.
        """
        for i in xrange(recorder_count):
            recorder = Recorder()
            Recorder.recorders.append(recorder)
            t = threading.Thread(target=recorder._run)
            t.daemon = True
            t.start()
            logging.debug('Launched recorder')

    @staticmethod
    def add_hit(hit):
        """
        Add a hit in one of the recorders queue.
        """
        # Get a queue so that one client IP will always use the same queue.
        recorders = Recorder.recorders
        queue = recorders[abs(hash(hit.ip)) % len(recorders)].queue
        queue.put(hit)

    @staticmethod
    def wait_empty():
        """
        Wait until all recorders have an empty queue.
        """
        for recorder in Recorder.recorders:
            recorder._wait_empty()


    def _run(self):
        while True:
            hit = self.queue.get()
            try:
                self._record_hit(hit)
            except Piwik.Error, e:
                fatal_error(e, hit.filename, hit.lineno)
            self.queue.task_done()

    def _wait_empty(self):
        """
        Wait until the queue is empty.
        """
        while True:
            if self.queue.empty():
                # We still have to wait for the last queue item being processed
                # (queue.empty() returns True before queue.task_done() is
                # called).
                self.queue.join()
                return
            time.sleep(1)

    def date_to_piwik(self, date):
        date, time = date.isoformat(sep=' ').split()
        return '%s %s' % (date, time.replace('-', ':'))

    def _record_hit(self, hit):
        """
        Insert the hit into Piwik.
        """
        site_id, main_url = resolver.resolve(hit)
        if site_id is None:
            # This hit doesn't match any known Piwik site.
            stats.piwik_sites_ignored.add(hit.host)
            stats.count_lines_no_site.increment()
            return

        stats.dates_recorded.add(hit.date.date())

        args = {
            'rec': '1',
            'apiv': '1',
            'url': main_url + hit.path[:1024],
            'urlref': hit.referrer[:1024],
            'cip': hit.ip,
            'cdt': self.date_to_piwik(hit.date),
            'idsite': site_id,
            'dp': 1,
            'token_auth': config.options.piwik_token_auth,
        }
        if hit.is_download:
            args['download'] = args['url']
            stats.count_lines_downloads.increment()
        if hit.status == '404':
            args['action_name'] = '404/URL = %s/From = %s' % (
                urllib.quote(args['url']),
                urllib.quote(args['urlref'])
            )

        if not config.options.dry_run:
            piwik.call(
                '/piwik.php', args,
                expected_content=PIWIK_EXPECTED_IMAGE,
                headers={'User-Agent' : hit.user_agent},
            )
        stats.count_lines_recorded.increment()


    @staticmethod
    def invalidate_reports():
        if config.options.dry_run or not stats.dates_recorded:
            return

        dates = [date.strftime('%Y-%m-%d') for date in stats.dates_recorded]
        print 'Purging Piwik archives for dates: %s' % dates
        result = piwik.call_api(
            'CoreAdminHome.invalidateArchivedReports',
            dates=','.join(dates),
            idSites=','.join(stats.piwik_sites),
        )




class Hit(object):
    """
    It's a simple container.
    """
    def __init__(self, **kwargs):
        for key, value in kwargs.iteritems():
            setattr(self, key, value)
        super(Hit, self).__init__()


class Parser(object):
    """
    The Parser parses the lines in a specified file and inserts them into
    a Queue.
    """

    ## All check_* methods are called for each hit and must return True if the
    ## hit can be imported, False otherwise.

    def check_hostname(self, hit):
        # Check against config.hostnames.
        if not hasattr(hit, 'host') or not config.options.hostnames:
            return True

        # Accept the hostname only if it matches one pattern in the list.
        result = any(
            fnmatch.fnmatch(hit.host, pattern)
            for pattern in config.options.hostnames
        )
        if not result:
            stats.count_lines_hostname_skipped.increment()
        return result

    def check_extension(self, hit):
        for extension in EXCLUDED_EXTENSIONS:
            if hit.path.endswith(extension) and not hit.is_download:
                stats.count_lines_static.increment()
                return False
        return True

    def check_user_agent(self, hit):
        for s in itertools.chain(EXCLUDED_USER_AGENTS, config.options.excluded_useragents):
            if s in hit.user_agent:
                stats.count_lines_skipped_user_agent.increment()
                return False
        return True


    def parse(self, filename):
        """
        Parse the specified filename and insert hits in the queue.
        """
        def invalid_line(line):
            stats.count_lines_invalid.increment()
            if self.options.debug >= 2:
                logging.debug('Invalid line detected: ' + line)

        if config.options.show_progress:
            print 'Parsing log %s...' % filename

        if filename.endswith('.gz'):
            open_func = gzip.open
        else:
            open_func = open

        file = open_func(filename, 'r')
        for lineno, line in enumerate(file):
            # Guess the format if needed.
            if not config.format_regexp:
                logging.debug('Guessing the log format...')
                for name, format in FORMATS.iteritems():
                    if re.match(format, line):
                        config.format = format
                        config.format_regexp = re.compile(format)
                        logging.debug('Format %s matches', name)
                        break
                    logging.debug('Format %s does not match', name)
                if not config.format_regexp:
                    return fatal_error(
                        'Cannot guess the logs format. Please give one using'
                        ' the --format option'
                    )
                # Make sure the format is compatible with the resolver.
                resolver.check_format(format)

            stats.count_lines_parsed.increment()
            if stats.count_lines_parsed.value <= config.options.skip:
                continue

            match = config.format_regexp.match(line)
            if not match:
                invalid_line(line)
                continue

            hit = Hit(
                filename=filename,
                lineno=lineno,
                status=match.group('status'),
                full_path=match.group('path'),
            )

            # Strip query string
            hit.path = hit.full_path.split('?', 1)[0]

            # Parse date _with_ timezone to get an UTC timestamp.
            date_string = match.group('date')
            try:
                tz = float(date_string[-5:])
                hit.date = datetime.datetime.strptime(date_string[:-6], '%d/%b/%Y:%H:%M:%S')
            except ValueError:
                # Date format is incorrect, the line is probably badly formatted.
                invalid_line(line)
                continue
            hit.date -= datetime.timedelta(hours=tz/100)

            try:
                hit.referrer = match.group('referrer')
            except IndexError:
                hit.referrer = ''
            if hit.referrer == '-':
                hit.referrer = ''

            try:
                hit.user_agent = match.group('user_agent')
            except IndexError:
                hit.user_agent = ''

            hit.ip = match.group('ip')
            try:
                hit.length = int(match.group('length'))
            except ValueError:
                # Not all lines have a length (e.g. 304 redirects)
                hit.length = 0
            try:
                hit.host = match.group('host')
            except IndexError:
                # Some formats have no host.
                pass

            hit.is_download = hit.path.rsplit('.', 1)[-1] in DOWNLOAD_EXTENSIONS

            # Check if the hit must be excluded.
            check_methods = inspect.getmembers(self, predicate=inspect.ismethod)
            if all((method(hit) for name, method in check_methods if name.startswith('check_'))):
                Recorder.add_hit(hit)




def main():
    """
    Start the importing process.
    """
    if config.options.show_progress:
        stats.start_monitor()

    stats.set_time_start()

    recorders = Recorder.launch(config.options.recorders)

    for filename in config.filenames:
        if os.path.exists(filename):
            parser.parse(filename)
        else:
            print >> sys.stderr, 'File %s does not exist' % filename

    Recorder.wait_empty()
    stats.set_time_stop()

    if config.options.show_progress:
        stats.stop_monitor()

    try:
        Recorder.invalidate_reports()
    except Piwik.Error, e:
        pass
    stats.print_summary()



def fatal_error(error, filename=None, lineno=None):
    print >> sys.stderr, 'Fatal error: %s' % error
    if filename and lineno is not None:
        print >> sys.stderr, (
            'You can restart the import of "%s" from the point it failed by '
            'specifying --skip=%d on the command line.\n' % (filename, lineno)
        )
    os._exit(1)


if __name__ == '__main__':
    try:
        piwik = Piwik()
        config = Configuration()
        stats = Statistics()
        resolver = config.get_resolver()
        parser = Parser()
        main()
    except KeyboardInterrupt:
        pass
