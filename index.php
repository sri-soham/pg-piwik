<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 * 
 * @package Piwik
 */

error_reporting(E_ALL|E_NOTICE);
@ini_set('display_errors', 1);
@ini_set('magic_quotes_runtime', 0);
if(ini_get('session.save_handler') == 'user')
{
	@ini_set('session.save_handler', 'files');
	@ini_set('session.save_path', '');
}

if(!defined('PIWIK_INCLUDE_PATH'))
{
	define('PIWIK_INCLUDE_PATH', dirname(__FILE__));
}

if((@include "Version.php") === false || !class_exists('Piwik_Version', false))
{
	ini_set('include_path', PIWIK_INCLUDE_PATH . '/core'
	     . PATH_SEPARATOR . PIWIK_INCLUDE_PATH . '/libs'
	     . PATH_SEPARATOR . PIWIK_INCLUDE_PATH . '/plugins');
}

require_once "core/testMinimumPhpVersion.php";

// NOTE: the code above this comment must be PHP4 compatible

@date_default_timezone_set(date_default_timezone_get());

if(!defined('PIWIK_ENABLE_ERROR_HANDLER') || PIWIK_ENABLE_ERROR_HANDLER)
{
	require_once "core/ErrorHandler.php";
	require_once "core/ExceptionHandler.php";

	set_error_handler('Piwik_ErrorHandler');
	set_exception_handler('Piwik_ExceptionHandler');
}

session_cache_limiter('nocache');

require_once "core/Loader.php";
if(!defined('PIWIK_ENABLE_DISPATCH') || PIWIK_ENABLE_DISPATCH)
{
	$controller = Piwik_FrontController::getInstance();
	$controller->init();
	$controller->dispatch();
}
