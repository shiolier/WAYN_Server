<?php

class Model_Group extends Model_Base {
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
		'leader_id' => array(
			'data_type' => 'integer',
			'validation' => array(
				'required',
			),
		),
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

	protected static $_table_name = 'groups';

	protected static $_has_many = array(
		'requests' => array(
			'model_to' => 'Model_Participation_Request',
			'key_from' => 'id',
			'key_to' => 'group_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
	);

	protected static $_many_many = array(
		'users' => array(
			'model_to' => 'Model_User',
			'key_from' => 'id',
			'key_to' => 'id',
			'table_through' => 'groups_users',
			'key_through_from' => 'group_id',
			'key_through_to' => 'user_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
	);

	protected static $_to_array_exclude = array(
		'updated_at',
		'users',
		'requests',
	);

}
