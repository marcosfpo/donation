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

namespace marcosfpo\donation\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected $doles_table;


	/**
	* Constructor
	*
	* @param \phpbb\config\config				$config
	* @param \phpbb\controller\helper			$helper
	* @param \phpbb\template\template			$template
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $controller_helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $doles_table)
	{
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->doles_table = $doles_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewonline_overwrite_location'	=> 'add_page_viewonline',
			'core.page_header'			=> 'add_page_header_links',
			'core.user_setup'			=> 'load_language_on_setup',
			'core.viewtopic_modify_post_row' 	=> 'viewtopic_modify_post_row',
			'core.memberlist_view_profile' 		=> 'memberlist_view_profile',
			'core.index_modify_page_title'		=> 'index_modify_page_title',
		);
	}

	public function add_page_viewonline($event)
	{
		global $user, $phpbb_container, $phpEx;
		if (strrpos($event['row']['session_page'], 'app.' . $phpEx . '/donation') === 0)
		{
			$event['location'] = $user->lang('VIEWING_DONATE');
			$event['location_url'] = $phpbb_container->get('controller.helper')->route('marcosfpo_donation_controller');
		}
	}

	public function add_page_header_links($event)
	{
		$this->template->assign_vars(array(
			'DONATION_ACHIEVEMENT_ENABLE'		=> (isset($this->config['mfpo_donation_achievement_enable'])) ? $this->config['mfpo_donation_achievement_enable']:false,
			'DONATION_ACHIEVEMENT'			=> (isset($this->config['mfpo_donation_achievement'])) ? $this->config['mfpo_donation_achievement']:false,
			'DONATION_INDEX_ENABLE'			=> (isset($this->config['mfpo_donation_index_enable'])) ? $this->config['mfpo_donation_index_enable']:false,
			'DONATION_INDEX_TOP'			=> (isset($this->config['mfpo_donation_index_top'])) ? $this->config['mfpo_donation_index_top']:false,
			'DONATION_INDEX_BOTTOM'			=> (isset($this->config['mfpo_donation_index_bottom'])) ? $this->config['mfpo_donation_index_bottom']:false,
			'DONATION_GOAL_ENABLE'			=> (isset($this->config['mfpo_donation_goal_enable'])) ? $this->config['mfpo_donation_goal_enable']:false,
			'DONATION_GOAL'				=> (isset($this->config['mfpo_donation_goal'])) ? $this->config['mfpo_donation_goal']:false,
			'DONATION_GOAL_CURRENCY_ENABLE'		=> (isset($this->config['mfpo_donation_goal_currency_enable'])) ? $this->config['mfpo_donation_goal_currency_enable']:false,
			'DONATION_GOAL_CURRENCY'		=> (isset($this->config['mfpo_donation_goal_currency'])) ? $this->config['mfpo_donation_goal_currency']:false,
			'S_DONATE_ENABLED'			=> (isset($this->config['mfpo_donation_enable'])) ? $this->config['mfpo_donation_enable']:false,
		));

		if (!empty($this->config['mfpo_donation_goal_enable']) && $this->config['mfpo_donation_goal'] > 0)
		{
			$donation_goal_number = ($this->config['mfpo_donation_achievement'] * 100) / $this->config['mfpo_donation_goal'];
			$donation_goal_rest = $this->config['mfpo_donation_goal'] - $this->config['mfpo_donation_achievement'];
			$this->template->assign_vars(array(
				'DONATION_GOAL_NUMBER'		=> round($donation_goal_number),
				'DONATION_GOAL_REST'		=> $donation_goal_rest,
			));
		}
		$this->template->assign_vars(array(
			'U_DONATE' => $this->controller_helper->route('marcosfpo_donation_controller'),
		));
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'marcosfpo/donation',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
	
	public function viewtopic_modify_post_row($event)
     	{
        	$row = $event['row'];
        	$user_id = $row['user_id'];
        	$current_user_id = $this->user->data['user_id'];
        	
        	if ($user_id == $current_user_id) 
        	{
		       	$user_date_format = $user->data['user_dateformat'];
			$user_date_format_wo_time = substr($user_date_format, ( strpos($user_date_format, '|') ), ( strrpos($user_date_format, '|') - strpos($user_date_format, '|') + 1 ));

	        	$user_date_format = $this->user->data['user_dateformat'];
        		$sql = 'SELECT user_id, expiration, first_donation, status FROM ' . $this->doles_table . ' WHERE user_id = ' . $user_id;
        		$result = $this->db->sql_query($sql);
			if ($row = $this->db->sql_fetchrow($result))
			{
				$expiration = $row['expiration'];
				$first = $row['first_donation'];
				
				$event['post_row'] = array_merge($event['post_row'], array(
					'S_DAYS_TO_EXPIRE'	=> floor( ($expiration - time())/60/60/24 ),
					'EXPIRATION' 		=> $this->user->format_date($expiration, $user_date_format_wo_time),
				));
			}
			$this->db->sql_freeresult($result);
        	}	
    	}


	public function memberlist_view_profile($event)
    	{
		$data = $event['member'];
		$user_id = $data['user_id'];
		$current_user_id = $this->user->data['user_id'];
        	
        	if ($user_id == $current_user_id) 
        	{
		       	$user_date_format = $user->data['user_dateformat'];
			$user_date_format_wo_time = substr($user_date_format, ( strpos($user_date_format, '|') ), ( strrpos($user_date_format, '|') - strpos($user_date_format, '|') + 1 ));

       			$sql = 'SELECT user_id, expiration, first_donation, last_donation, amount, status FROM ' . $this->doles_table . ' WHERE user_id = ' . $user_id;
	       		$result = $this->db->sql_query($sql);
			if ($row = $this->db->sql_fetchrow($result))
			{
				$donor_expiration = $row['expiration'];
				$donor_first = $row['first_donation'];
				$donor_last = $row['last_donation'];
				$donor_status = $row['status'];
				$donor_amount = $row['amount'];

				$this->template->assign_vars(array(	
					'S_DAYS_TO_EXPIRE' 	=> floor( ($donor_expiration - time())/60/60/24 ),
					'EXPIRATION'		=> $this->user->format_date($donor_expiration, $user_date_format_wo_time),
					'LAST'			=> $this->user->format_date($donor_last, $user_date_format_wo_time),
					'AMOUNT'		=> number_format($donor_amount, 2, ",", "."),
					'STATUS'		=> $donor_status,
				));
			}
			$this->db->sql_freeresult($result);
		}
    	}
    	
    	public function index_modify_page_title($event)
    	{
    		$sql = 'SELECT u.username as usern, d.last_donation as lst 
    		FROM ' . $this->doles_table . ' d 
    		INNER JOIN ' . USERS_TABLE . ' u 
    			ON d.user_id = u.user_id 
    			WHERE d.last_donation = (
    				SELECT MAX(last_donation) FROM '. $this->doles_table . '
    				);';
    				
    		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))
		{
		       	$user_date_format = $this->user->data['user_dateformat'];
			$user_date_format_wo_time = substr($user_date_format, ( strpos($user_date_format, '|') ), ( strrpos($user_date_format, '|') - strpos($user_date_format, '|') + 1 ));

			$username = $row['usern'];
			$last= $row['lst'];
			
	    		$this->template->assign_vars(array(	
				'S_DONATE_ENABLED' => (isset($this->config['mfpo_donation_enable'])) ? $this->config['mfpo_donation_enable'] : false,
				'TOTAL_DONORS'	   => (isset($this->config['mfpo_donation_donors'])) ? $this->config['mfpo_donation_donors'] : 0,
				'LAST_DONOR'	   => $username,
				'LAST'		   => $this->user->format_date($last, $user_date_format_wo_time),
    			));
		}
		$this->db->sql_freeresult($result);    		
    	}
}