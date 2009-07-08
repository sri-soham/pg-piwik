<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id$
 * 
 * @package Piwik_Installation
 */

/**
 * 
 * @package Piwik_Installation
 */
class Piwik_Installation_FormFirstWebsiteSetup extends Piwik_Form
{
	function init()
	{
		$urlToGoAfter = 'index.php' . Piwik_Url::getCurrentQueryString();

		$urlExample = 'http://example.org';
		$javascriptOnClickUrlExample = "\"javascript:if(this.value=='$urlExample'){this.value='http://';} this.style.color='black';\"";
		
		$formElements = array(
			array('text', 'siteName', 'website name'),
			array('text', 'url', 'website URL', "style='color:rgb(153, 153, 153);' value=$urlExample onfocus=".$javascriptOnClickUrlExample." onclick=".$javascriptOnClickUrlExample),
		);
		$this->addElements( $formElements );
		
		$formRules = array();
		foreach($formElements as $row)
		{
			$formRules[] = array($row[1], sprintf('%s required', $row[2]), 'required');
		}
		
		$this->addRules( $formRules );	
		
		$this->addElement('submit', 'submit', 'Go!');
	}	
}
