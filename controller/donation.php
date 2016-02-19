<?php
/**
*
* @package phpBB Extension - phpBB Paypal Donation
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @Author Stoker - http://www.phpbb3bbcodes.com
*
*/

namespace marcosfpo\donation\controller;

use \DateTime;
use \DateInterval;

class donation
{
	/**
	* The database tables
	*
	* @var string
	*/
	protected $conf_table;
	protected $doles_table;
	
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;
	
	/** @var \phpbb\log\log */
	protected $log;	

	/**
	* Constructor
	*
	* @param \phpbb\config\config				$config
	* @param \phpbb\controller\helper			$helper
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\request\request				$request
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\log\log_interface $log, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, $conf_table, $doles_table)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->request = $request;;
		$this->conf_table = $conf_table;
		$this->doles_table = $doles_table;
		$this->log = $log;
	}

	public function handle()
	{
		// Do we have the donation extension enabled
		if (isset($this->config['mfpo_donation_enable']) && $this->config['mfpo_donation_enable'] == 0)
		{
			trigger_error($this->user->lang['DONATION_DISABLED'], E_USER_NOTICE);
		}

		if (isset($this->config['mfpo_donation_email']) && $this->config['mfpo_donation_email'] == '')
		{
			trigger_error($this->user->lang['DONATION_DISABLED_EMAIL'], E_USER_NOTICE);
		}

		$sql = 'SELECT *
		FROM ' . $this->conf_table;
		$result = $this->db->sql_query($sql);
		$confs = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$confs[$row['config_name']] = $row['config_value'];
		}
		$this->db->sql_freeresult($result);
		
		$donation_body = isset($confs['donation_body']) ? $confs['donation_body'] : '';
		$donation_cancel = isset($confs['donation_cancel']) ? $confs['donation_cancel'] : '';
		$donation_success = isset($confs['donation_success']) ? $confs['donation_success'] : '';
		
		$success_url = generate_board_url() . '/app.php/donation?mode=success';
		$success_url_x = generate_board_url() . '/app.php/donation?mode=success_x';
		$cancel_url = generate_board_url() . '/app.php/donation?mode=cancel';

		$mode = $this->request->variable('mode', '');

		if (!empty($this->config['mfpo_donation_goal_enable']) && $this->config['mfpo_donation_goal'] > 0)
		{
			$donation_goal_number = ($this->config['mfpo_donation_achievement'] * 100) / $this->config['mfpo_donation_goal'];
			$this->template->assign_vars(array(
				'DONATION_GOAL_NUMBER'				=> round($donation_goal_number),
			));
		}
		
		$user_id = $this->user->data['user_id'];
		$user_date_format = $this->user->data['user_dateformat'];
		$user_date_format_wo_time = substr($user_date_format, ( strpos($user_date_format, '|') ), ( strrpos($user_date_format, '|') - strpos($user_date_format, '|') + 1 ));
		
		$frequency = $this->config['mfpo_donation_frequency'];
		$email = $this->config['mfpo_donation_email'];
		
		$added_by_human = 0;
		$gift_sent = 0;
		$status = 0; # 1: ativo; 0: inativo; 2: esperando ativação
		$method = 'PAYPAL';
		$first = new DateTime();
		$last = new DateTime();
		$expiration = new DateTime();
		$amount = 0.00;
		
		$sql = 'SELECT * FROM ' . $this->doles_table . ' WHERE user_id = ' . $user_id;
		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))
		{
			$last->setTimestamp($row['last_donation']);
			$first->setTimestamp($row['first_donation']);
			$expiration->setTimestamp($row['expiration']);
			$amount = $row['amount'];
			$status = $row['status'];
		}
		$this->db->sql_freeresult($result);
		
		$last_new = new DateTime();
		$expiration_new = $this->calculate_expiration_date($frequency, $last_new);	
		$amount_new = $this->config['mfpo_donation_amount'];
		
		// Lets build a page ...
		$this->template->assign_vars(array(
			'U_DONATE_SUCCESS'			=> $success_url,
			'U_DONATE_CANCEL'			=> $cancel_url,
			'DONATION_EMAIL'			=> $email,
			'DONATION_AMOUNT'			=> $amount,
			'DONATION_FREQUENCY'			=> $frequency,
			'DONATION_ACHIEVEMENT_ENABLE'		=> $this->config['mfpo_donation_achievement_enable'],
			'DONATION_ACHIEVEMENT'			=> $this->config['mfpo_donation_achievement'],
			'DONATION_GOAL_ENABLE'			=> $this->config['mfpo_donation_goal_enable'],
			'DONATION_GOAL'				=> $this->config['mfpo_donation_goal'],
			'DONATION_GOAL_CURRENCY_ENABLE'		=> $this->config['mfpo_donation_goal_currency_enable'],
			'DONATION_GOAL_CURRENCY'		=> $this->config['mfpo_donation_goal_currency'],
			'DONATION_BODY'				=> html_entity_decode($donation_body),
			'DONATION_CANCEL'			=> html_entity_decode($donation_cancel),
			'DONATION_SUCCESS'			=> html_entity_decode($donation_success),
			'DONATION_EXPIRATION'			=> $this->user->format_date($expiration->getTimestamp(), $user_date_format_wo_time),
			'DONATION_FIRST'			=> $this->user->format_date($first->getTimestamp(), $user_date_format_wo_time),
			'DONATION_LAST'				=> $this->user->format_date($last->getTimestamp(), $user_date_format_wo_time),
			'DONATION_STATUS'			=> $status,
			'DONATION_DAYS_TO_EXPIRE'		=> floor( ($expiration->getTimestamp() - time())/60/60/24 ),
			'DONATION_AMOUNT_NEW'			=> number_format($amount_new, 2, ".", ","),
			'DONATION_F_LAST_NEW'			=> $last_new->format('d/m/Y'),
			'DONATION_F_EXPIRATION_NEW'		=> $expiration_new->format('d/m/Y'),
			'DONATION_F_AMOUNT'			=> number_format($amount, 2, ",", "."),
			'DONATION_F_AMOUNT_NEW'			=> number_format($amount_new, 2, ",", "."),
		));

		// Set up Navlinks
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->user->lang('DONATION_TITLE'),
			'U_VIEW_FORUM' => $this->helper->route('marcosfpo_donation_controller'),
		));

		switch ($mode)
		{
			case 'success':
				$this->update_donation($user_id, $last_new, $expiration_new, $amount_new, $method, $added_by_human, $gift_sent, $status);
				$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'ACP_DONATION_CREATED_CHANGED_LOG', time(), array($this->user->data['user_id']));
				return $this->helper->render('donate/success_body.html', $this->user->lang('DONATION_SUCCESSFULL_TITLE'));
			break;
			case 'cancel':
				return $this->helper->render('donate/cancel_body.html', $this->user->lang('DONATION_CANCELLED_TITLE'));
			break;
			default;
				return $this->helper->render('donate/donate_body.html', $this->user->lang('DONATION_TITLE'));
			break;
		}
	}
	
	public function update_donation($user_id, $last, $expiration, $amount, $method = 'PAYPAL', $added_by_human = 0, $gift_sent = 0)
	{
		$first = new DateTime();
	
		$sql = 'INSERT INTO ' . $this->doles_table . '(user_id, first_donation, last_donation, expiration, amount, method, added_by_human, gift_sent, status) '
		. 'VALUES (' 
			. (int) $user_id . ', ' 
			. $first->getTimestamp() . ', ' 
			. $last->getTimestamp() . ', ' 
			. $expiration->getTimestamp() . ', ' 
			. (double) $amount . ', ' 
			. "'" . $this->db->sql_escape($method) . "', " 
			. (int) $added_by_human . ', ' 
			. (int) $gift_sent . ', ' 
			. '2) '
		. 'ON DUPLICATE KEY UPDATE '
			. 'last_donation = ' . $last->getTimestamp() . ', ' 
			. 'expiration = ' . $expiration->getTimestamp() . ', ' 
			. 'amount = ' . (double) $amount . ', ' 
			. "method = '" . $this->db->sql_escape($method) . "', " 
			. 'added_by_human = ' . (int) $added_by_human . ', ' 
			. 'gift_sent = ' . (int) $gift_sent . ', ' 
			. 'status = 2;';
		
		$this->db->sql_query($sql);
	}
	
	public function calculate_expiration_date($frequency, $donation_date)
	{
		$diff1year  = new DateInterval('P1Y');
		$diff6month = new DateInterval('P6M');
		$diff3month = new DateInterval('P3M');
		$diff1month = new DateInterval('P1M');
		
		$expiration = new DateTime();
		$expiration->setTimestamp($donation_date->getTimestamp());
		
		$expiration->add($diff1year);
		
		switch ($frequency) {
			case 2:
				$expiration->add($diff6month);
				break;
			case 3:
				$expiration->add($diff3month);
				break;
			case 4:
				$expiration->add($diff1month);
				break;
		}
		
		return $expiration;
	}
	
	public function days_to_expire($expiration)
	{
		$now = new DateTime();
		$datediff = ( $expiration->getTimestamp() - date() )/60/60/24;
		
		return $datediff/60/60/24;
	}
}