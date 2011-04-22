<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 *
 * @category Piwik
 * @package Piwik
 */

/**
 * Handling IP addresses (both IPv4 and IPv6).
 *
 * As of Piwik 1.3, IP addresses are stored in the DB has VARBINARY(16),
 * and passed around in network address format which has the advantage of
 * being in big-endian byte order, allowing for binary-safe string
 * comparison of addresses (of the same length), even on Intel x86.
 *
 * As a matter of naming convention, we use $ip for the network address format
 * and $ipString for the presentation format (i.e., human-readable form).
 *
 * @package Piwik
 */
class Piwik_IP
{
	/**
	 * Sanitize human-readable IP address.
	 *
	 * @param string $ipString IP address
	 * @return string|false
	 */
	static public function sanitizeIp($ipString)
	{
		$ipString = trim($ipString);

		// CIDR notation, A.B.C.D/E
		$posSlash = strrpos($ipString, '/');
		if($posSlash !== false)
		{
			$ipString = substr($ipString, 0, $posSlash);
		}

		$posColon = strrpos($ipString, ':');
		$posDot = strrpos($ipString, '.');
		if($posColon !== false)
		{
			// IPv6 address with port, [A:B:C:D:E:F:G:H]:EEEE
			$posRBrac = strrpos($ipString, ']');
			if($posRBrac !== false && $ipString[0] == '[')
			{
				$ipString = substr($ipString, 1, $posRBrac - 1);
			}

			if($posDot !== false)
			{
				// IPv4 address with port, A.B.C.D:EEEE
				if($posColon > $posDot)
				{
					$ipString = substr($ipString, 0, $posColon);
				}
				// else: Dotted quad IPv6 address, A:B:C:D:E:F:G.H.I.J
			}
			// else: IPv6 address, A:B:C:D:E:F:G:H
		}
		// else: IPv4 address, A.B.C.D

		return $ipString;
	}

	/**
	 * Sanitize human-readable (user-supplied) IP address range.
	 *
	 * Accepts the following formats for $ipRange:
	 * - single IPv4 address, e.g., 127.0.0.1
	 * - single IPv6 address, e.g., ::1/128
	 * - IPv4 block using CIDR notation, e.g., 192.168.0.0/22 represents the IPv4 addresses from 192.168.0.0 to 192.168.3.255
	 * - IPv6 block using CIDR notation, e.g., 2001:DB8::/48 represents the IPv6 addresses from 2001:DB8:0:0:0:0:0:0 to 2001:DB8:0:FFFF:FFFF:FFFF:FFFF:FFFF
	 * - wildcards, e.g., 192.168.0.*
	 *
	 * @param string $ipRangeString IP address range
	 * @return string|false IP address range in CIDR notation
	 */
	static public function sanitizeIpRange($ipRangeString)
	{
		// in case mbstring overloads strlen function
		$strlen = function_exists('mb_orig_strlen') ? 'mb_orig_strlen' : 'strlen';

		$ipRangeString = trim($ipRangeString);
		if(empty($ipRangeString))
		{
			return false;
		}

		// IPv4 address with wildcards '*'
		if(strpos($ipRangeString, '*') !== false)
		{
			if(preg_match('~(^|\.)\*\.\d+(\.|$)~', $ipRangeString))
			{
				return false;
			}

			$bits = 32 - 8 * substr_count($ipRangeString, '*');
			$ipRangeString = str_replace('*', '0', $ipRangeString);
		}

		// CIDR
		if(($pos = strpos($ipRangeString, '/')) !== false)
		{
			$bits = substr($ipRangeString, $pos + 1);
			$ipRangeString = substr($ipRangeString, 0, $pos);
		}

		// single IP
		if(($ip = @inet_pton($ipRangeString)) === false)
			return false;

		$maxbits = $strlen($ip) * 8;
		if(!isset($bits))
			$bits = $maxbits;

		if($bits < 0 || $bits > $maxbits)
		{
			return false;
		}

		return "$ipRangeString/$bits";
	}

	/**
	 * Convert presentation format IP address to network address format
	 *
	 * @param string $ipString IP address, either IPv4 or IPv6, e.g., "127.0.0.1"
	 * @return string Binary-safe string, e.g., "\x7F\x00\x00\x01"
	 */
	static public function P2N($ipString)
	{
		// use @inet_pton() because it throws an exception and E_WARNING on invalid input
		$ip = @inet_pton($ipString);
		return $ip === false ? "\x00\x00\x00\x00" : $ip;
	}

	/**
	 * Convert network address format to presentation format
	 *
	 * @see prettyPrint()
	 *
	 * @param string $ip IP address in network address format
	 * @return string IP address in presentation format
	 */
	static public function N2P($ip)
	{
		// use @inet_ntop() because it throws an exception and E_WARNING on invalid input
		$ipStr = @inet_ntop($ip);
		return $ipStr === false ? '0.0.0.0' : $ipStr;
	}

	/**
	 * Alias for N2P()
	 *
	 * @param string $ip IP address in network address format
	 * @return string IP address in presentation format
	 */
	static public function prettyPrint($ip)
	{
		return self::N2P($ip);
	}

