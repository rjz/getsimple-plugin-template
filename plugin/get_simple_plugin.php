<?php

if (!class_exists('GetSimplePlugin')):

if (!defined('GS_NULL')) {
	define('GS_NULL', '\0');
}

class GetSimplePlugin {

	protected

		/**
		 *	Plugin description, etc. goes here.
		 *
		 *	The supplied values are used to register the plugin with GetSimple
		 */
		$_info = array(
			'name' => 'My Plugin',
			'version' => '1.0',
			'author' => 'Author name',
			'author_website' => 'http://example.com/', 
			'description' => 'The stuff this plugin does', 
			'page_type' => 'theme',
			'menu_callback' => null
		),

		/**
		 *	Any default settings
		 */
		$_defaults = array(
			// 'foobar' => 'value'		
		),

		/**
		 *	Any hooks that need to be registered.
		 *
		 *	Note that all callbacks must be `public` methods to ensure 
		 *	avoid trouble when called outside of the class
		 */
		$_actions = array(
			// 'hook_name' => 'action_callback_name'
		),

		$_filters = array(
			// 'hook_name' => 'filter_callback_name'
		),
		
		/**
		 *	Any javascripts to be queued up.
		 *
		 *	Format follows: 
		 *
		 *	    'script_name' => GSBACK
		 *
		 *	Where the script_name is the basename of a script located
		 *	in `/plugins/%THISPLUGIN%/%THISPLUGIN_DIR%/admin` and the
		 *	value is one of `GSBACK|GSFRONT|GSBOTH`
		 */
		$_scripts = array(
			// 'admin' => GSBACK,		
			// 'client' => GSFRONT,
			// 'script' => GSBOTH
		),

		$_styles = array(
			// 'admin' => GSBACK,		
			// 'client' => GSFRONT,
			// 'script' => GSBOTH
		);

	/**
	 *	Initialize plugin
	 */
	public function __construct () {
	
		$this->_restore();
		$this->_register();
		$this->_init();
	}

	/**
	*	Get or set a plugin setting
	*
	*	If an `array` is passed for the `$key` parameter, all
	*	key-value pairs included in the array that are included
	*	in the `$_defaults` whitelist will be assigned.
	*
	*	@param	mixed	$key to retrieve or set 
	*	@param	string	(optional) $value to assign
	*	@return	string	value assigned to $key
	*/
	public function setting ($key, $value = GS_NULL) {

		if (is_array($key)) {

			$settings = $key;
			$whitelist = array_keys($this->_defaults);

			foreach ($whitelist as $key) {
				if (array_key_exists($key, $settings)) {
				$this->_settings[$key] = $settings[$key];
				}
			}

			$this->_save();

		} else if ($value != GS_NULL) {

			// setter
			$this->_settings[$key] = $value;
			$this->_save();
			return $value;

		} else {

			// getter
			if (isset($this->_settings[$key])) {
				return $this->_settings[$key];
			} elseif (isset($this->_defaults[$key])) {
				return $this->_defaults[$key];
			}
		}

		return NULL;
	}

	/**
	 *	Return an underscorized version of the plugin id
	 *	@return	string
	 */
	protected function _fs_name () {
		$dirname = strtolower($this->_info['id']);
		$dirname = preg_replace('#[^\w\d]+#', '_', $dirname);

		return $dirname;
	}

	/**
	 *	The path to this plugin's data directory
	 *	@param	string	an optional string to append to the path
	 *	@return string
	 */
	protected function _data_dir ($append = '') {
		return GSDATAOTHERPATH . '/' . $this->_fs_name() . '/' . $append;
	}

	/**
	 *	The path to this plugin's plugin directory
	 *	@param	string	an optional string to append to the path
	 *	@return string
	 */
	protected function _plugin_dir ($append = '') {
		return GSPLUGINPATH . '/' . $this->_fs_name() . '/' . $append;
	}

	/**
	 *	The relative URL of this plugin's plugin directory
	 *	@param	string	an optional string to append to the url
	 *	@return string
	 */
	protected function _plugin_url ($append = '') {
		global $SITEURL;
		return $SITEURL . '/plugins/' . $this->_fs_name() . '/' . $append;
	}

