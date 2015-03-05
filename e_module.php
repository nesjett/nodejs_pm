<?php
/**
 * @file
 * This file is loaded every time the core of e107 is included. ie. Wherever
 * you see require_once("class2.php") in a script. It allows a developer to
 * modify or define constants, parameters etc. which should be loaded prior to
 * the header or anything that is sent to the browser as output. It may also be
 * included in Ajax calls.
 */

e107::lan('nodejs_pm', false, true);

// Register events.
$event = e107::getEvent();
$event->register('user_pm_sent', 'nodejs_pm_event_user_pm_sent_callback');

/**
 * Event callback after triggering "user_pm_sent".
 *
 * @param array $info
 *  Details about private message.
 */
function nodejs_pm_event_user_pm_sent_callback($info)
{
	$to = (int) $info['pm_to'];

	if($to > 0)
	{
		e107_require_once(e_PLUGIN . 'nodejs/nodejs.main.php');
		$plugPrefs = e107::getPlugConfig('nodejs_pm')->getPref();

		// If show alert messages.
		if((int) $plugPrefs['nodejs_pm_alert'] === 1)
		{
			$template = e107::getTemplate('nodejs_pm');
			$sc = e107::getScBatch('nodejs_pm', true);
			$tp = e107::getParser();

			$sc_vars = array(
				'account' => e107::user($to),
				'pm'      => $info,
			);

			$sc->setVars($sc_vars);
			$markup = $tp->parseTemplate($template['ALERT'], true, $sc);

			$message = (object) array(
				'channel'  => 'nodejs_user_' . $to,
				'callback' => 'pmNodejsAlert',
				'type' => 'pmNodejsAlert',
				'markup'   => $markup,
			);
			nodejs_enqueue_message($message);
		}

		// Count unread messages of targeted user.
		$db = e107::getDb();
		$new = $db->count('private_msg', '(*)', "WHERE pm_read = 0 AND pm_to = '" . $to . "' AND pm_read_del != 1");
		if($new)
		{
			// Push the number of unread messages to client.
			$message = (object) array(
				'channel'  => 'nodejs_user_' . $to,
				'callback' => 'pmNodejsMenu',
				'type' => 'pmNodejsMenu',
				'data'     => $new,
			);
			nodejs_enqueue_message($message);
		}
	}
}