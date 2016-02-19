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

namespace marcosfpo\donation\cron;

/**
 * Donation cron task.
 */
class donations_check extends \phpbb\cron\task\base
{
	protected $config;
	protected $log;
	protected $controller;

	public function __construct(\phpbb\config\config $config, \phpbb\log\log_interface $log, \marcosfpo\donation\controller\admin_controller $controller)
	{
		$this->config = $config;
		$this->log = $log;
		$this->controller = $controller;
	}
	
	protected function do_donations_check()
	{
		$begin = time();
		
		$syncary = $this->controller->do_sync_donations();
		$msgary = $this->controller->do_msg_donors();
		
		$donors = $syncary['donors'];
		$inactive = $syncary['inactive'];
		$added = $syncary['added'];
		$removed = $syncary['removed'];
		$added_list = implode(", ", $syncary['added_ary']);
		$removed_list = implode(", ", $syncary['removed_ary']);
		$messages = $msgary['messages'];
		$msg1_list = implode(", ", $msgary['msg1_ary']);
		$msg2_list = implode(", ", $msgary['msg2_ary']);
		$msg3_list = implode(", ", $msgary['msg3_ary']);
		$msg4_list = implode(", ", $msgary['msg4_ary']);
		
		$end = time();
		
		$duration = $end - $begin;
		
		$logary = array(
			$donors,
			$inactive,
			$added,
			$removed,
			$added_list,
			$removed_list,
			$messages,
			$msg1_list,
			$msg2_list,
			$msg3_list,
			$msg4_list,
			date_format($duration, "H:i:s"),
		);
			
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'DONATION_CRON_LOG', time(), $logary);
	}
	
	/**
	Runs this cron task.
	*/
	public function run()
	{
		$this->do_donations_check();
		$this->config->set('donations_check_last_gc', time());
	}

	/**
	Returns whether this cron task should run now, because enough time has passed since it was last run.
	*/	
	public function should_run() 
	{
		return $this->config['donations_check_last_gc'] < time() - $this->config['donations_check_gc'];
	}

}