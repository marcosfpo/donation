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

class donation_schema_1_1_1 extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array(
			'\marcosfpo\donation\migrations\donation_schema_1_1_0',
		);
	}
	
	public function update_schema()
	{
		return array(
			'add_columns' => array(
            			$this->table_prefix . 'mfpo_donation_doles' => array(
		                	'msg_status' => array('TINT:1', 0),
		                	'comments' => array('TEXT', ''),
            			),
			),
			'add_index' => array(
            			$this->table_prefix . 'mfpo_donation_doles' => array(
	            			'msts' => array('msg_status'),
            			),
				
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.update', array('mfpo_donation_version', '1.1.1')),
			array('custom', array(array($this, 'insert_sample_data'))),
		);
	}
	
	public function insert_sample_data()
	{
		$sample_data = array(
				array(
					'config_name' 	=> 'donation_expired_pm',
					'config_value'	=> '[b][size=130]{USER}, sua colaboração venceu, mas o CXT Brasil precisa muito de você para continuar existindo![/size][/b]

Última colaboração: {LAST}
Data de vencimento: {EXPIRATION}

O valor atual da colaboração é de {NEW_AMOUNT}.

[b]Renove sua colaboração clicando aqui:[/b] [url]http://forumxt600.com.br/forum/app.php/donation[/url]

A colaboração é cumulativa (começa ao término da outra) e, portanto, pode ser feita a qualquer momento.',
				),
				array(
					'config_name' 	=> 'donation_expired_pm_subject',
					'config_value'	=> 'Sua colaboração venceu!',
				),
		);

		// Insert sample PM data
		$this->db->sql_multi_insert($this->table_prefix . 'mfpo_donation_conf', $sample_data);
	}	
}