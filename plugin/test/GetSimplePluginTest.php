<?php

require_once('test_helper.php');
require_once('get_simple_plugin.php');

class GetSimpleChildPlugin extends GetSimplePlugin {

	protected

		$_defaults = array(),

		$_info = array(
			'id' => 'foobar',
			'name' => 'My Plugin',
			'version' => '1.0',
			'author' => 'Author name',
			'author_website' => 'http://example.com/', 
			'description' => 'The stuff this plugin does', 
			'page_type' => 'theme'
		),

		$_settings = array();
}

class GetSimplePluginTest extends GSTestCase {

	public function setUp () {
		$this->model = new GetSimpleChildPlugin();
	}

	public function test_settings () {

		$key = 'foo';
		$val = 'bar';

		$this->model->setting($key, $val);
		$this->assertEquals($val, $this->model->setting($key));
		
		$this->model->setting('foo', NULL);
		$this->assertEquals(NULL, $this->model->setting($key));
	}

	public function test_settings_array () {

		$key = 'foo';
		$val = 'bar';

		$data = array();
		$data[$key] = '';

		$this->model->setting($data);
		$this->assertEquals(NULL, $this->model->setting($key));
		
		$this->set_protected('_defaults', $data);
		$data[$key] = $val;
		$this->model->setting($data);
		$this->assertEquals($val, $this->model->setting($key));
	}

	public function test_restore () {

		$key = 'foo';
		$val = 'bar';

		$data = array();
		$data[$key] = '';

		$this->set_protected('_defaults', $data);

		$data[$key] = $val;
		$this->call_protected('_save', $data);

		$data[$key] = '';
		$this->set_protected('_settings', $data);
		$this->call_protected('_restore');

		$this->assertEquals($val, $this->model->setting($key));
	}
}

