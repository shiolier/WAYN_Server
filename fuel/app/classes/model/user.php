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

	protected static $_has_many = array(
		'requests' => array(
			'model_to' => 'Model_Participation_Request',
			'key_from' => 'id',
			'key_to' => 'user_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
	);

	protected static $_many_many = array(
		'groups' => array(
			'model_to' => 'Model_Group',
			'key_from' => 'id',
			'key_to' => 'id',
			'table_through' => 'groups_users',
			'key_through_from' => 'user_id',
			'key_through_to' => 'group_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
	);

	protected static $_to_array_exclude = array(
		'password',
		'updated_at',
		'groups',
		'requests',
	);

	public function updated_location_at($format = null) {
		return $this->date_format('updated_location_at', $format);
	}

	public static function auth($id, $password) {
		return self::find('first', array(
			'where' => array(
				'id' => $id,
				'password' => sha1($password),
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

	public function is_participation($group_id) {
		foreach ($this->groups as $group) {
			if ($group->id == $group_id) {
				return true;
			}
		}
		return false;
	}

	public function is_request($group_id) {
		foreach ($this->requests as $request) {
			if ($request->group_id == $group_id) {
				return true;
			}
		}
		return false;
	}
}
