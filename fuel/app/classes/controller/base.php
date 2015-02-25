<?php

abstract class Controller_Base extends Controller_Rest {
	protected $result = array();
	
	protected $_supported_formats = array(
		'xml' => 'application/xml',
		// 'rawxml' => 'application/xml',
		'json' => 'application/json',
		// 'jsonp'=> 'text/javascript',
		// 'serialized' => 'application/vnd.php.serialized',
		// 'php' => 'text/plain',
		// 'html' => 'text/html',
		// 'csv' => 'application/csv',
	);

	public function before() {
		parent::before();
		
		$this->_check_extension(Input::extension());
	}

	private function _check_extension($extension) {
		foreach (array_keys($this->_supported_formats) as $key) {
			if ($extension == $key) {
				return;
			}
		}
		throw new HttpNotFoundException();
	}
}