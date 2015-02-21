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

	protected static $_to_array_exclude = array(
		'updated_at',
	);

}
