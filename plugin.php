<?php
/*
Plugin Name: My Plugin
Description: A Plugin based on the GetSimple Plugin Template
Version: 1.0
Author: RJ Zaworski <rj@rjzaworski.com>
Author URI: http://rjzaworski.com/
*/

if (!class_exists('Plugin')):

require_once('plugin/get_simple_plugin.php');

class Plugin extends GetSimplePlugin {

	protected
		
		/**
		 *	The very simplest plugins will still need to specify 
		 *	a few fields in the `$_info` array.
		 */
		$_defaults = array(
			'email' => 'mailbag@example.com'
		),

		$_actions = array(
			'theme-sidebar' => 'admin_menu'
		),

		$_filters = array(
			'content' => 'filter_content'
		);

	/**
	 *	Assign the correct plugin ID and call the parent constructor
	 *	@constructor
	 */
	public function __construct () {

		// configure this plugin
		$this->_info = array(
			'id' =>             basename(__FILE__, '.php'),
			'name' =>           'Contact Form',
			'version' =>        '1.0',
			'author' =>         'RJ Zaworski <rj@rjzaworski.com>',
			'author_website' => 'http://rjzaworski.com/', 
			'description' =>    'A Contact Link',
			'page_type' =>      'theme',
			'menu_callback' =>  'admin_view'
		);

		// initiate the plugin
		parent::__construct();
	}

	/**
	 *	Callback attached to `theme-sidebar`: create a menu entry
	 *	@callback
	 */
	public function admin_menu () {
		createSideMenu($this->_info['id'], 'Contact Link');
	}

	/**
	 *	Callback attached in `admin_menu`: admin settings
	 *	@callback
	 */
	public function admin_view () {

		$data = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$action = $_POST['_action'];
			$data['action'] = $action;
			
			// Obligatory CSRF check
			if (check_nonce($_POST['_nonce'], $action, $this->_info['id'])) {

				// Take action!
				switch ($action) {
				case 'save':
					if ($this->_save($_POST)) {
						$data['updated'] = 'Settings saved';
					} else {
						$data['error'] = 'There was a problem saving data';
					}
					break;
				}
			} elseif ($action) {
				// Failed CSRF test
				$data['error'] = 'Request timed out';
			}
		}

		$this->_load_view('admin', $data);
	}

	/**
	 *	Callback attached to `content` filter: replace contact tag with content 
	 *	@param	string	$content to filter
	 *	@param	string	filtered content
	 */
	public function filter_content ($content) {

		$key = '(% contact %)';

		if (strpos($content, $key) === FALSE) {
			return $content;
		}

		$email = $this->_settings['email'];
		$mailto = "<a href=\"mailto:$email\">$email</a>";

		return str_replace($content, $key, $contact_form);
	}
}

new Plugin();

endif;
