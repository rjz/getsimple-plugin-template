<?php

require_once('test_helper.php');
require_once('get_simple_plugin.php');

require_once('../plugin.php');

class PluginTest extends GSTestCase {

	public function setUp () {
		$this->model = new Plugin();
	}

	public function test_defaults () {
		$this->assertEquals(2+2, 4);
	}
}
