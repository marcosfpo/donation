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

use Symfony\Component\DependencyInjection\ContainerInterface;
use \DateTime;
use \DateInterval;

/**
* Admin controller
*/
class admin_controller
{
	/**
	* The database tables
	*
	* @var string
	*/
	protected $config_table;
	protected $doles_table;

	/** @var \phpbb\config\config */
	protected $config;

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
	
	public $u_action;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\template\template			$template
	 * @param \phpbb\user						$user
	 * @param \phpbb\db\driver\driver_interface	$db
	 * @param \phpbb\request\request			$request
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\log\log_interface $log, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, $config_table, $doles_table)
	{
		$this->config = $config;
		$this->template = $template;
		$this->log = $log;
		$this->user = $user;
		$this->db = $db;
		$this->request = $request;
		$this->config_table = $config_table;
		$this->doles_table = $doles_table;
		
		global $phpbb_root_path, $phpEx;
		include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
		$this->user->add_lang('acp/groups');
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_config()
	{
		add_form_key('config_form');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('config_form'))
			{
				$error[] = 'FORM_INVALID';
			}

			$confs_row = array(
				'donation_body' 		=> $this->request->variable('donation_body', '', true),
				'donation_cancel' 		=> $this->request->variable('donation_cancel', '', true),
				'donation_success' 		=> $this->request->variable('donation_success', '', true),
				'donation_pm'			=> $this->request->variable('donation_pm', '', true),
				'donation_pm_subject'   	=> $this->request->variable('donation_pm_subject', '', true),
				'donation_expired_pm'		=> $this->request->variable('donation_expired_pm', '', true),
				'donation_expired_pm_subject'   => $this->request->variable('donation_expired_pm_subject', '', true),
			 );

			foreach ($confs_row as $this->config_name => $this->config_value)
			{
				$sql = 'UPDATE ' . $this->config_table . "
					SET config_value = '" . $this->db->sql_escape($this->config_value) . "'
					WHERE config_name = '" . $this->db->sql_escape($this->config_name) . "'";
				$this->db->sql_query($sql);
			}

			$this->set_config();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'DONATION_SAVED_LOG');

			trigger_error($this->user->lang['DONATION_SAVED'] . adm_back_link($this->u_action));
		}

		// let's get it on
		$sql = 'SELECT * FROM ' . $this->config_table;
		$result = $this->db->sql_query($sql);
		$confs = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$confs[$row['config_name']] = $row['config_value'];
		}
		$this->db->sql_freeresult($result);

		
		$sql = 'SELECT group_id, group_name, group_type
			FROM ' . GROUPS_TABLE . '
			WHERE ' . $this->db->sql_in_set('group_name', array('BOTS', 'GUESTS', 'ADMINISTRATORS', 'GLOBAL_MODERATORS', 'REGISTERED', 'REGISTERED_COPPA', 'NEWLY_REGISTERED'), true, true) .
				(($exclude_predefined_groups) ? ' AND group_type <> ' . GROUP_SPECIAL : '') . '
			ORDER BY group_name';
		
		$result = $this->db->sql_query($sql);
		$groups = array();
		while ($row = $this->db->sql_fetchrow($result))
        	{
            		$group_id = $row['group_id'];
            		$group_name = ($row['group_type'] == GROUP_SPECIAL) ? $this->user->lang['G_' . $row['group_name']] : $row['group_name'];
            		
            		$groups[$group_id] = $group_name; 
        	}
        	$this->db->sql_freeresult($result);

		$donation_body = isset($confs['donation_body']) ? $confs['donation_body'] : '';
		$donation_cancel = isset($confs['donation_cancel']) ? $confs['donation_cancel'] : '';
		$donation_success = isset($confs['donation_success']) ? $confs['donation_success'] : '';

		$donation_pm = isset($confs['donation_pm']) ? $confs['donation_pm'] : '';
		$donation_pm_subject = isset($confs['donation_pm_subject']) ? $confs['donation_pm_subject'] : '';
		$donation_expired_pm = isset($confs['donation_expired_pm']) ? $confs['donation_expired_pm'] : '';
		$donation_expired_pm_subject = isset($confs['donation_expired_pm_subject']) ? $confs['donation_expired_pm_subject'] : '';
		
		$donation_version = isset($this->config['mfpo_donation_version']) ? $this->config['mfpo_donation_version'] : '';

		$this->template->assign_vars(array(
			'DONATION_VERSION'				=> $donation_version,
			'DONATION_ENABLE'				=> $this->config['mfpo_donation_enable'],
			'DONATION_INDEX_ENABLE'				=> $this->config['mfpo_donation_index_enable'],
			'DONATION_INDEX_TOP'				=> $this->config['mfpo_donation_index_top'],
			'DONATION_INDEX_BOTTOM'				=> $this->config['mfpo_donation_index_bottom'],
			'DONATION_EMAIL'				=> $this->config['mfpo_donation_email'],
			'DONATION_AMOUNT'				=> $this->config['mfpo_donation_amount'],
			'DONATION_FREQUENCY'				=> $this->config['mfpo_donation_frequency'],
			'DONATION_GROUP_ID'				=> $this->config['mfpo_donation_group_id'],
			'DONATION_ACHIEVEMENT_ENABLE'			=> $this->config['mfpo_donation_achievement_enable'],
			'DONATION_ACHIEVEMENT'				=> $this->config['mfpo_donation_achievement'],
			'DONATION_GOAL_ENABLE'				=> $this->config['mfpo_donation_goal_enable'],
			'DONATION_GOAL'					=> $this->config['mfpo_donation_goal'],
			'DONATION_GOAL_CURRENCY_ENABLE'			=> $this->config['mfpo_donation_goal_currency_enable'],
			'DONATION_GOAL_CURRENCY'			=> $this->config['mfpo_donation_goal_currency'],
			'DONATION_BODY'					=> $donation_body,
			'DONATION_CANCEL'				=> $donation_cancel,
			'DONATION_SUCCESS'				=> $donation_success,
			'DONATION_PM'					=> $donation_pm,
			'DONATION_PM_SUBJECT'				=> $donation_pm_subject,
			'DONATION_EXPIRED_PM'				=> $donation_expired_pm,
			'DONATION_EXPIRED_PM_SUBJECT'			=> $donation_expired_pm_subject,
			'U_ACTION'					=> $this->u_action)
		);		

		$group_id = $this->config['mfpo_donation_group_id'];
		foreach($groups as $key=>$value)
		{
			$selected = ($group_id == $key);
            		$this->template->assign_block_vars('groups', array(
					'GROUP_ID'	=> $key,
					'GROUP_NAME'	=> $value,
					'S_SELECTED'	=> $selected,			
			));
		}
        	
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_config()
	{
		$this->config->set('mfpo_donation_enable', $this->request->variable('donation_enable', 1));
		$this->config->set('mfpo_donation_index_enable', $this->request->variable('donation_index_enable', 0));
		$this->config->set('mfpo_donation_index_top', $this->request->variable('donation_index_top', 0));
		$this->config->set('mfpo_donation_index_bottom', $this->request->variable('donation_index_bottom', 0));
		$this->config->set('mfpo_donation_email', $this->request->variable('donation_email', ''));
		$this->config->set('mfpo_donation_amount', $this->request->variable('donation_amount', ''));
		$this->config->set('mfpo_donation_frequency', $this->request->variable('donation_frequency', 1));
		$this->config->set('mfpo_donation_group_id', $this->request->variable('donation_group_id', 0));
		$this->config->set('mfpo_donation_goal_enable', $this->request->variable('donation_goal_enable', 0));
		$this->config->set('mfpo_donation_goal', $this->request->variable('donation_goal', ''));
		$this->config->set('mfpo_donation_achievement_enable', $this->request->variable('donation_achievement_enable', 0));
		$this->config->set('mfpo_donation_achievement', $this->request->variable('donation_achievement', ''));
		$this->config->set('mfpo_donation_goal_currency_enable', $this->request->variable('donation_goal_currency_enable', 0));
		$this->config->set('mfpo_donation_goal_currency', $this->request->variable('donation_goal_currency', '', true));
	}
	
	
	public function display_donations()
	{
		$line = 0;
		$current_user_id = $this->user->data['user_id'];
		$user_date_format = $this->user->data['user_dateformat'];
		$user_date_format_wo_time = substr($user_date_format, ( strpos($user_date_format, '|') ), ( strrpos($user_date_format, '|') - strpos($user_date_format, '|') + 1 ));
	
		$sql = 'SELECT u.user_id as userid, 
		          u.username as usn, 
		          d.first_donation as frd, 
		          d.last_donation as lsd, 
		          d.expiration as exr, 
		          d.amount as amn, 
		          d.method as mth, 
		          d.added_by_human as adh, 
		          d.gift_sent as gfs, 
		          d.status as sts,
		          d.msg_status as msgsts '
			. 'FROM ' . $this->doles_table . ' d INNER JOIN ' . USERS_TABLE . ' u '
			. 'ON d.user_id = u.user_id '
			. 'ORDER BY last_donation DESC';
		
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$line++;
			$userid	= $row['userid'];
			$this->template->assign_block_vars('donations', array(
				'S_COUNT_LINE'	 => $line,
				'USERID'	 => $userid,
				'USER'	         => $row['usn'],
				'FIRST'          => $this->user->format_date($row['frd'], $user_date_format_wo_time), 
				'LAST'           => $this->user->format_date($row['lsd'], $user_date_format_wo_time), 
				'EXPIRATION'     => $this->user->format_date($row['exr'], $user_date_format_wo_time), 
				'AMOUNT'         => number_format($row['amn'], 2, ",", "."),
				'METHOD'         => $row['mth'],
				'GIFT_SENT'      => $row['gfs'],
				'STATUS'         => $row['sts'],
				'ADDED_BY_HUMAN' => $row['adh'],
				'MSG_STATUS'     => $row['msgsts'],
				
				'S_DAYS_TO_EXPIRE'	=> floor( ($row['exr'] - time())/60/60/24 ),
				
				'U_DELETE_DONATION'	=> "{$this->u_action}&amp;action=delete&amp;user_id=" . $userid,
				'U_EDIT_DONATION'	=> "{$this->u_action}&amp;action=edit&amp;user_id=" . $userid,
				'U_ACTIVATE_DONATION'	=> "{$this->u_action}&amp;action=activate&amp;user_id=" . $userid,
				'U_DEACTIVATE_DONATION'	=> "{$this->u_action}&amp;action=deactivate&amp;user_id=" . $userid,
				'U_SEND_GIFT'		=> "{$this->u_action}&amp;action=send_gift&amp;user_id=" . $userid,
			));
		}
		$this->db->sql_freeresult($result);
        	
		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'U_ACTION'	   => $this->u_action,
			'U_ADD_DONATION'   => "{$this->u_action}&amp;action=add",
        		'U_SYNC_DONATIONS' => "{$this->u_action}&amp;action=sync",
        		'U_MSG_DONATIONS'  => "{$this->u_action}&amp;action=msg",
		));		
	}
	
	public function add_donation()
	{
		$this->add_edit_donation();
		
		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'S_ADD_DONATION'	=> true,
			'U_ACTION'		=> "{$this->u_action}&amp;action=add",
		));
	}

	public function edit_donation($donation_id)
	{
	
		$this->add_edit_donation($donation_id);

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'S_EDIT_DONATION'	=> true,
			'U_ACTION'		=> "{$this->u_action}&amp;action=edit",
		));
	}

	public function delete_donation($donation_id)
	{
		$this->do_delete_donation($donation_id);
				
		// If AJAX was used, show user a result message
		if ($this->request->is_ajax())
		{
			$json_response = new \phpbb\json_response;
								$json_response->send(array(
			'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
			'MESSAGE_TEXT'	=> $this->user->lang('ACP_DONATION_DELETE_SUCCESS'),
			'REFRESH_DATA'	=> array(
					'time'	=> 3
				)
			));
		}
	}

	public function activate_donation($donation_id)
	{
		$this->do_activate_donation($donation_id);
		
		// Show user confirmation of the saved donation and provide link back to the previous screen
		trigger_error($this->user->lang('ACP_DONATION_EDIT_SUCCESS') . adm_back_link($this->u_action));
	}

	public function deactivate_donation($donation_id)
	{
		$this->do_deactivate_donation($donation_id);
		
		// Show user confirmation of the saved donation and provide link back to the previous screen
		trigger_error($this->user->lang('ACP_DONATION_EDIT_SUCCESS') . adm_back_link($this->u_action));
	}
	
	public function send_gift_donation($donation_id)
	{
		$this->do_send_gift_donation($donation_id);
		
		// Show user confirmation of the saved donation and provide link back to the previous screen
		trigger_error($this->user->lang('ACP_DONATION_EDIT_SUCCESS') . adm_back_link($this->u_action));
	}
	
	protected function do_delete_donation($donation_id)
	{
		$group_id = $this->config['mfpo_donation_group_id'];
	
		$sql = 'DELETE FROM ' . $this->doles_table . ' WHERE user_id = ' . $donation_id . ';';			
		$this->db->sql_query($sql);
		$this->update_group_members($donation_id);

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_DELETE_DONATION_LOG', time(), array($donation_id));	
	}
	
	protected function do_activate_donation($donation_id)
	{
		$sql = 'UPDATE ' . $this->doles_table . ' SET status = 1, msg_status = 0 WHERE user_id = ' . $donation_id . ';';
		$this->db->sql_query($sql);
		$this->update_group_members($donation_id);

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_DONATION_ACTIVATE_LOG', time(), array($donation_id));
	}
	
	protected function do_deactivate_donation($donation_id)
	{
		$sql = 'UPDATE ' . $this->doles_table . ' SET status = 0 WHERE user_id = ' . $donation_id . ';';			
		$this->db->sql_query($sql);
		$this->update_group_members($donation_id);

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_DONATION_DEACTIVATE_LOG', time(), array($donation_id));
	}
	
	protected function do_send_gift_donation($donation_id)
	{
		$sql = 'UPDATE ' . $this->doles_table . ' SET gift_sent = 1 WHERE user_id = ' . $donation_id . ';';			
		$this->db->sql_query($sql);
		
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_GIFT_SENT_DONATION_LOG', time(), array($donation_id));
	}
	
	protected function add_edit_donation($donation_id = 0)
	{
		$errors = array();

		// Is the form submitted
		$submit = $this->request->is_set_post('submit');

		$first = new DateTime();
		$last = new DateTime();
		$expiration = new DateTime();
		
		$firstf = $first->format('Y-m-d');
		$expiration = $this->calculate_expiration_date($this->config['mfpo_donation_frequency'], $last);
		$expirationf = $expiration->format('Y-m-d');
		
		list($last_year, $last_month, $last_day) = explode('-', $firstf);
		list($first_year, $first_month, $first_day) = explode('-', $firstf);
		list($expiration_year, $expiration_month, $expiration_day) = explode('-', $expirationf);

		$donor = '';
		$method = '';
		$amount = $this->config['mfpo_donation_amount'];
		$s_added_by_human = 1;
		$s_gift_sent = 0;
		$s_status = 0;
		$s_msg_status = 0;
		$comments = '';
		
		if ($donation_id <> 0)
		{
			$sql = 'SELECT u.user_id as userid, 
					u.username as usn, 
					FROM_UNIXTIME(d.first_donation, \'%Y-%m-%d\') as frd, 
					FROM_UNIXTIME(d.last_donation, \'%Y-%m-%d\') as lsd, 
					FROM_UNIXTIME(d.expiration, \'%Y-%m-%d\') as exr, 
					d.amount as amn, 
					d.method as mth, 
					d.added_by_human as adh, 
					d.gift_sent as gfs, 
					d.status as sts,
					d.msg_status as msgsts,
					d.comments as comm '
				. 'FROM ' . $this->doles_table . ' d INNER JOIN ' . USERS_TABLE . ' u '
					. 'ON d.user_id = u.user_id '
					. 'WHERE d.user_id = ' . $donation_id . ';';			
			
			$result = $this->db->sql_query($sql);
			if ($row = $this->db->sql_fetchrow($result))
			{
				list($first_year, $first_month, $first_day) = explode('-', $row['frd']);
				list($last_year, $last_month, $last_day) = explode('-', $row['lsd']);
				list($expiration_year, $expiration_month, $expiration_day) = explode('-', $row['exr']);

				$donation_id = $row['userid'];
				$donor = $row['usn'];
				$amount = $row['amn'];
				$method = $row['mth'];
				$s_added_by_human = $row['adh'];
				$s_gift_sent = $row['gfs'];
				$s_status = $row['sts'];
				$s_msg_status = $row['msgsts'];
				$comments = $row['comm'];
			}
			$this->db->sql_freeresult($result);	
		}
		
		$donation_id = $this->request->variable('donation_id', $donation_id);
		$donor = utf8_normalize_nfc($this->request->variable('donor', $donor, true));
		$last_day =  $this->request->variable('last_day', $last_day);
		$last_month =  $this->request->variable('last_month', $last_month);
		$last_year =  $this->request->variable('last_year', $last_year);
		$expiration_day = $this->request->variable('expiration_day', $expiration_day);
		$expiration_month = $this->request->variable('expiration_month', $expiration_month);
		$expiration_year = $this->request->variable('expiration_year', $expiration_year);
		$first_day = $this->request->variable('first_day', $first_day);
		$first_month = $this->request->variable('first_month', $first_month);
		$first_year = $this->request->variable('first_year', $first_year);
		$amount = $this->request->variable('amount', $amount);
		$method = $this->request->variable('method', $method);
		$s_added_by_human = $this->request->variable('s_added_by_human', $s_added_by_human);
		$s_gift_sent = $this->request->variable('s_gift_sent', $s_gift_sent);
		$s_status = $this->request->variable('s_status', $s_status);
		$s_msg_status = $this->request->variable('s_msg_status', $s_msg_status);
		$comments = utf8_normalize_nfc($this->request->variable('comments', $comments, true));

		$first->setDate($first_year, $first_month, $first_day);
		$last->setDate($last_year, $last_month, $last_day);
		$expiration->setDate($expiration_year, $expiration_month, $expiration_day);
		
		$firstf = implode('-', array($first_year, $first_month, $first_day));
		$lastf = implode('-', array($last_year, $last_month, $last_day));
		$expirationf = implode('-', array($expiration_year, $expiration_month, $expiration_day));
		
		//determina qual formulário é o que se está trabalhando
		add_form_key('add_edit_donation_form');
		
		if ($submit) 
		{
			if (!check_form_key('add_edit_donation_form', -1))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			} 
			else 
			{
				if (empty($donor)) {
					$errors[] = $this->user->lang('EMPTY_DONOR_EXCEPTION');
				} 
				elseif ($donation_id == 0) 
				{
					$sql = 'SELECT user_id
						FROM ' . USERS_TABLE . '
						WHERE username = "' . $donor . '"';
			
					$result = $this->db->sql_query($sql);
				
					if ($row = $this->db->sql_fetchrow($result))
					{
						$donation_id = $row['user_id'];
					}
					else
					{
						$errors[] = $this->user->lang('DONOR_NOT_FOUND_EXCEPTION');
					}
	        			$this->db->sql_freeresult($result);
	        		}
	        		
	        		if ($expiration < $last)
				{
					$errors[] = $this->user->lang('EXPIRATION_GT_LAST_EXCEPTION');
				}
				
				if ($first > $last)
				{
					$errors[] = $this->user->lang('FIRST_GT_LAST_EXCEPTION');
				}
			}			
			
			if (empty($errors))
			{
				$this->update_donation($donation_id, $first, $last, $expiration, $amount, $method, $s_added_by_human, $s_gift_sent, $s_status, $s_msg_status, $comments);
				$this->update_group_members($donation_id);
				
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_DONATION_CREATED_CHANGED_LOG', time(), array($donor));
				
				// Show user confirmation of the saved donation and provide link back to the previous screen
				trigger_error($this->user->lang('ACP_DONATION_EDIT_SUCCESS') . adm_back_link($this->u_action));
			}
		}
		
		$s_first_day_options = '<option value="0"' . ((!$first_day) ? ' selected="selected"' : '') . '>--</option>';
		$s_last_day_options = '<option value="0"' . ((!$last_day) ? ' selected="selected"' : '') . '>--</option>';
		$s_expiration_day_options = '<option value="0"' . ((!$expiration_day) ? ' selected="selected"' : '') . '>--</option>';

		$s_first_month_options = '<option value="0"' . ((!$first_month) ? ' selected="selected"' : '') . '>--</option>';
		$s_last_month_options = '<option value="0"' . ((!$last_month) ? ' selected="selected"' : '') . '>--</option>';
		$s_expiration_month_options = '<option value="0"' . ((!$expiration_month) ? ' selected="selected"' : '') . '>--</option>';

		$s_first_year_options = '<option value="0"' . ((!$first_year) ? ' selected="selected"' : '') . '>--</option>';
		$s_last_year_options = '<option value="0"' . ((!$last_year) ? ' selected="selected"' : '') . '>--</option>';
		$s_expiration_year_options = '<option value="0"' . ((!$expiration_year) ? ' selected="selected"' : '') . '>--</option>';

		for ($i = 1; $i < 32; $i++)
		{
			$selected = ($i == $first_day) ? ' selected="selected"' : '';
			$s_first_day_options .= "<option value=\"$i\"$selected>$i</option>";
			
			$selected = ($i == $last_day) ? ' selected="selected"' : '';
			$s_last_day_options .= "<option value=\"$i\"$selected>$i</option>";

			$selected = ($i == $expiration_day) ? ' selected="selected"' : '';
			$s_expiration_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		for ($i = 1; $i < 13; $i++)
		{
			$selected = ($i == $first_month) ? ' selected="selected"' : '';
			$s_first_month_options .= "<option value=\"$i\"$selected>$i</option>";

			$selected = ($i == $last_month) ? ' selected="selected"' : '';
			$s_last_month_options .= "<option value=\"$i\"$selected>$i</option>";

			$selected = ($i == $expiration_month) ? ' selected="selected"' : '';
			$s_expiration_month_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		$s_birthday_year_options = '';

		$now = getdate();
		for ($i = 2003; $i <= ($now['year'] + 15); $i++)
		{
			$selected = ($i == $first_year) ? ' selected="selected"' : '';
			$s_first_year_options .= "<option value=\"$i\"$selected>$i</option>";

			$selected = ($i == $last_year) ? ' selected="selected"' : '';
			$s_last_year_options .= "<option value=\"$i\"$selected>$i</option>";

			$selected = ($i == $expiration_year) ? ' selected="selected"' : '';
			$s_expiration_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		unset($now);
		
		$this->template->assign_vars(array(
			'DONATION_ID'	   => $donation_id,
			'DONOR'	           => $donor,
			'AMOUNT'           => $amount,
			'METHOD'           => $method,
			'S_GIFT_SENT'      => $s_gift_sent,
			'S_STATUS'         => $s_status,
			'S_ADDED_BY_HUMAN' => $s_added_by_human,
			'S_MSG_STATUS'     => $s_msg_status,
			'COMMENTS'         => $comments,
		));

		$this->template->assign_vars(array(
			'S_FIRST_DAY_OPTIONS'        => $s_first_day_options, 
			'S_FIRST_MONTH_OPTIONS'      => $s_first_month_options, 
			'S_FIRST_YEAR_OPTIONS'       => $s_first_year_options, 
			'S_LAST_DAY_OPTIONS'         => $s_last_day_options, 
			'S_LAST_MONTH_OPTIONS'       => $s_last_month_options, 
			'S_LAST_YEAR_OPTIONS'        => $s_last_year_options, 
			'S_EXPIRATION_DAY_OPTIONS'   => $s_expiration_day_options, 
			'S_EXPIRATION_MONTH_OPTIONS' => $s_expiration_month_options, 
			'S_EXPIRATION_YEAR_OPTIONS'  => $s_expiration_year_options, 
		));
	
		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
			'U_BACK'	=> $this->u_action,
		));
	}
	
	protected function update_donation($user_id, $first_donation, $last_donation, $expiration, $amount, $method = 'PAYPAL', $added_by_human = 1, $gift_sent = 0, $status = 2, $msg_status = 2, $comments = '')
	{
		$sql = 'INSERT INTO ' . $this->doles_table . '(user_id, first_donation, last_donation, expiration, amount, method, added_by_human, gift_sent, status, msg_status, comments) '
		. 'VALUES (' 
			. (int) $user_id . ', ' 
			. $first_donation->getTimestamp() . ', ' 
			. $last_donation->getTimestamp() . ', ' 
			. $expiration->getTimestamp() . ', ' 
			. (double) $amount . ', ' 
			. "'" . $this->db->sql_escape($method) . "', " 
			. (int) $added_by_human . ', ' 
			. (int) $gift_sent . ', ' 
			. (int) $status . ', '
			. (int) $msg_status . ', '
			. "'" . $this->db->sql_escape($comments) . "'" . ') '
		. 'ON DUPLICATE KEY UPDATE '
			. 'first_donation = ' . $first_donation->getTimestamp() . ', ' 
			. 'last_donation = ' . $last_donation->getTimestamp() . ', ' 
			. 'expiration = ' . $expiration->getTimestamp() . ', ' 
			. 'amount = ' . (double) $amount . ', ' 
			. "method = '" . $this->db->sql_escape($method) . "', " 
			. 'added_by_human = ' . (int) $added_by_human . ', ' 
			. 'gift_sent = ' . (int) $gift_sent . ', ' 
			. 'status = ' .  (int) $status . ', '
			. 'msg_status = ' .  (int) $msg_status . ', '
			. "comments = '" . $this->db->sql_escape($comments) . "';";
		
		$this->db->sql_query($sql);
	}
	
	protected function update_group_members($user_id)
	{
		$status = 0;
		$group_id = $this->config['mfpo_donation_group_id'];
		$donors = (int) $this->config['mfpo_donation_donors'];
		
		$sql = 'SELECT status FROM ' . $this->doles_table . ' WHERE user_id = ' . $user_id;
		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))
		{
			$status = $row['status'];
		}
		$this->db->sql_freeresult($result);
		
		if ($status == 1)
		{
			group_user_add($group_id, $user_id);
			$donors++;
		}
		else
		{
			group_user_del($group_id, $user_id);
			$donors--;
		}
		
		$this->config->set('mfpo_donation_donors', $donors);
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
	
	public function sync_donations()
	{
		$result = $this->do_sync_donations();
		
		$donors = $result['donors'];
		$inactive = $result['inactive'];
		$added = $result['added'];
		$removed = $result['removed'];
		$added_ary = $result['added_ary'];
		$removed_ary = $result['removed_ary'];
		
		foreach ($added_ary as $key => $value) 
		{
			$this->template->assign_block_vars('added', array(
				'USERID'   => $key,
				'USERNAME' => $value,
			));
		}

		foreach ($removed_ary as $key => $value) 
		{
			$this->template->assign_block_vars('removed', array(
				'USERID'   => $key,
				'USERNAME' => $value,
			));
		}
		
		$this->template->assign_vars(array(
			'ACTIVE_DONORS'    => $donors, 
			'INACTIVE_DONORS'  => $inactive, 
			'DONORS_ADDED'     => $added, 
			'DONORS_REMOVED'   => $removed, 
			'S_SYNC'	   => true,
			'U_BACK'	   => $this->u_action
		));
	
	}
	
	public function do_sync_donations()
	{
		$donors = 0;
		$inactive = 0;
		$added = 0;
		$removed = 0;
		
		$now = new DateTime();
		$expiration = new DateTime();
		
		$added_ary = array();
		$removed_ary = array();
		
		$sql = 'SELECT u.user_id as user_id, u.username as username, d.expiration as expiration, d.status as status 
			FROM ' . $this->doles_table . ' d INNER JOIN ' . USERS_TABLE . ' u ON d.user_id = u.user_id;';
			
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$user_id = $row['user_id'];
			$status = $row['status'];
			$username = $row['username'];
			$expiration->setTimestamp($row['expiration']);
			
			if ($expiration->getTimestamp() < $now->getTimestamp())
			{
				if ($status <> 0)
				{
					$status = 0;
					$this->do_deactivate_donation($user_id);
					$removed++;
					$removed_ary[$user_id] = $username;
				}
			}
			else
			{
				if ($status == 0)
				{
					$status = 1;
					$this->do_activate_donation($user_id);
					$added++;
					$added_ary[$user_id] = $username;
				}
			}
			
			if ($status == 1)
			{
				$donors++;
			}
			else
			{
				$inactive++;
			}
		}
		$this->db->sql_freeresult($result);
		
		$this->config->set('mfpo_donation_donors', $donors);

		return array(
			'donors'       => $donors,
			'inactive'     => $inactive,
			'added'        => $added,
			'removed'      => $removed,
			'added_ary'   => $added_ary,
			'removed_ary' => $removed_ary,
		);
	}
	
	public function msg_donors()
	{
		$result = $this->do_msg_donors();
		
		$messages = $result['messages'];
		$msg1_ary = $result['msg1_ary'];
		$msg2_ary = $result['msg2_ary'];
		$msg3_ary = $result['msg3_ary'];
		$msg4_ary = $result['msg4_ary'];
					
		foreach ($msg1_ary as $key => $value) 
		{
			$this->template->assign_block_vars('msg1', array(
				'USERNAME' => $value,
			));
		}

		foreach ($msg2_ary as $key => $value) 
		{
			$this->template->assign_block_vars('msg2', array(
				'USERNAME' => $value,
			));
		}
		
		foreach ($msg3_ary as $key => $value) 
		{
			$this->template->assign_block_vars('msg3', array(
				'USERNAME' => $value,
			));
		}
		foreach ($msg4_ary as $key => $value) 
		{
			$this->template->assign_block_vars('msg4', array(
				'USERNAME' => $value,
			));
		}
		
		$this->template->assign_vars(array(
			'MESSAGES_SENT'    => $messages,
			'S_MSG'  	   => true,
			'U_BACK'	   => $this->u_action
		));
	}
	
	protected function do_msg_donors()
	{
		$messages = 0;
		$first = new DateTime();
		$last = new DateTime();
		$expiration = new DateTime();
		$now = new DateTime();
		
		$msg1_ary = array();
		$msg2_ary = array();
		$msg3_ary = array();
		$msg4_ary = array();
		
		$sql = 'SELECT * FROM ' . $this->config_table;
		$result = $this->db->sql_query($sql);
		$confs = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$confs[$row['config_name']] = $row['config_value'];
		}
		$this->db->sql_freeresult($result);
		
		$sql = 'SELECT  d.user_id as userid, 
				u.username as usn, 
				d.first_donation as frd, 
				d.last_donation as lsd, 	
				d.expiration as exr, 
				d.amount as amn, 
				d.status as sts,
				d.msg_status as msgsts
			FROM ' . $this->doles_table . ' d 
			INNER JOIN ' . USERS_TABLE . ' u  ON d.user_id = u.user_id';
				
		$result = $this->db->sql_query($sql);	
		while ($row = $this->db->sql_fetchrow($result))
		{
		
			$user_id = $row['userid'];
			$usern = utf8_normalize_nfc($row['usn']);
			$first->setTimestamp($row['frd']);
			$last->setTimestamp($row['lsd']);
			$expiration->setTimestamp($row['exr']);
			$amount = $row['amn'];
			$status = $row['sts'];
			$msg_status = $row['msgsts'];
			$days2expire = floor( ( $row['exr'] - time() )/60/60/24 );

			if ($days2expire < 0)
			{
				if ($msg_status < 4)
				{

					$subject_ex = isset($confs['donation_expired_pm_subject']) ? $confs['donation_expired_pm_subject'] : '';
					$text_ex = isset($confs['donation_expired_pm']) ? $confs['donation_expired_pm'] : '';
		
					$text_ex = str_replace('{NEW_AMOUNT}', number_format($this->config['mfpo_donation_amount'], 2, ",", "."), $text_ex);

					$text_ex = str_replace('{USER}', $usern, $text_ex);
					$text_ex = str_replace('{FIRST}', $first->format("d-m-Y"), $text_ex);
					$text_ex = str_replace('{LAST}', $last->format("d-m-Y"), $text_ex);
					$text_ex = str_replace('{EXPIRATION}', $expiration->format("d-m-Y"), $text_ex);
					$text_ex = str_replace('{OLD_AMOUNT}', number_format($amount, 2, ",", "."), $text_ex);
					$text_ex = str_replace('{STATUS}', $status, $text_ex);
					$text_ex = str_replace('{DAYS_TO_EXPIRE}', $days2expire, $text_ex);
				
					$this->send_message($user_id, 2, $subject_ex, $text_ex, 4);
					$messages++;
					
					$msg4_ary[$user_id] = $usern;
				}
			}
			else
			{
				$subject = isset($confs['donation_pm_subject']) ? $confs['donation_pm_subject'] : '';
				$text = isset($confs['donation_pm']) ? $confs['donation_pm'] : '';

				$text = str_replace('{NEW_AMOUNT}', number_format($this->config['mfpo_donation_amount'], 2, ",", "."), $text);

				$text = str_replace('{USER}', $usern, $text);
				$text = str_replace('{FIRST}', date_format($first,"d-m-Y"), $text);
				$text = str_replace('{LAST}', date_format($last,"d-m-Y"), $text);
				$text = str_replace('{EXPIRATION}', date_format($expiration,"d-m-Y"), $text);
				$text = str_replace('{OLD_AMOUNT}', number_format($amount, 2, ",", "."), $text);
				$text = str_replace('{STATUS}', $status, $text);
				$text = str_replace('{DAYS_TO_EXPIRE}', $days2expire, $text);

				if ($days2expire <= 30 && $days2expire > 15)
				{
					if ($msg_status < 1)
					{
						$this->send_message($user_id, 2, $subject, $text, 1);
						$messages++;
						
						$msg1_ary[$user_id] = $usern;
					}
				}
				elseif ($days2expire <= 15 && $days2expire > 1)
				{
					if ($msg_status < 2)
					{
						$this->send_message($user_id, 2, $subject, $text, 2);
						$messages++;
						
						$msg2_ary[$user_id] = $usern;
					}
				}
				elseif ($days2expire <= 1 && $days2expire >= 0)
				{
					if ($msg_status < 3)
					{
						$this->send_message($user_id, 2, $subject, $text, 3);
						$messages++;
						
						$msg3_ary[$user_id] = $usern;
					}
				}
			}
		}
		$this->db->sql_freeresult($result);
		
		return array(
			'messages' => $messages,
			'msg1_ary' => $msg1_ary,
			'msg2_ary' => $msg2_ary,
			'msg3_ary' => $msg3_ary,
			'msg4_ary' => $msg4_ary,
		);
	}

	private function send_message($user_to, $user_id = 2, $subject, $text, $msg_status)
	{
		$user_id = 2;
		
		$subject = utf8_normalize_nfc($subject);
		$text = utf8_normalize_nfc($text);
	
		$m_flags = 3; // 1 is bbcode, 2 is smiles, 4 is urls (add together to turn on more than one)
		$uid = $bitfield = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;

		generate_text_for_storage($text, $uid, $bitfield, $m_flags, $allow_bbcode, $allow_urls, $allow_smilies);

		global $phpbb_root_path, $phpEx;
		include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);

		$pm_data = array(
			'address_list'		=> array('u' => array($user_to => 'to')),
			'from_user_id'		=> $user_id,
			'from_user_ip'		=> $this->user->ip,
			'enable_sig'		=> false,
			'enable_bbcode'		=> $allow_bbcode,
			'enable_smilies'	=> $allow_smilies,
			'enable_urls'		=> $allow_urls,
			'icon_id'		=> 0,
			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,
			'message'		=> $text,
		);

		$this->set_msg_status($user_to, $msg_status);
		submit_pm('post', $subject, $pm_data, false);
	}
	
	public function set_msg_status($user_id, $msg_status = 0)
	{
		$sql = 'UPDATE ' . $this->doles_table . ' SET msg_status = ' . $msg_status . ' WHERE user_id = ' . $user_id;
		$this->db->sql_query($sql);
		
		switch ($msg_status)
		{
			case 0:
				$message = 'DONATION_MSG_0_LOG';
			break; 
			case 1:
				$message = 'DONATION_MSG_1_LOG';
			break; 
			case 2:
				$message = 'DONATION_MSG_2_LOG';
			break; 
			case 3:
				$message = 'DONATION_MSG_3_LOG';
			break; 
			case 4:
				$message = 'DONATION_MSG_4_LOG';
			break; 
		}
	
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $message, time(), array($user_id));
	}
	
}
