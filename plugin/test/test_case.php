<?php

/**
 *	Test case extension uses reflection to expose protected 
 *	methods and parameters
 *
 *	@see http://blog.rjzaworski.com/?p=962
 */
class GSTestCase extends PHPUnit_Framework_TestCase {

	protected function call_protected ($method, $args = array()) {
		$method = new ReflectionMethod(get_class($this->model), $method);
		$method->setAccessible(true);
		return $method->invokeArgs($this->model, $args);
	}

	protected function set_protected ($key, $value) {
		$property = new ReflectionProperty(get_class($this->model), $key);
		$property->setAccessible(true);
		return $property->setValue($this->model, $value);
	}

	protected function get_protected ($key) {
		$property = new ReflectionProperty(get_class($this->model), $key);
		$property->setAccessible(true);
		return $property->getValue($this->model);
	}

}
