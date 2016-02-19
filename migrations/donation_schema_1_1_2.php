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

namespace marcosfpo\donation\migrations;

class donation_schema_1_1_2 extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array(
			'\marcosfpo\donation\migrations\donation_schema_1_1_1',
		);
	}
	
	public function update_data()
	{
		return array(
			array('config.update', array('mfpo_donation_version', '1.1.2')),
			array('config.remove', array('mfpo_donors_check_last_run')),
			array('config.remove', array('mfpo_donors_check_run')),
			array('config.add', array('donations_check_last_gc', 0, true)),
			array('config.add', array('donations_check_gc', (60 * 60 * 24))),
		);
	}
	
}