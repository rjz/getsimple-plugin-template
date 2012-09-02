<?php

$tmp = tempnam(sys_get_temp_dir(), 'getsimple-test');

if (!defined('GSBACK'))
	define('GSBACK', 0x0123);

if (!defined('GSDATAOTHERPATH'))
	define('GSDATAOTHERPATH', sys_get_temp_dir());

if (!defined('GSROOTPATH'))
	define('GSROOTPATH', sys_get_temp_dir());

global $GSADMIN;
	$GSADMIN = 'admin';


class GSStub {

	protected $_called = array();

	protected function _get_calls_by_name ($name) {
		return array_filter($this->_called, function ($meth) { 
			return $meth['name'] == $name; 
		});
	}

	public function called ($name) {
		return count($this->_get_calls_by_name($name)) > 0;
	}

	public function report ($name, $arguments) {
		$this->_called[] = array(
			'name' => $name,
			'arguments' => $arguments
		);
	}
}

global $GSStub;
$GSStub = new GSStub();

function add_action ()      { global $GSStub; $GSStub->report('add_action', func_get_args()); }
function add_filter ()      { global $GSStub; $GSStub->report('add_filter', func_get_args());}
function queue_script ()    { global $GSStub; $GSStub->report('queue_script', func_get_args());}
function queue_style ()     { global $GSStub; $GSStub->report('queue_style', func_get_args());}
function register_plugin () { global $GSStub; $GSStub->report('register_plugin', func_get_args());}
function register_script () { global $GSStub; $GSStub->report('register_script', func_get_args());}
function register_style ()  { global $GSStub; $GSStub->report('register_style', func_get_args());}

require_once('test_case.php');

/**
 *	Define any additional global includes or helpers here...
 */
