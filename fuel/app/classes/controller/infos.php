<?php

use Fuel\Core\Database_Exception;
use Orm\ValidationFailed;

class Controller_Infos extends Controller_Base {
	const VERSION_CODE = 1;
	const VERSION_NAME = "1.0";

	public function get_version() {
		$this->result['version_code'] = self::VERSION_CODE;
		$this->result['version_name'] = self::VERSION_NAME;
		return $this->response($this->result);
	}
}