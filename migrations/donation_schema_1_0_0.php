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

class donation_schema_1_0_0 extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array(
			'\marcosfpo\donation\migrations\donation_schema_0_1_0',
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.update', array('mfpo_donation_version', '1.0.0')),
		);
	}

}