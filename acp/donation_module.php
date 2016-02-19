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

namespace marcosfpo\donation\acp;

class donation_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $request, $user;

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('marcosfpo.donation.admin.controller');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);
		
		// Requests
		$action = $request->variable('action', '');
		$donation_id = $request->variable('user_id', 0);
			
		// Add the donations lang file
		$user->add_lang_ext('marcosfpo/donation', 'common');
		
		$this->page_title = $user->lang('ACP_DONATIONS_MANAGE_DONATIONS');

		// Load the "settings" or "manage" module modes
		switch($mode)
		{
			case 'configuration':
				$this->tpl_name = 'acp_donation';
				// Set the page title for our ACP page
				$this->page_title = $user->lang['ACP_DONATION_MOD'];
				
				// Load the display options handle in the admin controller
				$admin_controller->display_config();
				break;
			case 'donations':
				$this->tpl_name = 'acp_donations';
				// Set the page title for our ACP page
				$this->page_title = $user->lang['ACP_DONATION_MOD'];
				
				switch ($action)
				{
					case 'add':
						$this->page_title = $user->lang('ACP_DONATIONS_ADD_DONATION');
						$admin_controller->add_donation();
						return;
					break;

					case 'edit':
						$this->page_title = $user->lang('ACP_DONATIONS_EDIT_DONATION');
						$admin_controller->edit_donation($donation_id);
						return;
					break;
		
					case 'sync':
						if (confirm_box(true))
						{
							$this->page_title = $user->lang('ACP_DONATIONS_SYNC_DONATIONS');
							$admin_controller->sync_donations();
							return;
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_SYNC_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;

					case 'msg':
						if (confirm_box(true))
						{
							$this->page_title = $user->lang('ACP_DONATIONS_MSG_DONORS');
							$admin_controller->msg_donors();
							return;
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_MSG_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;

					case 'delete':
						if (confirm_box(true))
						{
							$admin_controller->delete_donation($donation_id);
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_DELETE_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;
				
					case 'activate':
						if (confirm_box(true))
						{
							$admin_controller->activate_donation($donation_id);
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_ACTIVATE_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;
			
					case 'deactivate':
						if (confirm_box(true))
						{
							$admin_controller->deactivate_donation($donation_id);
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_DEACTIVATE_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;
			
					case 'send_gift':
						if (confirm_box(true))
						{
							$admin_controller->send_gift_donation($donation_id);
						}
						else
						{
							confirm_box(false, $user->lang('ACP_DONATION_SEND_GIFT_CONFIRM'), build_hidden_fields(array(
								'donation_id'	=> $donation_id,
								'mode'		=> $mode,
								'action'	=> $action,
							)));
						}
					break;
				}
								
				$admin_controller->display_donations();
				
			break;
		}
	}
}