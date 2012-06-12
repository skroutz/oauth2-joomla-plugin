<?php
/**
 * @package    SkroutzEasy plugin for Joomla 1.5.x and 1.6.x
 * @copyright  Copyright (c) 2012 Skroutz S.A. - http://www.skroutz.gr
 * @link       http://developers.skroutz.gr/oauth2
 * @license    MIT
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.event.plugin');

/**
 * SkroutzEasy Authentication Plugin
 */
class plgAuthenticationSkroutzEasy extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 */
	function plgAuthenticationSkroutzEasy(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access  public
	 * @param   array   $credentials    Array holding the user credentials
	 * @param   array   $options        Array of extra options
	 * @param   object  $response       Authentication response object
	 * @return  boolean
	 */
	function onAuthenticate( $credentials, $options, &$response )
	{
		// Get a database object
		$db =& JFactory::getDBO();

		$query = 'SELECT `id`, `password`, `gid`'
			. ' FROM `#__users`'
			. ' WHERE username=' . $db->Quote( $credentials['username'] )
			;
		$db->setQuery( $query );
		$result = $db->loadObject();
                
		if($result && $options['oauth_login']) {
			$user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
			$response->email = $user->email;
			$response->fullname = $user->name;
			$response->status = JAUTHENTICATE_STATUS_SUCCESS;
			$response->error_message = '';
		} else {
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
			$response->error_message = 'User does not exist';
		}
	}
}
