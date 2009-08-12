<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 *
 * @package Piwik_Login
 */

/**
 * @package Piwik_Login
 */
class Piwik_Login_Controller extends Piwik_Controller
{
	function getDefaultAction()
	{
		return 'login';
	}

	function login( $messageNoAccess = null )
	{
		$form = new Piwik_Login_Form();

		// get url from POSTed form or GET parameter (getting back from password remind form)
		$currentUrl = 'index.php'.Piwik_Url::getCurrentQueryString();
		$urlToRedirect = Piwik_Common::getRequestVar('form_url', htmlspecialchars($currentUrl), 'string');
		$urlToRedirect = htmlspecialchars_decode($urlToRedirect);

		if($form->validate())
		{
			$login = $form->getSubmitValue('form_login');
			$password = $form->getSubmitValue('form_password');
			$md5Password = md5($password);
			$authenticated = $this->authenticateAndRedirect($login, $md5Password, $urlToRedirect);
			if($authenticated === false)
			{
				$messageNoAccess = Piwik_Translate('Login_LoginPasswordNotCorrect');
			}
		}

		$view = Piwik_View::factory('login');
		// make navigation login form -> reset password -> login form remember your first url
		$view->urlToRedirect = $urlToRedirect;
		$view->AccessErrorString = $messageNoAccess;
		$view->linkTitle = Piwik::getRandomTitle();
		$view->addForm( $form );
		$view->subTemplate = 'genericForm.tpl';
		echo $view->render();
	}
	
	function logme()
	{
		$login = Piwik_Common::getRequestVar('login', null, 'string');
		$password = Piwik_Common::getRequestVar('password', null, 'string');
		$currentUrl = 'index.php';
		$urlToRedirect = Piwik_Common::getRequestVar('url', $currentUrl, 'string');
		
		if(strlen($password) != 32)
		{
			throw new Exception("The password parameter is expected to be a MD5 hash of the password.");
		}
		if($login == Zend_Registry::get('config')->superuser->login)
		{
			throw new Exception("The Super User cannot be authenticated using this URL.");
		}
		$authenticated = $this->authenticateAndRedirect($login, $password, $urlToRedirect);
		if($authenticated === false)
		{
			echo Piwik_Translate('Login_LoginPasswordNotCorrect');
		}
	}
	
	protected function authenticateAndRedirect($login, $md5Password, $urlToRedirect)
	{
		$tokenAuth = Piwik_UsersManager_API::getTokenAuth($login, $md5Password);
		
		$auth = Zend_Registry::get('auth');
		$auth->setLogin($login);
		$auth->setTokenAuth($tokenAuth);
		$authResult = $auth->authenticate();
		if($authResult->isValid())
		{
			$authCookieName = Zend_Registry::get('config')->General->login_cookie_name;
			$authCookieExpiry = time() + Zend_Registry::get('config')->General->login_cookie_expire;
			$cookie = new Piwik_Cookie($authCookieName, $authCookieExpiry);
			$cookie->set('login', $login);
			$cookie->set('token_auth', $authResult->getTokenAuth());
			$cookie->save();

			$urlToRedirect = htmlspecialchars_decode($urlToRedirect);
			Piwik_Url::redirectToUrl($urlToRedirect);
		}
		return false;
	}
	
	function lostPassword($messageNoAccess = null)
	{
		$form = new Piwik_Login_PasswordForm();
		$currentUrl = 'index.php';
		$urlToRedirect = Piwik_Common::getRequestVar('form_url', htmlspecialchars($currentUrl), 'string');

		if($form->validate())
		{
			$loginMail = $form->getSubmitValue('form_login');
			$this->lostPasswordFormValidated($loginMail, $urlToRedirect);
			return;
		}
		$view = Piwik_View::factory('lostPassword');
		$view->AccessErrorString = $messageNoAccess;
		// make navigation login form -> reset password -> login form remember your first url
		$view->urlToRedirect = $urlToRedirect;
		$view->linkTitle = Piwik::getRandomTitle();
		$view->addForm( $form );
		$view->subTemplate = 'genericForm.tpl';
		echo $view->render();
	}
	
	protected function lostPasswordFormValidated($loginMail, $urlToRedirect)
	{
		Piwik::setUserIsSuperUser();
		$user = null;
		$isSuperUser = false;
		
		if( $loginMail == Zend_Registry::get('config')->superuser->email
			|| $loginMail == Zend_Registry::get('config')->superuser->login )
		{
			$isSuperUser = true;
			$user = array( 
					'login' => Zend_Registry::get('config')->superuser->login,
					'email' => Zend_Registry::get('config')->superuser->email);
		}
		else if( Piwik_UsersManager_API::userExists($loginMail) )
		{
			$user = Piwik_UsersManager_API::getUser($loginMail);
		}
		else if( Piwik_UsersManager_API::userEmailExists($loginMail) )
		{
			$user = Piwik_UsersManager_API::getUserByEmail($loginMail);
		}

		if( $user === null )
		{
			$messageNoAccess = Piwik_Translate('Login_InvalidUsernameEmail');
		}
		else
		{
			$view = Piwik_View::factory('passwordsent');
				
			$login = $user['login'];
			$email = $user['email'];
			$randomPassword = Piwik_Common::getRandomString(8);

			// send email with new password
			try
			{
				$mail = new Piwik_Mail();
				$mail->addTo($email, $login);
				$mail->setSubject(Piwik_Translate('Login_MailTopicPasswordRecovery'));
				$mail->setBodyText(
					str_replace(
						'\n',
						"\n",
						sprintf(Piwik_Translate('Login_MailPasswordRecoveryBody'), $login, $randomPassword, Piwik_Url::getCurrentUrlWithoutQueryString())
					)
				);
				
				$piwikHost = $_SERVER['HTTP_HOST'];
				if(strlen($piwikHost) == 0)
				{
					$piwikHost = 'piwik.org';
				}
				
				$fromEmailName = Zend_Registry::get('config')->General->login_password_recovery_email_name;
				$fromEmailAddress = Zend_Registry::get('config')->General->login_password_recovery_email_address;
				$fromEmailAddress = str_replace('{DOMAIN}', $piwikHost, $fromEmailAddress);
				$mail->setFrom($fromEmailAddress, $fromEmailName);
				@$mail->send();
			
				if($isSuperUser)
				{
					$user['password'] = md5($randomPassword);
					Zend_Registry::get('config')->superuser = $user;
				}
				else
				{
					Piwik_UsersManager_API::updateUser($login, $randomPassword);
				}
			}
			catch(Exception $e)
			{
				$view->ErrorString = $e->getMessage();
			}

			$view->linkTitle = Piwik::getRandomTitle();
			$view->urlToRedirect = $urlToRedirect;
			echo $view->render();
		}
	}
	
	static public function clearSession()
	{	
		$authCookieName = Zend_Registry::get('config')->General->login_cookie_name;
		$cookie = new Piwik_Cookie($authCookieName);
		$cookie->delete();	
	}
	
	public function logout()
	{
		self::clearSession();
		Piwik::redirectToModule('CoreHome');
	}
}
