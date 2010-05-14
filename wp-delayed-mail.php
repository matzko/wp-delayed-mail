<?php
/*
Plugin Name: Delayed Email
Plugin URI: http://austinmatzko.com/wordpress-plugins/wp-delayed-mail/
Description: Delay the sending of an email. <code>wp_delayed_mail</code> is the same as <code>wp_mail</code> except that the first argument passed to <code>wp_delayed_mail</code> is the UNIX epoch timestamp at which to send the email.
Author: Austin Matzko
Author URI: http://ilfilosofo.com
Version: 1.0

Copyright 2010 Austin Matzko

*/

class Filosofo_WP_Delayed_Email_Factory {

	public function __construct()
	{
		add_action('delayed_mail_carrier', array(&$this, 'event_delayed_mail_carrier'));
	}

	/**
	 * Create a custom object to hold the mail information.
	 *  
	 * @param array $mail_args The arguments to pass to wp_mail
	 * @return int The ID of the object.
	 */
	private function _create_scheduled_object($mail_args = array())
	{
		$data = array(
			'post_content' => 'This is a dummy content for a mail parcel object.  Please ignore.',
			'post_type' => '_delayed_mail_parcel',
			'post_status' => 'draft',
		);

		$object_id = (int) wp_insert_post($data);

		if ( ! empty( $object_id ) ) {
			update_post_meta($object_id, '_delayed_mail_data', $mail_args);
		}

		return $object_id;
	}

	/**
	 * Get the arguments for the scheduled email.
	 *
	 * @param int The ID of the object.
	 * @return array The data to pass as arguments to wp_mail
	 */
	private function _get_scheduled_arguments($object_id = 0)
	{
		$object_id = (int) $object_id;
		$return = get_post_meta($object_id, '_delayed_mail_data', true);

		if ( is_array($return) ) {
			return $return;
		} else {
			return array();
		}
	}

	public function event_delayed_mail_carrier($parcel_id = 0)
	{
		$parcel_id = (int) $parcel_id;
		$args = get_post_meta($parcel_id, '_delayed_mail_data', true);
		$_obj = get_post($parcel_id);
		if ( 
			is_array($args) &&
			isset($_obj->post_type) &&
			'_delayed_mail_parcel' == $_obj->post_type
		) {
			call_user_func_array('wp_mail', $args);
			wp_delete_post($parcel_id, true);
		}
	}

	public function schedule_mail()
	{
		$args = func_get_args();
		if ( ! is_array($args) ) {
			return false;
		}

		$time = array_shift($args);

		$obj_id = $this->_create_scheduled_object($args);

		if ( 0 < $obj_id && $time > time() ) {
			wp_schedule_single_event( $time, 'delayed_mail_carrier', array($obj_id));
		}
	}
}

function init_filosofo_wp_delayed_email()
{
	global $filosofo_wp_delayed_email;
	$filosofo_wp_delayed_email = new Filosofo_WP_Delayed_Email_Factory; 
}

function wp_delayed_mail()
{
	global $filosofo_wp_delayed_email;
	$args = func_get_args();
	call_user_func_array(array(&$filosofo_wp_delayed_email, 'schedule_mail'), $args);
}

add_action('plugins_loaded', 'init_filosofo_wp_delayed_email');
