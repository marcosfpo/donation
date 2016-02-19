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

class donation_schema_0_1_0 extends \phpbb\db\migration\migration
{

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('mfpo_donation_version', '0.1.0')),
			array('config.add', array('mfpo_donation_enable', 1)),
			array('config.add', array('mfpo_donation_email', '')),
			array('config.add', array('mfpo_donation_amount', 55)),
			array('config.add', array('mfpo_donation_frequency', 1)),
			array('config.add', array('mfpo_donation_group_id', '')),
			array('config.add', array('mfpo_donation_achievement_enable', 0)),
			array('config.add', array('mfpo_donation_achievement', '')),
			array('config.add', array('mfpo_donation_goal_enable', 0)),
			array('config.add', array('mfpo_donation_goal', '')),
			array('config.add', array('mfpo_donation_goal_currency_enable', 0)),
			array('config.add', array('mfpo_donation_goal_currency', '')),
			array('config.add', array('mfpo_donation_index_enable', 0)),
			array('config.add', array('mfpo_donation_index_top', 0)),
			array('config.add', array('mfpo_donation_index_bottom', 0)),

         		array('config.add', array('mfpo_donors_check_last_run', 0)), // last run
		        array('config.add', array('mfpo_donors_check_run', (60 * 60 * 24))), // seconds between run; 1 day
		        
		        array('custom', array(array($this, 'migrate_donations'))),
			array('custom', array(array($this, 'insert_sample_data'))),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'mfpo_donation_conf'	=> array(
					'COLUMNS'	=> array(
						'config_name'		=> array('VCHAR', ''),
						'config_value'		=> array('TEXT', ''),
					),
					'PRIMARY_KEY'	=> 'config_name',
				),
				$this->table_prefix . 'mfpo_donation_doles'	=> array(
                    			'COLUMNS' => array(
			                        'user_id' => array('UINT', 0),
			                        'first_donation' => array('TIMESTAMP', 0),
			                        'last_donation' => array('TIMESTAMP', 0),
			                        'expiration' => array('TIMESTAMP', 0),
			                        'amount' => array('DECIMAL', 0),
			                        'method' => array('VCHAR', 'PAYPAL'),
			                        'added_by_human' => array('BOOL', 0),
			                        'gift_sent' => array('BOOL', 0),
			                        'status' => array('TINT:1', 0),
			                    ),
			                'PRIMARY_KEY' => 'user_id',
			                'KEYS' => array(
			                        'first' => array('INDEX', 'first_donation'),
			                        'last' => array('INDEX', 'last_donation'),
			                        'expir' => array('INDEX', 'expiration'),
			                        'meth' => array('INDEX', 'method'),
			                        'stat' => array('INDEX', 'status'),
			                ),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'mfpo_donation_conf',
				$this->table_prefix . 'mfpo_donation_doles',
			),
		);
	}
	
	public function insert_sample_data()
	{
		global $user;

		$sample_data = array(
				array(
					'config_name' 	=> 'donation_body',
					'config_value'	=> '<p style="font-size:large;">Clube dos Colaboradores do CXT Brasil</p>
<div>
Um dos fundamentos do CXT Brasil é a participação e acesso a informação do site de forma livre e gratuita.<br /><br />
Porém, se você quiser ajudar a manter e incentivar as melhorias no fórum, pode se tornar um colaborador. Cada contribuição é válida por um ano.<br /><br />
Como retorno pela colaboração você poderá concorrer aos sorteios periódicos de produtos fornecidos pelas empresas apoiadoras do CXT Brasil.<br /><br />
O fórum continua livre e aberto a todos, não havendo nenhuma obrigação de participação.
</div>
<br/><br/>
<p style="font-size:large;color:red;">Colaborando, você ganha um kit de adesivos do CXT!</p>',
				),
				array(
					'config_name' 	=> 'donation_success',
					'config_value'	=> '<p style="font-size:large;">Tudo certo, sua colaboração está efetivada! </p>

<div>
Muito obrigado!! <br />
Não esqueça de ficar de olho nos anúncios que rolam no fórum, para não perder a chance de se inscrever nos sorteios!<br />
Qualquer dúvida, estamos à disposição. <br />
Quando faltar 15 dias para o vencimento da sua anuidade, nosso sistema irá lhe enviar um aviso. <br /><br />
Abraço.
</div>',
				),
				array(
					'config_name' 	=> 'donation_cancel',
					'config_value'	=> '<p style="font-size:large;">Você cancelou o processo de pagamento antes da colaboração ser realizada!</p>

<div>
Mesmo assim, agradecemos ter considerado a opção de se tornar um colaborador.<br />
Caso reconsidere, saiba que todos os membros CXT Brasil serão beneficiados com sua ajuda, pois você estará ajudando a manter o fórum no ar.<br /><br />
Abraço.
</div>',
				),
				array(
					'config_name' 	=> 'donation_pm',
					'config_value'	=> '[mod=CXT Brasil]Não se preocupe! Estamos apenas testando.[/mod]

[glow=darkblue][size=130]Sua colaboração está para vencer e o CXT Brasil precisa muito de você para continuar existindo![/size][/glow]

Última colaboração: <xxxx>
Data de vencimento: <xxxx>
Valor antigo: <xxxx>

[b]Renove sua colaboração clicando aqui:[/b] [url]http://forumxt600.com.br/forum_317_teste/app.php/donation[/url]

A colaboração é acumulativa e, portanto, pode ser feita a qualquer momento.',
				),
				array(
					'config_name' 	=> 'donation_pm_subject',
					'config_value'	=> 'Sua colaboração está para vencer!',
				),
		);

		// Insert sample PM data
		$this->db->sql_multi_insert($this->table_prefix . 'mfpo_donation_conf', $sample_data);
	}
	
	public function migrate_donations()
	{
		$sql = 'INSERT INTO `cxt3_mfpo_donation_doles`(
			`user_id`, 
			`first_donation`, 
			`last_donation`, 
			`expiration`, 
			`amount`, 
			`method`, 
			`added_by_human`, 
			`gift_sent`, 
			`status`) 
			SELECT 
			`user_id`, 
			`join_date` as first_donation, 
			`renew_date` as last_donation, 
			UNIX_TIMESTAMP(DATE_ADD(FROM_UNIXTIME(`renew_date`), INTERVAL 1 YEAR)) as expiration, 
			0 as amount, 
			\'CHECKING ACCOUNT\' as method, 
			0 as added_by_human, 
			0 as gift_sent, 
			(!`user_pending`) as sts 
			FROM `cxt3_user_group` 
			WHERE `group_id` = 8727';
		$this->db->sql_query($sql);
	}
	
}