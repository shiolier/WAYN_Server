<?php

class Model_User extends Model_Base {
	protected static $_properties = array(
		'id',
		'name' => array(
			'data_type' => 'varchar',
			'validation' => array(
				'required',
				'min_length' => array(1),
				'max_length' => array(100),
			),
		),
		'password' => array(
			'data_type' => 'varchar',
			'validation' => array(
				'required',
			),
		),
		'latitude',
		'longitude',
		'altitude',
		'updated_location_at',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_Validation' => array(
			'events' => array('before_save'),
		),
	);

	protected static $_table_name = 'users';

	protected static $_to_array_exclude = array(
		'password',
		'updated_at',
	);

	public function updated_location_at($format = null) {
		return $this->date_format('updated_location_at', $format);
	}

	public static function auth($id, $password) {
		return self::find('first', array(
			'where' => array(
				'id' => $id,
				'password' => self::password_to_hash($password),
			),
		));
	}

	/**
	 * 位置情報の更新
	 * @param $params
	 * 		latitude	double
	 * 		longitude	double
	 * 		altitude	double
	 */
	public function update_location($params) {
		$this->latitude = $params['latitude'];
		$this->longitude = $params['longitude'];
		$this->altitude = $params['altitude'];
		$this->updated_location_at = time();

		return $this->save();
	}
}
