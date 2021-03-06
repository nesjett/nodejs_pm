<?php
/**
 * @file
 * Class installation to define shortcodes.
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class nodejs_pm_shortcodes.
 */
class nodejs_pm_shortcodes extends e_shortcode
{

	private $plugPrefs = array();


	function __construct()
	{
		parent::__construct();
		$this->plugPrefs = e107::getPlugConfig('nodejs_pm')->getPref();
	}


	function sc_avatar()
	{
		$tp = e107::getParser();
		$tp->thumbWidth = 50;
		$tp->thumbHeight = 50;
		return $tp->toAvatar($this->var['account']);
	}


	function sc_username()
	{
		$uparams = array(
			'id'   => $this->var['account']['user_id'],
			'name' => $this->var['account']['user_name'],
		);
		return '<a href="' . e107::getUrl()->create('user/profile/view', $uparams) . '">' . $uparams['name'] . '</a>';
	}


	function sc_links()
	{
		return '<a href="' . e_PLUGIN_ABS . 'pm/pm.php?show.' . $this->var['pm']['pm_id'] . '">' . LAN_NODEJS_PM_MENU_02 . '</a>';
	}


	function sc_message()
	{
		$tp = e107::getParser();
		$message = strip_tags($this->var['pm']['pm_text']);
		return $tp->text_truncate($message, $this->plugPrefs['nodejs_pm_length']);
	}


	function sc_message_read()
	{
		$name = '<strong>' . $this->var['account']['user_name'] . '</strong>';
		return str_replace('[x]', $name, LAN_NODEJS_PM_MENU_06);
	}


	function sc_newcount()
	{
		$count = (int) $this->var['new'] > 0 ? $this->var['new'] : '';
		return $count;
	}


	function sc_header()
	{
		return LAN_NODEJS_PM_MENU_01;
	}


	function sc_inbox()
	{
		return '<a href="' . e_PLUGIN_ABS . 'pm/pm.php?inbox"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> ' . LAN_NODEJS_PM_MENU_03 . '</a>';
	}


	function sc_outbox()
	{
		return '<a href="' . e_PLUGIN_ABS . 'pm/pm.php?outbox"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> ' . LAN_NODEJS_PM_MENU_04 . '</a>';
	}


	function sc_compose()
	{
		return '<a href="' . e_PLUGIN_ABS . 'pm/pm.php?send"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> ' . LAN_NODEJS_PM_MENU_05 . '</a>';
	}
}
