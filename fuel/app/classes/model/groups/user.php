<?php

class Model_Groups_User extends Model_Base {
	protected static $_properties = array(
		'id',
		'user_id' => array(
			'data_type' => 'integer',
			'validation' => array(
				'required',
			),
		),
		'group_id' => array(
			'data_type' => 'integer',
			'validation' => array(
				'required',
			),
		),
		'approve',
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

	protected static $_table_name = 'groups_users';

}