	/**
	 *	Show a view
	 *
	 *	@see https://github.com/rjz/wp-plugin-demos/blob/master/basic-plugin/includes/MY_Plugin.php#L81 
	 *
	 *	@param	string	the name of the view
	 *	@param	array	(optional) variables to pass to the view
	 *	@param	boolean	echo the view? (default: true)
	 */
	protected function _load_view( $view, $data = null, $echo = true ) {

		$view = $view . '.php';
		$viewfile = $this->_plugin_dir() . '/views/' . $view;

		if (!file_exists($viewfile)) {
			echo "couldn't load view: '$view'";
		}

		if (!is_array($data)) {
			$data = array();
		}

		// merge in plugin data
		$data['settings'] = $this->_settings;
		$data['info'] = $this->_info;

		foreach($data as $key => $value) {
			${$key} = $value;
		}

		ob_start();
			include $viewfile;
			$result = ob_get_contents();
		ob_end_clean();

		if ($echo) {
			echo $result;
		} else {
			return $result;
		}
	}

	protected function _settings_file () {
		return $this->_data_dir() . '/settings.json';
	}

	/**
	 *	Called if the plugin has not been installed previously
	 */
	protected function _install () {

		// create data directory
		if (!file_exists($this->_data_dir())) {
			if (mkdir($this->_data_dir()) === false) { 
				die($this->_info['name'] . ' failed creating data directory');
			}
		}

		// create default settings
		$this->_settings = array_merge($this->_defaults, array(
			'version' => $this->_info['version']
		));

		// copy settings to settings file
		if ($this->_save() === false) {
			die($this->_info['name'] . ' failed creating settings file');
		}
	}

	/**
	 *	Reload settings from settings file or install if none exist
	 */
	protected function _restore () {

		// create data directory
		if (file_exists($this->_settings_file())) {
			
			// restore settings
			$settings = json_decode(file_get_contents($this->_settings_file()));
			$this->_settings = array_merge($this->_defaults, get_object_vars($settings));

		} else {
			// install default settings
			$this->_install();
		}
	}

	/**
	 *	Save current settings
	 *
	 *	Provided settings are compared to the whitelisted keys supplied
	 *	in {@see GetSimpleWidget::_defaults}
	 *
	 *	@param	array	(optional) settings to merge before saving
	 *	@return boolean
	 */
	protected function _save ($settings = null) {

		if (is_array($settings)) {
			$whitelist = array_keys($this->_defaults);
			foreach ($whitelist as $key) {
				if (array_key_exists($key, $settings)) {
					$this->_settings[$key] = $settings[$key];
				}
			}
		}

		return file_put_contents($this->_settings_file(), json_encode($this->_settings));
	}

	/**
	 *	Register plugin, hooks, and filters with getSimple
	 */
	protected function _register () {

		$plugininfo = array(
			$this->_info['id'],
			$this->_info['name'],
			$this->_info['version'],
			$this->_info['author'],
			$this->_info['author_website'],
			$this->_info['description'], 
			$this->_info['page_type']
		);

		if (isset($this->_info['menu_callback'])) {
			$plugininfo[] = array($this, $this->_info['menu_callback']);
		}

		// register plugin
		call_user_func_array('register_plugin', $plugininfo);

		// register actions
		foreach ($this->_actions as $hook => $action) {
			add_action($hook, array($this, $action));
		}
		
		// register filters
		foreach ($this->_filters as $hook => $filter) {
			add_filter($hook, array($this, $filter));
		}

		// register scripts
		foreach ($this->_scripts as $name => $queue_region) {
			$script_name = $this->_info['id'] . '_' . $name . '_script';
			register_script($script_name, $this->_plugin_url('js/' . $name . '.js'), $this->_info['version'], FALSE);
			queue_script($script_name, $queue_region);
		}

		// register styles 
		foreach ($this->_styles as $name => $queue_region) {
			$style_name = $this->_info['id'] . '_' . $name . '_style';
			register_style($style_name, $this->_plugin_url('css/' . $name . '.css'), $this->_info['version'], FALSE);
			queue_style($style_name, $queue_region);
		}

	}

	/**
	 *	Called to initialize plugin
	 */
	protected function _init () { /* Implement me. */ }
}

endif; // GetSimplePlugin
