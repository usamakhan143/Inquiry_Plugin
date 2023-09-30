<?php

/**
 * Plugin Name: Contact Plugin
 * Description: This is my first plugin development Enviroment Setup
 * Version: 1.0.0
 * Text Domain: translate-contact-plugin
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


if (!class_exists('ContactPlugin')) {


	class ContactPlugin
	{

		public function __construct()
		{
			// Define a constant to initialize the plugin path.
			define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

			// Define a constant to initialize the frontend plugin path.
			define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));

			// Call the packages that you are using in the plugin to enhance the functionality.
			require_once(MY_PLUGIN_PATH . '/vendor/autoload.php');
		}

		public function initialize()
		{
			include_once(MY_PLUGIN_PATH . '/includes/utilities.php');
			include_once(MY_PLUGIN_PATH . '/includes/option-page.php');
			include_once(MY_PLUGIN_PATH . '/includes/contact-form.php');
		}
	}
} else {
	die('This class is already exist!');
}

$ContactPlugin = new ContactPlugin();
$ContactPlugin->initialize();
