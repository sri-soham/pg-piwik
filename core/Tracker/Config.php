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
 * Backward compatibility layer
 *
 * @todo remove this in 2.0
 * @since 1.7
 * @deprecated 1.7
 *
 * @package Piwik
 * @subpackage Piwik_Tracker_Config
 */
class Piwik_Tracker_Config
{
	/**
	 * Returns the singleton Piwik_Config
	 *
	 * @return Piwik_Config
	 */
	static public function getInstance()
	{
		return Piwik_Config::getInstance();
	}
}