	/**
	 * Get low and high IP addresses for a specified range.
	 *
	 * @param array $ipRange An IP address range in presentation format
	 * @return array|false Array ($lowIp, $highIp) in network address format, or false if failure
	 */
	static public function getIpsForRange($ipRange)
	{
		// in case mbstring overloads strlen and substr functions
		$strlen = function_exists('mb_orig_strlen') ? 'mb_orig_strlen' : 'strlen';

		if(strpos($ipRange, '/') === false)
		{
			$ipRange = self::sanitizeIpRange($ipRange);
		}
		$pos = strpos($ipRange, '/');

		$bits = substr($ipRange, $pos + 1);
		$range = substr($ipRange, 0, $pos);
		$high = $low = @inet_pton($range);
		if($low === false)
		{
			return false;
		}

		$lowLen = $strlen($low);
		$i = $lowLen - 1;
		$bits = $lowLen * 8 - $bits;

		for($n = (int)($bits / 8); $n > 0; $n--, $i--)
		{
			$low[$i] = chr(0);
			$high[$i] = chr(255);
		}

		$n = $bits % 8;
		if($n)
		{
			$low[$i] = chr(ord($low[$i]) & ~((1 << $n) - 1));
			$high[$i] = chr(ord($high[$i]) | ((1 << $n) - 1));
		}

		return array($low, $high);
	}

	/**
	 * Determines if an IP address is in a specified IP address range.
	 *
	 * An IPv4-mapped address should be range checked with an IPv4-mapped address range.
	 *
	 * @param string $ip IP address in network address format
	 * @param array $ipRanges List of IP address ranges
	 * @return bool True if in any of the specified IP address ranges; else false.
	 */
	static public function isIpInRange($ip, $ipRanges)
	{
		// in case mbstring overloads strlen and substr functions
		$strlen = function_exists('mb_orig_strlen') ? 'mb_orig_strlen' : 'strlen';

		$ipLen = $strlen($ip);
		if(empty($ip) || empty($ipRanges) || ($ipLen != 4 && $ipLen != 16))
		{
			return false;
		}

		foreach($ipRanges as $range)
		{
			if(is_array($range))
			{
				// already split into low/high IP addresses
				$range[0] = self::P2N($range[0]);
				$range[1] = self::P2N($range[1]);
			}
			else
			{
				// expect CIDR format but handle some variations
				$range = self::getIpsForRange($range);
			}
			if($range === false)
			{
				continue;
			}

			$low = $range[0];
			$high = $range[1];
			if($strlen($low) != $ipLen)
			{
				continue;
			}

			// binary-safe string comparison
			if($ip >= $low && $ip <= $high)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the best possible IP of the current user, in the format A.B.C.D
	 * For example, this could be the proxy client's IP address.
	 *
	 * @return string IP address in presentation format
	 */
	static public function getIpFromHeader()
	{
		static $clientHeaders = null;
		if(is_null($clientHeaders))
		{
			if(!empty($GLOBALS['PIWIK_TRACKER_MODE']))
			{
				$clientHeaders = @Piwik_Tracker_Config::getInstance()->General['proxy_client_headers'];
			}
			else
			{
				$config = Zend_Registry::get('config');
				if($config !== false && isset($config->General->proxy_client_headers))
				{
					$clientHeaders = $config->General->proxy_client_headers->toArray();
				}
			}
			if(!is_array($clientHeaders))
			{
				$clientHeaders = array();
			}
		}

		$default = '0.0.0.0';
		if(isset($_SERVER['REMOTE_ADDR']))
		{
			$default = $_SERVER['REMOTE_ADDR'];
		}

		$ipString = self::getNonProxyIpFromHeader($default, $clientHeaders);
		return self::sanitizeIp($ipString);
	}

	/**
	 * Returns a non-proxy IP address from header
	 *
	 * @param string $default Default value to return if no matching proxy header
	 * @param array $proxyHeaders List of proxy headers
	 * @return string
	 */
	static public function getNonProxyIpFromHeader($default, $proxyHeaders)
	{
		$proxyIps = null;
		if(!empty($GLOBALS['PIWIK_TRACKER_MODE']))
		{
			$proxyIps = @Piwik_Tracker_Config::getInstance()->General['proxy_ips'];
		}
		else
		{
			$config = Zend_Registry::get('config');
			if($config !== false && isset($config->General->proxy_ips))
			{
				$proxyIps = $config->General->proxy_ips->toArray();
			}
		}
		if(!is_array($proxyIps))
		{
			$proxyIps = array();
		}
		$proxyIps[] = $default;

		// examine proxy headers
		foreach($proxyHeaders as $proxyHeader)
		{
			if(!empty($_SERVER[$proxyHeader]))
			{
				$proxyIp = self::getLastIpFromList($_SERVER[$proxyHeader], $proxyIps);
				if(strlen($proxyIp) && stripos($proxyIp, 'unknown') === false)
				{
					return $proxyIp;
				}
			}
		}

		return $default;
	}

	/**
	 * Returns the last IP address in a comma separated list, subject to an optional exclusion list.
	 *
	 * @param string $csv Comma separated list of elements
	 * @param array $excludedIps Optional list of excluded IP addresses (or IP address ranges)
	 * @return string Last (non-excluded) IP address in the list
	 */
	static public function getLastIpFromList($csv, $excludedIps = null)
	{
		$p = strrpos($csv, ',');
		if($p !== false)
		{
			$elements = explode(',', $csv);
			for($i = count($elements); $i--; )
			{
				$element = trim(Piwik_Common::sanitizeInputValue($elements[$i]));
				if(empty($excludedIps) || !self::isIpInRange(self::P2N($element), $excludedIps))
				{
					return $element;
				}
			}
		}
		return trim(Piwik_Common::sanitizeInputValue($csv));
	}

	/**
	 * Get hostname for a given IP address
	 *
	 * @param string $ipStr Human-readable IP address
	 * @return string Hostname or unmodified $ipStr if failure
	 */
	static public function getHostByAddr($ipStr)
	{
		// PHP's reverse lookup supports ipv4 and ipv6
		return gethostbyaddr($ipStr);
	}
}
