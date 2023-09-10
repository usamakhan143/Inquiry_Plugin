<?php
/**
* Plugin Name: Contact Plugin
* Description: This is my first plugin development Enviroment Setup
* Version: 1.0.0
* Text Domain: translate-contact-plugin
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if(!class_exists('ContactPlugin')) {


	class ContactPlugin {

		public function __construct()
		{
			define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
		}

	}

}
else {
	die('This class is already exist!');
}