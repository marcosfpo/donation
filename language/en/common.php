<?php
/**
*
* @package phpBB Extension - phpBB Paypal Donation
* @copyright (c) 2015 marcosfpo
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @Author Stoker - http://www.phpbb3bbcodes.com
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'DONATEINDEX'					=> 'Become a donor!',
	
	'VIEWING_DONATE'				=> 'Donation page',
	
	'ACP_DONATION_MOD'				=> 'Donation Extension',
	
	'DONATION_SAVED'				=> 'Donation settings saved',
	
	'DONATION_VERSION'				=> 'Version',
	
	'DONATION_CONFIG'				=> 'Setup',
	'DONATION_SETTINGS'				=> 'Donation general settings',
	
	'DONATION_ENABLE'				=> 'Enable Donation Extension',
	'DONATION_ENABLE_EXPLAIN'			=> 'Enable or disable Donetion Extension.',
	
	'DONATION_INDEX_ENABLE'				=> 'View statistics collaborations in the index page',
	'DONATION_INDEX_ENABLE_EXPLAIN'			=> 'Enable if you want to display statistics collaborations in the index page.',
	
	'DONATION_INDEX_TOP'				=> 'View collaborations statistics on top',
	'DONATION_INDEX_TOP_EXPLAIN'			=> 'Enable if you want to view the contributions of statistics at the top of the forum.',
	
	'DONATION_INDEX_BOTTOM'				=> 'View collaborations statistics below',
	'DONATION_INDEX_BOTTOM_EXPLAIN'			=> 'Enable if you want to view the contributions of statistics at the bottom of the forum.',
	
	'DONATION_EMAIL_PAYPAL'				=> 'PayPal email or ID',
	'DONATION_EMAIL_PAYPAL_EXPLAIN'			=> 'Enter the email address or PayPal account ID.',
	
	'DONATION_STATS_SETTINGS'			=> 'Donation statistics setup',
	
	'DONATION_ACHIEVEMENT_ENABLE'			=> 'Enable obtained donations',
	'DONATION_ACHIEVEMENT_ENABLE_EXPLAIN'		=> 'Enable the display of donations obtained if desired.',
	
	'DONATION_ACHIEVEMENT'				=> 'Obtained donations',
	'DONATION_ACHIEVEMENT_EXPLAIN'			=> 'Obtained donations amount.',
	
	'DONATION_GOAL_ENABLE'				=> 'Enable target for donations',
	'DONATION_GOAL_ENABLE_EXPLAIN'			=> 'Enable target for donations, if you want to view it.',
	
	'DONATION_GOAL'					=> 'Donation target',
	'DONATION_GOAL_EXPLAIN'				=> 'Value to be obtained in donations.',
	
	'DONATION_GOAL_CURRENCY_ENABLE'			=> 'Enable currency to target donations',
	'DONATION_GOAL_CURRENCY_ENABLE_EXPLAIN'		=> 'Enable currency to target collaborations , whether to display it.',
	
	'DONATION_GOAL_CURRENCY'			=> 'Target currency for donations',
	'DONATION_GOAL_CURRENCY_EXPLAIN'		=> 'Currency to be used to obtained collaborations and target for donations.',
	
	'DONATION_AMOUNT'				=> 'Donation amount',
	'DONATION_AMOUNT_EXPLAIN'			=> 'Amount donated by the donor.',
	
	'DONATION_BODY_SETTINGS'			=> 'Donation page setup',
	
	'DONATION_PM_SETTINGS'				=> 'Alert PM',

	'DONATION_PM'                                   => 'PM text',
	'DONATION_PM_EXPLAIN'			        => 'PM text to be sent to the donor about winning next.<br />Use the following tags, if desired: {USER} : recipient ; {LAST}: date of last donation ; {EXPIRATION} : donation expiration date; {DAYS_TO_EXPIRE}: days until expiration; {OLD_AMOUNT}: value of the last donation; {NEW_AMOUNT}: current value of donation<br /><br />BB Code is available.',

	'DONATION_PM_SUBJECT'				=> 'PM subject',
	'DONATION_PM_SUBJECT_EXPLAIN'			=> 'Due warning PM subject to the donor.',

	'DONATION_EXPIRED_PM'				=> 'PM for overdue donations',
	'DONATION_EXPIRED_PM_EXPLAIN'			=> 'PM text to be sent to the donor about overdue donation.<br />Use the following tags, if desired: {USER} : recipient ; {LAST}: date of last donation ; {EXPIRATION} : donation expiration date; {DAYS_TO_EXPIRE}: days until expiration; {OLD_AMOUNT}: value of the last donation; {NEW_AMOUNT}: current value of donation<br /><br />BB Code is available.',

	'DONATION_EXPIRED_PM_SUBJECT'			=> 'PM overdue subject',
	'DONATION_EXPIRED_PM_SUBJECT_EXPLAIN'		=> 'PM overdue subject to be sent to the donor.',
	
	'DONATION_BODY'					=> 'Donation text page',
	'DONATION_BODY_EXPLAIN'				=> 'Enter a text to be displayed on the donation page.<br /><br />HTML is available.',

	'DONATION_SUCCESS_SETTINGS'			=> 'Successful donation page setup',

	'DONATION_SUCCESS'				=> 'Text of successful collaboration page',
	'DONATION_SUCCESS_EXPLAIN'			=> 'Enter a text to be displayed if the donation is successful.<br />This page appears after a successful donation.<br /><br />HTML is available.',

	'DONATION_CANCEL_SETTINGS'			=> 'Canceled donation page setup',

	'DONATION_CANCEL'				=> 'Successful donation page text',
	'DONATION_CANCEL_EXPLAIN'			=> 'Enter a text to be displayed if the donation is canceled.<br />This page appears after a canceled donation.<br /><br />HTML is available.',

	'DONATION_DISABLED'				=> 'Sorry, but the collaboration page is unavailable.',
	'DONATION_DISABLED_EMAIL'			=> 'Paypal account has not been set. Please notify the administrator.',
	'DONATION_NOT_INSTALLED'			=> 'The records of Donation Extension database were not found.<br />Please run the % sinstaller % s to make the change in the database.',
	'DONATION_NOT_INSTALLED_USER'			=> 'Donation page is not installed. Please notify the administrator.',
	'DONATION_TITLE'				=> 'Make a donation',
	'DONATION_DESCRIPTION'				=> 'Donation to',	
	'DONATION_TITLE_HEAD'				=> 'Make a donation to',
	'WE_HAVE_ACHIEVED'				=> 'We received',
	'WE_HAVE_ACHIEVED_IN'				=> 'in donations.',
	'OUR_DONATION_GOAL'				=> 'Our goal is to achieve',
	'DONATION_CANCELLED_TITLE'			=> 'Donation canceled',
	'DONATION_SUCCESSFULL_TITLE'			=> 'Successful donation',
	'DONATION_CONTACT_SERVICE'			=> 'Connecting... please wait...',
	'DONATION_BODY_DEFAULT'				=> 'Please help keep the forum running. Donate!',
	'DONATION_SUCCESS_DEFAULT'			=> 'Thanks for the donation. All forum participants will thank you.',
	'DONATION_CANCEL_DEFAULT'			=> 'You canceled your donation. No problem. Please, in the future consider becoming a donor.',
	'DONATION_ADMIN_DEFAULT'			=> 'This text can be changed in the ACP, under Donation extension.',
	'DONATIONS_INDEX'				=> 'Donations',
	'DONATION_BRL'					=> '$ BRL',
	'DONATION_USD'					=> '$ USD',
	'DONATION_EUR'					=> '€ EUR',
	'DONATION_GBP'					=> '£ GBP',
	'DONATION_JPY'					=> '¥ JPY',
	'DONATION_AUD'					=> '$ AUD',
	'DONATION_CAD'					=> '$ CAD',
	'DONATION_HKD'					=> '$ HKD',
	'DONATION_FREQUENCY'				=> 'Frequency',
	'DONATION_FREQUENCY_EXPLAIN'			=> 'The frequency with which donations should happen.',
	'DONATION_YEARLY'				=> 'Yearly',
	'DONATION_SEMESTRIAL'				=> 'Semestral',
	'DONATION_QUARTERLY'				=> 'Quarterly',
	'DONATION_MONTHLY'				=> 'Monthly',
	'DONATION_GROUP'				=> 'Group',
	'DONATION_GROUP_EXPLAIN'			=> 'Group to be attributed to donor members.',
	'DONATION_NO_GROUPS'				=> 'No valid group.',
	'DONATION_PAY_IMAGE_LABEL'			=> 'Click on the image below to continue',
	'DONATION_YEARLY_MSG'				=> 'valid for 1 year',
	'DONATION_SEMESTRIAL_MSG'			=> 'valid for 6 months',
	'DONATION_QUARTERLY_MSG'			=> 'valid for 3 months',
	'DONATION_MONTHLY_MSG'				=> 'valid for 1 month',
	'DONATION_CURRENCY_SYMBOL'			=> 'R$',
	'DONATION_DONATIONS'				=> 'Donations',
	
	'DONATION_ADD'					=> 'New donation',
	
	'DONATION_FIRST'				=> 'Donor since',
	'DONATION_FIRST_EXPLAIN'                        => 'First donation made by the member.',
	
	'DONATION_LAST'					=> 'Last donation',
	'DONATION_LAST_EXPLAIN'                         => 'Date of last donation or donation being registered.',
	
	'DONATION_EXPIRATION'				=> 'Donor until',
	'DONATION_EXPIRATION_EXPLAIN'                   => 'Date on which the collaboration wins.<br/>Leave blank to calculate automatically.',
	
	'DONATION_METHOD'				=> 'Method',
	'DONATION_METHOD_EXPLAIN'			=> 'Method that was used to receive the donation.',
		
	'DONATION_ADDED_BY_HUMAN' 			=> 'Included manually?',
	'DONATION_ADDED_BY_HUMAN_EXPLAIN'               => 'The collaboration has been or is being created or changed by the administrator?<br />Answer "No" if it was recorded automatically.',
	
	'DONATION_GIFT_SENT'				=> 'Gift sent?',
	'DONATION_GIFT_SENT_EXPLAIN'                    => 'The gift that came with the donation has been sent?',
	
	'DONATION_STATUS'				=> 'Donation status',
	'DONATION_STATUS_EXPLAIN'                       => 'Donation status. Inactive when due and canceled by the administrator; active, when the expiration date; and awaiting activation when it was still needed some action to that collaboration to take effect.',
	
	'DONATION_MSG_STATUS'				=> 'PM status',
	'DONATION_MSG_STATUS_EXPLAIN'			=> 'Status of sending the alert MPs of </> Due in 30 days.; due in 15 days; due in one day; expired.',	
	
	'DONATION_COMMENTS'				=> 'Comments',
	'DONATION_COMMENTS_EXPLAIN'			=> 'Observations and notes about giving. <br /> Does not appear for the donor.',

	'DONATION_EMPTY'				=> 'No collaboration performed or no collaboration has met the filter criteria.',
	'DONATION_NOT_LOGGED'				=> 'You must enter your registered user before continuing.',
	'DONATION_RENOVATION'				=> 'Paying again, consider a renovation, which should be valid',
	'DONATION_PAY_ALERT'				=> 'By continuing, do not use the next and back buttons of your browser. <br /> Use only the existing links and buttons on the bodies of the following pages.',
	'DONATION_REMAINING'				=> 'due in',
	
	'DONATION_ACTIVE'				=> 'Active',
	'DONATION_INACTIVE'				=> 'Inactive',
	'DONATION_WAITING_ACTIVATION'			=> 'Waiting activation',
	
	'DONATION_MSG_0'				=> 'No PM sent',
	'DONATION_MSG_1'				=> 'Warning 30 days sent',
	'DONATION_MSG_2'				=> 'Warning 15 days sent',
	'DONATION_MSG_3'				=> 'Warning 1 day sent',
	'DONATION_MSG_4'				=> 'Warning expired sent',
	
	'DONATION_DONOR'				=> 'Donor',
	'DONATION_DONOR_EXPLAIN'                        => 'Name of donor (username).',
	
	'DONATION_EXPIRED'				=> 'expired',
	'DONATION_EDIT'					=> 'Edit donation',
	'U_R_DONOR'                                     => 'Already a donor!',
	'DONATION_SEND_NOTICE'				=> 'The gifts will be sent to the address registered with PayPal.',
	
	'DONATION_METHOD_PAYPAL'                        => 'PayPal',
	'DONATION_METHOD_CHECKING_ACCOUNT'              => 'Checking account',
	'DONATION_METHOD_IN_PERSON'                     => 'In person',
	'DONATION_METHOD_OTHER'                         => 'Other',
	
	'ACP_DONATION_DELETE_CONFIRM'                   => 'Delete donor<br /><b>WARNING!</ B>You can not undo this operation.',
	'ACP_DONATION_ACTIVATE_CONFIRM'                 => 'Enable donation?',
	'ACP_DONATION_DEACTIVATE_CONFIRM'               => 'Disable donation?',
	'ACP_DONATION_SEND_GIFT_CONFIRM'                => 'Send gift?',
	'ACP_DONATION_SYNC_CONFIRM'			=> 'Synchronize now?<br />This operation will check who has expired or donations and add or remove from the group, as needed.<br />Also, updates the active donors count and update the statistics of the homepage.',
	'ACP_DONATION_MSG_CONFIRM'			=> 'Send message now? <br /> This operation sends PMs to for those with their donor to expires in 30, 15 and 1 day. <br /> Also, warn those who are with the unsuccessful collaboration. <br /> Only one alert message is sent to each type.',
	
	'DONATION_ACTIVATE'				=> 'Enable donation',
	'DONATION_DEACTIVATE'				=> 'Inactivate donation',
	'DONATION_SEND'					=> 'Send gift',

	'ACP_DONATIONS_ADD_DONATION'			=> 'New donation',
	'ACP_DONATIONS_ADD_DONATION_EXPLAIN'		=> 'Manually enter a donation',
	'ACP_DONATIONS_EDIT_DONATION'			=> 'Modify donation',
	'ACP_DONATIONS_EDIT_DONATION_EXPLAIN'		=> 'Change the data in a collaboration. Enter the shipment of gifts, for example.',
	'ACP_DONATIONS_MANAGE_DONATIONS'		=> 'Manage donations',
	'ACP_DONATIONS_MANAGE_DONATIONS_EXPLAIN'	=> 'Manage incoming donations.',
	'ACP_DONATIONS_SYNC_DONATIONS'			=> 'Donors synchronization',
	'ACP_DONATIONS_MSG_DONORS'			=> 'Sending messages to donors',
	
	'ACP_DONATION_CREATED_CHANGED_LOG'		=> '<strong>User donation</strong> %1$s <strong>added or edited</strong>',
	'ACP_GIFT_SENT_DONATION_LOG'                    => '<strong>Gift sent</strong> %1$s',
	'ACP_DONATION_DEACTIVATE_LOG'                   => '<strong>Donation inactivated</strong> %1$s',
	'ACP_DONATION_ACTIVATE_LOG'                     => '<strong>Donation activated</strong> %1$s',
	'DONATION_SAVED_LOG'                            => '<strong>Donation settings saved</strong>',
	'ACP_DELETE_DONATION_LOG'                       => '<strong>Donation removed</strong> %1$s',
	'DONATION_MSG_1_LOG'                            => '<strong>Warning 30 days to due sent</strong> %1$s',
	'DONATION_MSG_2_LOG'                            => '<strong>Warning 15 days to due sent</strong> %1$s',
	'DONATION_MSG_3_LOG'                            => '<strong>Warning due today sent</strong> %1$s',
	'DONATION_MSG_4_LOG'                            => '<strong>Warning expired sent</strong> %1$s',
	'DONATION_CRON_LOG'                             => 'Daily Cron synchronization and messages to donors performs in $12%s <br /> »Donors: %1$s Inactive: %2$s added to the group %3$s (%4$s) Removed from group: $5%s (%s$6)<br />»messages posted: %s$7» 30 days: %8$s 15 days: %9$s Today: %10$s Expiration: %$11s',
	
	'EXPIRATION_GT_LAST_EXCEPTION'                  => 'Expiration date must be great or equal Last donation date',
	'EMPTY_DONOR_EXCEPTION'                         => 'Donor may not be empty.',
	'DONOR_NOT_FOUND_EXCEPTION'                     => 'Donor not found!<br />There is no registered user corresponding to the donor.',
	'FIRST_GT_LAST_EXCEPTION'                       => 'First donation is later than the latest donation.',
	
	'ACP_DONATION_EDIT_SUCCESS'                     => 'Donation saved.',
	'ACP_DONATION_DELETE_SUCCESS'                   => 'Donation removed.',
	
	'DONORS'					=> 'Donors', 

	'ACTIVE_DONORS'					=> 'Active donors',
	'INACTIVE_DONORS'				=> 'Inactive donors',
	'DONORS_ADDED'					=> 'Donors added to group',
	'DONORS_REMOVED'				=> 'Donors removed from group',
	'MESSAGES_SENT'					=> 'PMs sent',
	
	'DONATION_SYNC'					=> 'Sync',
	'DONATION_SEND_MESSAGE'				=> 'Sent PMs',
	
	'LAST_DONOR'					=> 'Most recent donation',
));
