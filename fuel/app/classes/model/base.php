<?php

use Orm\Model;

class Model_Base extends Model {
	const DATE_FORMAT = 'Y/m/d H:i:s';

	public function created_at($format = null) {
		return $this->date_format('created_at', $format);
	}
	public function updated_at($format = null) {
		return $this->date_format('updated_at', $format);
	}
	public function deleted_at($format = null) {
		return $this->date_format('deleted_at', $format);
	}

	public function date_format($key, $format = null) {
		if(empty($this->$key)) {
			return null;
		}
		if($format === false) {
			return $this->$key;
		}
		if(empty($format)) {
			$format = static::DATE_FORMAT;
		}
		return date($format, $this->$key);
	}

	public static function find_by_id($id) {
		return static::find('first', array(
			'where' => array(
				array('id', '=', $id),
			),
		));
	}
}