<?php 
/**
* @version   $Id: shoutbox.php 2012-01-16 21:00:00
* @package   JJ Shoutbox
* @copyright Copyright (C) 2011 - 2013 JoomJunk. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__).'/helper.php' );

$displayname = $params->get('loginname');
$smile = $params->get('smile');
$swearcounter = $params->get('swearingcounter');
$swearnumber = $params->get('swearingnumber');
$number = $params->get('maximum');
$bordercolor = $params->get('bordercolor');
$borderwidth = $params->get('borderwidth');
$guestpost = $params->get('guestpost');
$submittext = $params->get('submittext');
$nonmembers = $params->get('nonmembers');
$deletecolor = $params->get('deletecolor');
$headercolor = $params->get('headercolor');
$houradd = $params->get('timezone', '0');
$extraadd = $params->get('timeadd', '0');
$width = $params->get('width', '250');
$profile = $params->get('profile');
$date = $params->get('date');

$dataerror= JText::_('SHOUT_DATABASEERRORSHOUT');

$user = JFactory::getUser();
require_once( dirname(__FILE__).'/assets/recaptcha/recaptchalib.php');
if(isset($_POST)) {
	$input = new JInput();
	$post = $input->getArray($_POST);
	if($params->get('recaptchaon')==0) {
		if(isset($post["recaptcha_response_field"])) {
			if ($post["recaptcha_response_field"]) {
				$resp = recaptcha_check_answer ($params->get('recaptcha-private'),
												$_SERVER["REMOTE_ADDR"],
												$post["recaptcha_challenge_field"],
												$post["recaptcha_response_field"]);

				if ($resp->is_valid) {
				modShoutboxHelper::postfiltering($post, $user, $swearcounter, $swearnumber, $extraadd, $displayname);
				} else {
						$error = $resp->error;
				}
			}
		}
	} else {
		modShoutboxHelper::postfiltering($post, $user, $swearcounter, $swearnumber, $extraadd, $displayname);
	}
	if(isset($post['delete'])) {
		$deletepostnumber=$post['idvalue'];
		modShoutboxHelper::deletepost($deletepostnumber);
	}
	if(isset($post['deleteall'])) {
		$delete = $post['valueall'];
		if(is_int($delete)) {
			if($delete>0) {
				if($delete>$post['max']) {
					$delete=$post['max'];
				}
				modShoutboxHelper::deleteall($delete);
			} else {
				JLog::add(JText::_('SHOUT_GREATER_THAN_ZERO'), JLog::WARNING, 'jerror');
			}
		} else {
			JLog::add(JText::_('SHOUT_NOT_INT'), JLog::WARNING, 'jerror');
		}
	}
}

require(JModuleHelper::getLayoutPath('mod_shoutbox'));
?>
